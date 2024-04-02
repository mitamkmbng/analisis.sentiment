<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\CrossValidation\Metrics\MCC;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Metrics\Accuracy;
use Rubix\ML\Datasets\Unlabeled;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureExtraction\TfidfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Tokenization\Tokenizer;
use Phpml\Tokenization\WordTokenizer;
use Rubix\ML\Classifiers\NaiveBayes;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Transformers\NumericStringConverter;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Dataset;

class Modeling extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Mcrud');
    }

    public function index() {
        
    }

    public function klasifikasi()
    {
        $data['cleaning'] = $this->db->query('SELECT * FROM dataset a, cleaning b where a.id_dataset=b.id_dataset');
        $data['after'] = $this->db->query('SELECT * FROM data_after');
        
        // Ambil data report dari database
        $report = $this->db->get_where('report', ['id_report' => 1])->row();
    
        // Jika data report ditemukan, ambil nilai accuracy dan confusion_matrix
        if ($report) {
            $accuracy = $report->accuracy;
            $confusionMatrix = json_decode($report->confusion_matrix, true);
            $f1_score = $report->f1_score;
            $precision = $report->precision;
            $recall = $report->recall;
        } else {
            $accuracy = null;
            $confusionMatrix = [
                'Negatif' => [
                    'Negatif' => 0,
                    'Netral' => 0,
                    'Positif' => 0
                ],
                'Netral' => [
                    'Negatif' => 0,
                    'Netral' => 0,
                    'Positif' => 0
                ],
                'Positif' => [
                    'Negatif' => 0,
                    'Netral' => 0,
                    'Positif' => 0
                ]
            ];
            $f1_score = null;
            $precision = null;
            $recall = null;
        }

        // Mengambil data evaluasi dari tabel 'evaluasi'
        $evaluasi = $this->Mcrud->get_data('hasildata')->result();
        
        // Menghitung jumlah data evaluasi dengan masing-masing label
        $jumlah_negatif = 0;
        $jumlah_netral = 0;
        $jumlah_positif = 0;
        foreach ($evaluasi as $item) {
            if ($item->sentimen == 'Negatif') {
                $jumlah_negatif++;
            } elseif ($item->sentimen == 'Netral') {
                $jumlah_netral++;
            } elseif ($item->sentimen == 'Positif') {
                $jumlah_positif++;
            }
        }
        
        // Menyimpan jumlah data evaluasi dengan masing-masing label ke dalam array
        $data['jumlah_evaluasi'] = [
            'Negatif' => $jumlah_negatif,
            'Netral' => $jumlah_netral,
            'Positif' => $jumlah_positif
        ];
    
        $data['accuracy'] = $accuracy;
        $data['confusionMatrix'] = $confusionMatrix;
        $data['f1_score'] = $f1_score;
        $data['precision'] = $precision;
        $data['recall'] = $recall;

        $data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');
    
        $this->load->view('admin/header', $data);
        $this->load->view('admin/klasifikasi', $data);
        $this->load->view('admin/footer');
    }
    

    public function trainModel()
    {
        // Cek apakah tabel data_training kosong
		$data_training = $this->db->query("SELECT * FROM data_training")->result();
		if (empty($data_training)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan splitting data terlebih dahulu!</div></div>');
			redirect('admin/data_split');
			return; // Hentikan eksekusi fungsi label_act()
		}

        ini_set('memory_limit', '8G');
        $this->load->database();
    
        // Ambil data dari tabel data_training
        $this->db->select('tfidf, sentimen');
        $dataTraining = $this->db->get('data_training')->result_array();
    
        // Split data menjadi fitur (tfidf) dan label (sentimen)
        $samples = [];
        $labels = [];
        foreach ($dataTraining as $row) {
            $samples[] = json_decode($row['tfidf'], true); // ini sudah berupa vector TF-IDF
            $labels[] = $row['sentimen']; // ini label 'positif' 'negatif' dan 'netral'
        }
    
        $dataset = new Labeled($samples, $labels);
    
        // Convert numeric strings to float values
        $numericConverter = new NumericStringConverter();

        $uniqueTypes = $dataset->uniqueTypes();

        foreach ($uniqueTypes as $column => $type) {
            if ($type === 'continuous') {
                $samples = $dataset->samples();

                foreach ($samples as &$sample) {
                    $sample[$column] = $numericConverter->transform($sample[$column]);
                }

                unset($sample);

                $dataset = new Dataset($samples, $labels());
            }
        }

        // Train the Gaussian Naive Bayes classifier
        $estimator = new GaussianNB();

        $estimator->train($dataset);

        // Simpan model untuk digunakan kembali pada tahap berikutnya
        $modelPath = APPPATH . 'models/naive_bayes.php';
        $persister = new Filesystem($modelPath);
        $model = new PersistentModel($estimator, $persister);
        $model->save();

        $this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan !</div></div>');
		redirect('modeling/klasifikasi');
    }

    public function evaluateModel()
    {
        $this->db->empty_table('report');
        $this->db->empty_table('data_after');

        // Load the test dataset
        $this->db->select('id_testing, text, tfidf, sentimen');
        $dataTesting = $this->db->get('data_testing')->result_array();

        $samples = [];
        $labels = [];
        $dataIds = [];
        foreach ($dataTesting as $row) {
            $samples[] = json_decode($row['tfidf'], true);
            $labels[] = $row['sentimen'];
            $text[] = $row['text'];
            $dataIds[] = $row['id_testing'];
        }

        $dataset = new Labeled($samples, $labels);

        // Load the trained model
        $modelPath = APPPATH . 'models/naive_bayes.php';
        $persister = new Filesystem($modelPath);
        $model = PersistentModel::load($persister);

        // Predict labels using the model
        $predictions = $model->predict($dataset);

        // Save the predictions to the 'sesudah' column in 'data_after' table
        foreach ($dataIds as $index => $dataId) {
            $prediction = $predictions[$index];
            $label = $labels[$index];
            $teks = $text[$index];

            $data = array(
                'text' => $teks,
                'sentimen' => $label,
                'sesudah' => $prediction,
            );

            $this->db->insert('data_after', $data);
        }

        // Calculate accuracy
        $accuracy = new Accuracy();
        $accuracyValue = $accuracy->score($predictions, $labels);

        // Calculate confusion matrix
        $confusionMatrix = new ConfusionMatrix();
        $report = $confusionMatrix->generate($predictions, $labels);

        $score = new MulticlassBreakdown();
        $scoreValue = $score->generate($predictions, $labels);

        // Mendapatkan metrik dari laporan multiclass breakdown
        $overallF1Score = $scoreValue['overall']['f1 score'];
        $overallPrecision = $scoreValue['overall']['precision'];
        $overallRecall = $scoreValue['overall']['recall'];

        // Save the report to the database
        $id_report = 1;

        $data = array(
            'accuracy' => $accuracyValue,
            'confusion_matrix' => json_encode($report->toArray()),
            'f1_score' => $overallF1Score,
            'precision' => $overallPrecision,
            'recall' => $overallRecall,
        );

        $this->db->where('id_report', $id_report);
        $existingReport = $this->db->get('report')->row();

        if ($existingReport) {
            // Update existing report
            $this->db->where('id_report', $id_report);
            $this->db->update('report', $data);
        } else {
            // Insert new report
            $data['id_report'] = $id_report;
            $this->db->insert('report', $data);
        }

        // Set flash data and redirect
        $this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
        redirect('modeling/klasifikasi');
    }

    public function test_model()
    {
        // Mengambil data uji dari formulir
        $testData = $this->input->post('testData');
        $testDataArray = [$testData];
    
        if (!empty($testDataArray)) {
            // Load the trained model
            $modelPath = APPPATH . 'models/naive_bayes.php';
            $persister = new Filesystem($modelPath);
            $model = PersistentModel::load($persister);
    
            $stemming = $this->db->query("SELECT * FROM stemming")->result();
    
            // Inisialisasi array documents
            $documents = [];
    
            foreach ($stemming as $row) {
                $teks = $row->text_stemming;
    
                // Tokenizing data
                $tokenizer = new WordTokenizer();
                $tokens = $tokenizer->tokenize($teks);
    
                // Tambahkan token ke dalam array documents
                $documents[] = implode(' ', $tokens);
            }
    
            // Menerapkan tokenisasi pada data
            $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
    
            // Menerapkan tokenisasi pada data
            $vectorizer->fit($documents);
            $vectorizer->transform($documents);
            $vectorizer->transform($testDataArray);
    
            // Membuat objek TfIdfTransformer
            $tfidf_transformer = new TfIdfTransformer($documents);
            $tfidf_transformer->fit($documents);
    
            // Menerapkan tf-idf pada data uji
            $tfidf_transformer->transform($testDataArray);
    
            // Preprocess the test data
            $preprocessedData = new Unlabeled($testDataArray);
    
            // Predict label using the model
            $prediction = $model->predict($preprocessedData);
    
            // Store the test result
            $testResult = '';
            if ($prediction[0] === 'Negatif') {
                $testResult = 'Data tersebut diklasifikasikan sebagai Negatif.';
            } elseif ($prediction[0] === 'Netral') {
                $testResult = 'Data tersebut diklasifikasikan sebagai Netral.';
            } elseif ($prediction[0] === 'Positif') {
                $testResult = 'Data tersebut diklasifikasikan sebagai Positif.';
            }  
            // Menyimpan hasil pengujian dalam variabel $data untuk ditampilkan di view
            $data['test'] = true;
            $data['test_result'] = $testResult;
        } else {
            // Menyimpan informasi bahwa data uji belum dimasukkan
            $data['test'] = false;
        }
    
        // Ambil data report dari database
        $report = $this->db->get_where('report', ['id_report' => 1])->row();
        $data['after'] = $this->db->query('SELECT * FROM data_after');
    
        // Jika data report ditemukan, ambil nilai accuracy dan confusion_matrix
        if ($report) {
            $accuracy = $report->accuracy;
            $confusionMatrix = json_decode($report->confusion_matrix, true);
            $f1_score = $report->f1_score;
            $precision = $report->precision;
            $recall = $report->recall;
        } else {
            $accuracy = null;
            $confusionMatrix = null;
            $f1_score = null;
            $precision = null;
            $recall = null;
        }

        // Mengambil data evaluasi dari tabel 'evaluasi'
        $evaluasi = $this->Mcrud->get_data('hasildata')->result();
        
        // Menghitung jumlah data evaluasi dengan masing-masing label
        $jumlah_negatif = 0;
        $jumlah_netral = 0;
        $jumlah_positif = 0;
        foreach ($evaluasi as $item) {
            if ($item->sentimen == 'Negatif') {
                $jumlah_negatif++;
            } elseif ($item->sentimen == 'Netral') {
                $jumlah_netral++;
            } elseif ($item->sentimen == 'Positif') {
                $jumlah_positif++;
            }
        }
        
        // Menyimpan jumlah data evaluasi dengan masing-masing label ke dalam array
        $data['jumlah_evaluasi'] = [
            'Negatif' => $jumlah_negatif,
            'Netral' => $jumlah_netral,
            'Positif' => $jumlah_positif
        ];
    
        $data['accuracy'] = $accuracy;
        $data['confusionMatrix'] = $confusionMatrix;
        $data['f1_score'] = $f1_score;
        $data['precision'] = $precision;
        $data['recall'] = $recall;

        $data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');
    
        $this->load->view('admin/header', $data);
        $this->load->view('admin/klasifikasi', $data);
        $this->load->view('admin/footer');
    }    
    
}
