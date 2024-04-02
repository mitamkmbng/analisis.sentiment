<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Carbon\Carbon;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureExtraction\TfidfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Tokenization\Tokenizer;
use Phpml\Tokenization\WordTokenizer;

use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Sastrawi\Dictionary\ArrayDictionary;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\Tokenizer\TokenizerFactory;



class Admin extends CI_Controller {
	function __construct(){
		parent:: __construct();
		$this->load->model('Mcrud');
		$this->load->library(array('session'));
		$this->load->library('pagination');
		if($this->session->userdata('level')!='admin'){
			redirect('login');
		}
	}
	
	public function index()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');

		$this->load->view('admin/header', $data);
		$this->load->view('admin/dashboard', $data);
		$this->load->view('admin/footer');
	}

	public function upload_data()
	{
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'xls|xlsx|csv';
		$config['max_size']             = 1024;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file_upload')) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
			redirect('admin');
		} else {
			$data = array('upload_data' => $this->upload->data());

			$inputFileName = $data['upload_data']['full_path'];
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
			$worksheet = $spreadsheet->getActiveSheet();
			$highestRow = $worksheet->getHighestRow();

			$data = array();
			for ($row = 2; $row <= $highestRow; ++$row) {
				$userid = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$text = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
				$skor = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
		
				$data[] = array(
					'userid' => $userid,
					'text' => $text,
					'skor' => $skor,
				);
			}
		
			$this->db->insert_batch('dataset', $data);
		
			$this->session->set_flashdata('status', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Di Import ke Database !</div></div>');
			redirect('admin');
		}
	}

	public function import_excel(){
		date_default_timezone_set('Asia/Jakarta');
		if (isset($_FILES["fileExcel"]["name"])) {
			$path = $_FILES["fileExcel"]["tmp_name"];
			$object = IOFactory::createReader('Xlsx')->setReadDataOnly(true)->load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();	
				for($row=2; $row<=$highestRow; $row++)
				{
					$datetime = $worksheet->getCell("A" . $row)->getValue();
					$userid = $worksheet->getCell("B" . $row)->getValue();
					$text = $worksheet->getCell("C" . $row)->getValue();
										
					$temp_data[] = array(
						'datetime'=>$datetime,
						'userid'=>$userid,
						'text'=>$text,
					);
				}
			}

			$insert = $this->Mcrud->insert($temp_data);
			if($insert){
				$this->session->set_flashdata('status', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Di Import ke Database !</div></div>');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('status', '<div class="col-md-12" ><div class="alert alert-danger alert-message" align="center">Data Gagal Di Import !</div></div>');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			echo "Tidak ada file yang masuk";
		}
	}

	public function reset()
	{
		$kosong=$this->db->query("TRUNCATE dataset");
		$this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Direset !</div></div>');
			redirect('admin');
		
	}

	public function data_cleaning()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');
		$data['cleaning'] = $this->db->query('SELECT * FROM dataset a, cleaning b where a.id_dataset=b.id_dataset');	
		$data['stopwords'] = $this->db->query('SELECT * FROM cleaning a, stopwords b where a.id_cleaning=b.id_cleaning'); 	
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_cleaning', $data);
		$this->load->view('admin/footer');
	}

	public function data_stopwords()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');
		$data['cleaning'] = $this->db->query('SELECT * FROM dataset a, cleaning b where a.id_dataset=b.id_dataset');	
		$data['stopwords'] = $this->db->query('SELECT * FROM cleaning a, stopwords b where a.id_cleaning=b.id_cleaning'); 	
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_stopwords', $data);
		$this->load->view('admin/footer');
	}

	public function data_stemming()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');
		$data['cleaning'] = $this->db->query('SELECT * FROM dataset a, cleaning b where a.id_dataset=b.id_dataset');	
		$data['stopwords'] = $this->db->query('SELECT * FROM cleaning a, stopwords b where a.id_cleaning=b.id_cleaning'); 	
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_stemming', $data);
		$this->load->view('admin/footer');
	}

	public function cleaning_act()
	{
		$dataset = $this->db->query("SELECT * FROM dataset")->result();
	
		// Cek apakah tabel dataset kosong
		if (empty($dataset)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap unggah data terlebih dahulu!</div></div>');
			redirect('admin');
			return; // Hentikan eksekusi fungsi cleaning_act()
		}
	
		$this->db->empty_table('cleaning');
		foreach ($dataset as $row) {
			$id_dataset = $row->id_dataset;
			$teks = $row->text;
	
			// Membersihkan data
			$teks = strtolower(trim($teks));
			$teks = preg_replace('/https?:\/\/[^\s]+/', '', $teks);
			$teks = preg_replace('/www\.[^\s]+/', '', $teks);
			$teks = preg_replace('/\s+/', ' ', $teks);
			$teks = str_replace('-', ' ', $teks); // Mengganti tanda hubung dengan spasi
			$teks = preg_replace('/[^a-z\s]+/', '', $teks);
			$teks = preg_replace('/\d/', '', $teks);
			$teks = preg_replace('/\s\s+/', ' ', $teks);
	
			$data = array('id_dataset' => $id_dataset, 'text_cleaning' => $teks);
			$add = $this->Mcrud->tambah('cleaning', $data);
		}
	
		if ($add > 0) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
			redirect('admin/data_cleaning');
		} else {
			// Tangani jika tidak ada data yang ditambahkan
		}
	}
	
	public function stopwords_act()
	{
		$cleaning = $this->db->query("SELECT * FROM cleaning")->result();
	
		// Cek apakah tabel cleaning kosong
		if (empty($cleaning)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap membersihkan data terlebih dahulu!</div></div>');
			redirect('admin/data_stopwords');
			return; // Hentikan eksekusi fungsi stopwords_act()
		}
	
		$this->db->empty_table('stopwords');
		foreach ($cleaning as $row) {
			$id_cleaning = $row->id_cleaning;
			$teks = $row->text_cleaning;
	
			// Tahap stopwords
			$factory = new StopWordRemoverFactory();
			$stopword = $factory->createStopWordRemover();
			$teks = $stopword->remove($teks);
	
			$data = array('id_cleaning' => $id_cleaning, 'text_stopwords' => $teks);
			$add = $this->Mcrud->tambah('stopwords', $data);
		}
	
		if ($add > 0) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
			redirect('admin/data_stopwords');
		} else {
			// Tangani jika tidak ada data yang ditambahkan
		}
	}	
	
	public function stemming_act()
	{
		$stopwords = $this->db->query("SELECT * FROM stopwords")->result();
	
		// Cek apakah tabel stopwords kosong
		if (empty($stopwords)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan stopwords removal terlebih dahulu!</div></div>');
			redirect('admin/data_stemming');
			return; // Hentikan eksekusi fungsi stemming_act()
		}
	
		$this->db->empty_table('stemming');
	
		// create stemmer
		$stemmerFactory = new StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();
	
		// create tokenizer
		$tokenizer = new WhitespaceTokenizer();
	
		foreach ($stopwords as $row) {
			$id_stopwords = $row->id_stopwords;
			$teks = $row->text_stopwords;
	
			// tokenize
			$tokens = $tokenizer->tokenize($teks);
	
			// stemming
			$stemmedTokens = array_map([$stemmer, 'stem'], $tokens);
			$teks = implode(' ', $stemmedTokens);
	
			$data = array('id_stopwords' => $id_stopwords, 'text_stemming' => $teks);
			$add = $this->Mcrud->tambah('stemming', $data);
		}
	
		if ($add > 0) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
			redirect('admin/data_stemming');
		} else {
			// Tangani jika tidak ada data yang ditambahkan
		}
	}	

	public function data_tfidf()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');	
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_tfidf', $data);
		$this->load->view('admin/footer');
	}
	
	public function tfidf_act()
	{
		ini_set('memory_limit', '4096M');
	
		// Cek apakah tabel stemming kosong
		$stemming = $this->db->query("SELECT * FROM stemming")->result();
		if (empty($stemming)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan stemming data terlebih dahulu!</div></div>');
			redirect('admin/data_stemming');
			return; // Hentikan eksekusi fungsi tfidf_act()
		}

		$this->db->empty_table('tfidf');
		// Ambil data stemming
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
	
		// Buat instance TokenCountVectorizer dengan menghapus kata stopword
		$vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
	
		// Menerapkan tokenisasi pada data
		$vectorizer->fit($documents);
		$vectorizer->transform($documents);

		$vocab = $vectorizer->getVocabulary();

        // Simpan vocab ke database
        $voc = array(
            'vocab' => json_encode($vocab)
        );
        $where = array('id' => 1);
		$result = $this->db->query("SELECT * FROM vocab")->result();
		if (!empty($result)) {
			$this->Mcrud->update_data('vocab', $voc, $where);
		} else {
			$this->Mcrud->insert_data('vocab', $voc);
		}
	
		// Buat objek TfIdfTransformer
		$tfidf_vectors = new TfIdfTransformer($documents);
	
		// Menerapkan tf-idf pada data
		$tfidf_vectors->transform($documents);
	
		// Menyimpan hasil tokenizing dan tf-idf ke database
		foreach ($stemming as $index => $row) {
			$id_stemming = $row->id_stemming;
			$tokens = $documents[$index];
	
			$data = array(
				'id_stemming' => $id_stemming,
				'text_tfidf' => json_encode($tokens)
			);
	
			$add = $this->Mcrud->tambah('tfidf', $data);
		}
	
		if ($add > 0) {
			$this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan !</div></div>');
			redirect('admin/data_tfidf');
		} else {
			// handle error
		}
	}

	public function vocab() {
		$result = $this->db->query("SELECT * FROM vocab")->result();
		$vocab = !empty($result) ? json_decode($result[0]->vocab) : array();
		$wordFrequencies = $this->calculateWordFrequencies($vocab);
	
		// Menyiapkan data untuk dikirim ke view
		$data['vocab'] = $vocab;
		$data['wordFrequencies'] = $wordFrequencies;
	
		// Mengurutkan frekuensi kata dari yang terbanyak
		arsort($data['wordFrequencies']);
	
		// Pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/vocab');
		$config['total_rows'] = count($data['wordFrequencies']);
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
	
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');	
	
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_vocab', $data);
		$this->load->view('admin/footer');
	}
	
	private function calculateWordFrequencies($vocab) {
		// Mengambil data teks dari database
		$result = $this->db->query("SELECT * FROM stemming")->result();
		$text = '';
		foreach ($result as $row) {
			$text .= $row->text_stemming . ' ';
		}
	
		// Memecah teks menjadi kata-kata
		$words = explode(' ', strtolower($text));
	
		// Menginisialisasi array frekuensi kata
		$wordFrequencies = array();
	
		// Menghitung frekuensi kata berdasarkan kamus
		foreach ($vocab as $word) {
			$frequency = 0;
			foreach ($words as $w) {
				if ($w === $word) {
					$frequency++;
				}
			}
			$wordFrequencies[$word] = $frequency;
		}
	
		return $wordFrequencies;
	}	

	public function wordcloud() {
		$result = $this->db->query("SELECT * FROM vocab")->result();
		$vocab = !empty($result) ? json_decode($result[0]->vocab) : array();
		$wordFrequencies = $this->calculateWordFrequencies($vocab);
	
		// Menyiapkan data untuk dikirim ke view
		$data['vocab'] = $vocab;
		$data['wordFrequencies'] = $wordFrequencies;

		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');	
	
		$this->load->view('admin/header', $data);
		$this->load->view('admin/wordcloud', $data);
		$this->load->view('admin/footer');
	}

	public function data_label()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');

		$data['label'] = $this->db->query('SELECT * FROM hasildata');        
			
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_label', $data);
		$this->load->view('admin/footer');
	}
	
	public function label_act()
	{
		// Cek apakah tabel tfidf kosong
		$tfidf = $this->db->query("SELECT * FROM tfidf")->result();
		if (empty($tfidf)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan tfidf data terlebih dahulu!</div></div>');
			redirect('admin/data_tfidf');
			return; // Hentikan eksekusi fungsi label_act()
		}

		// Ambil data evaluasi dari database
		$data_evaluasi = $this->db->query("SELECT * FROM stemming")->result();
		$this->db->empty_table('hasildata');
	
		$lexicon_file_new = APPPATH . 'lexicon/new_lexicon.csv';
	
		$lexicon_new = array();
	
		// Load file tsv new
		if (($handle = fopen($lexicon_file_new, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 15000, ",")) !== FALSE) {
				$lexicon_new[$data[0]] = $data[1];
			}
			fclose($handle);
		}
	
		// Menggabungkan kedua array
		$lexicon = array_merge($lexicon_new);
	
		// Lakukan labeling otomatis untuk setiap data evaluasi
		foreach ($data_evaluasi as $evaluasi) {
			$ulasan = $evaluasi->text_stemming;
	
			// Lakukan preprocessing pada $ulasan
			$ulasan = strtolower($ulasan); // Ubah huruf besar menjadi huruf kecil
			$ulasan = preg_replace('/[^a-zA-Z0-9\s]/', '', $ulasan); // Hapus karakter selain huruf dan angka
			$ulasan = preg_replace('/\s\s+/', ' ', $ulasan); // Hapus spasi berlebih
			$ulasan = trim($ulasan); // Hapus spasi di awal dan akhir teks
	
			// Hitung skor sentiment
			$skor_sentiment = 0;
			$jumlah_kata = 0;
			$kata_positif = 0;
			$kata_negatif = 0;
	
			$kata = str_word_count($ulasan, 1);
			foreach ($kata as $kata_evaluasi) {
				if (isset($lexicon[$kata_evaluasi])) {
					$skor_sentiment += $lexicon[$kata_evaluasi];
					$jumlah_kata++;
	
					if ($lexicon[$kata_evaluasi] > 0) {
						$kata_positif++;
					} elseif ($lexicon[$kata_evaluasi] < 0) {
						$kata_negatif++;
					}
				} else {
					$jumlah_kata++;
				}
			}
	
			if ($skor_sentiment > -6) {
				$label_sentiment = 'Positif';
			} elseif ($skor_sentiment < -9) {
				$label_sentiment = 'Negatif';
			} else {
				$label_sentiment = 'Netral';
			}
	
			$data_update = array(
				'text' => $ulasan,
				'sentimen' => $label_sentiment
			);
			// $where = array(
			// 	'id_hasildata' => $evaluasi->id_stemming
			// );
			$this->Mcrud->insert_data('hasildata', $data_update);
		}
	
		$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
		redirect('admin/data_label');
	}

	public function empty_data_set() {
		$this->Mcrud->empty_table('dataset');
		$this->Mcrud->empty_table('cleaning');
		$this->Mcrud->empty_table('stopwords');
		$this->Mcrud->empty_table('stemming');
		$this->Mcrud->empty_table('tfidf');
		$this->Mcrud->empty_table('hasildata');
		$this->Mcrud->empty_table('split');
		$this->Mcrud->empty_table('data_training');
		$this->Mcrud->empty_table('data_testing');
		$this->Mcrud->empty_table('data_after');
		$this->Mcrud->empty_table('vocab');
		$this->Mcrud->empty_table('report');
		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Data berhasil dikosongkan!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect('admin');
	}
	public function empty_data_label() {
		$table_name = 'hasildata';
		$this->Mcrud->empty_table($table_name);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Data berhasil dikosongkan!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect('admin/data_label');
	}
	public function empty_data_split() {
		$table_name = 'split';
		$this->Mcrud->empty_table($table_name);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Data berhasil dikosongkan!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect('admin/data_split');
	}


	public function data_split()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');

		$data['split'] = $this->db->query('SELECT * FROM split');        
			
		$this->load->view('admin/header', $data);
		$this->load->view('admin/split_data', $data);
		$this->load->view('admin/footer');
	}

	public function mergeData()
	{
		// Cek apakah tabel hasildata kosong
		$hasildata = $this->db->query("SELECT * FROM hasildata")->result();
		if (empty($hasildata)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan labelling data terlebih dahulu!</div></div>');
			redirect('admin/data_label');
			return; // Hentikan eksekusi fungsi label_act()
		}

		$this->db->empty_table('split');
		// Ambil data dari tabel tfidf
		$tfidfData = $this->db->query('SELECT * FROM tfidf')->result();
	
		// Ambil data dari tabel hasildata
		$hasilData = $this->db->query('SELECT * FROM hasildata')->result();
	
		// Buat associative array untuk hasildata berdasarkan id_hasildata
		$hasilDataArray = array();
		foreach ($hasilData as $hasil) {
			$hasilDataArray[$hasil->id_hasil] = $hasil;
		}
	
		// Lakukan penggabungan data dan masukkan ke dalam tabel split
		foreach ($tfidfData as $tfidf) {
			if (isset($hasilDataArray[$tfidf->id_tfidf])) {
				$hasil = $hasilDataArray[$tfidf->id_tfidf];
				$data = array(
					'text' => $hasil->text,
					'sentimen' => $hasil->sentimen,
					'tfidf' => $tfidf->text_tfidf
				);
	
				// Masukkan data ke dalam tabel split
				$this->Mcrud->insert_data('split', $data);
			}
		}
	
		// Set flashdata dan redirect
		$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
		redirect('admin/data_split');
	}
	
	
	public function split_act()
	{
		// Cek apakah tabel split kosong
		$split = $this->db->query("SELECT * FROM split")->result();
		if (empty($split)) {
			$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-danger alert-message" align="center">Harap melakukan ambil data terlebih dahulu!</div></div>');
			redirect('admin/data_split');
			return; // Hentikan eksekusi fungsi label_act()
		}

		// Get all evaluation data from database
		$evaluation_data = $this->db->query('SELECT * FROM split')->result();
		$this->db->empty_table('data_training');
		$this->db->empty_table('data_testing');
		// Get the split ratio from the form submission
		$split_ratio = $this->input->get('split_ratio');
	
		// Split the data into training and testing data with a 80:20 ratio
		$training_data = array();
		$testing_data = array();
		foreach ($evaluation_data as $data) {
			$split_data = array(
				'text' => $data->text,
				'sentimen' => $data->sentimen,
				'tfidf' => $data->tfidf
			);
			if (rand(0, 99) < $split_ratio) {
				$training_data[] = $split_data;
			} else {
				$testing_data[] = $split_data;
			}
		}
	
		// Save the training data to the database
		foreach ($training_data as $data) {
			$this->Mcrud->insert_data('data_training', $data);
		}
	
		// Save the testing data to the database
		foreach ($testing_data as $data) {
			$this->Mcrud->insert_data('data_testing', $data);
		}
	
		// Set flashdata dan redirect
		$this->session->set_flashdata('suces', '<div class="col-md-12"><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan!</div></div>');
		redirect('admin/data_split');
	}

	public function data_training()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');

		$data['label'] = $this->db->query('SELECT * FROM data_training');        
			
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_training', $data);
		$this->load->view('admin/footer');
	}
		
	public function data_testing()
	{
		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');

		$data['label'] = $this->db->query('SELECT * FROM data_testing');        
			
		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_testing', $data);
		$this->load->view('admin/footer');
	}
	
	public function administrator()
	{
		$data['admin'] = $this->Mcrud->getadmin();

		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');	

		$this->load->view('admin/header', $data);
		$this->load->view('admin/data_administrator', $data);
		$this->load->view('admin/footer');
	}

	public function tambahadmin(){	
			
		$nama= $_POST['nama'];
		$level= $_POST['level'];
		$status= $_POST['status'];
		$username= $_POST['username'];
		$password= $_POST['password'];
		
		$data = array('nama'=>$nama, 'status'=>$status, 'level'=>$level, 'username'=>$username, 'password'=>$password);
		$add = $this->Mcrud->tambah('user',$data);
		if($add > 0){
			 $this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Disimpan !</div></div>');
			 redirect('admin/administrator');
			 
		}else{
			
		}
	}
	
	public function editadmin($id){
		
		$nama= $_POST['nama'];
		$level= $_POST['level'];
		$status= $_POST['status'];
		$username= $_POST['username'];
		$password= $_POST['password'];
		
		$data = 'nama="'.$nama.'", level="'.$level.'", status="'.$status.'",username="'.$username.'", password="'.$password.'"';
		$edit = $this->Mcrud->update('user', $data, "id_user='$id'");
			 $this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-success alert-message" align="center">Data Berhasil Diedit !</div></div>');
			 redirect ('admin/administrator');
	}

	public function hapusadmin($id){
		$data= "id_user='$id'";
		$hapus = $this->Mcrud->hapus('user', $data);
			 
			 $this->session->set_flashdata('suces', '<div class="col-md-12" ><div class="alert alert-danger alert-message" align="center">Data Berhasil Dihapus !</div></div>');
			 redirect('admin/administrator');
			 
		}
	
	public function gantipassword()
	{

		$data['total'] = $this->db->query('SELECT * FROM  dataset');		
		$data['stemming'] = $this->db->query('SELECT * FROM stopwords a, stemming b where a.id_stopwords=b.id_stopwords'); 
		$data['tfidf'] = $this->db->query('SELECT * FROM stemming a, tfidf b where a.id_stemming=b.id_stemming');	
		$data['hasildata'] = $this->db->query('SELECT * FROM hasildata'); 
		$data['training'] = $this->db->query('SELECT * FROM data_training');	
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/ganti_password');
		$this->load->view('admin/footer');
	}

	function gantipassword_act(){
		//data yang terekam pada method post atau yang kita ketikan pada inputan
		$id_admin = $this->session->id_admin;
		$username = $this->input->post('username');
		$pass_baru = $this->input->post('pass_baru');
		$ulang_pass = $this->input->post('ulang_pass');
		//proses validasi ganti dan ulangi password password
		$this->form_validation->set_rules('pass_baru','Password Baru','required|matches[ulang_pass]');
		$this->form_validation->set_rules('ulang_pass','Ulangi Password Baru','required');
		
		if($this->form_validation->run() != false){
			$data = 'username="'.$username.'", password="'.$pass_baru.'"';
			$this->Mcrud->update('admin', $data, "id_admin='$id_admin'");
			$this->session->set_flashdata('pesan', '<div class="alert alert-success">Password telah diupdate!</div>');
			redirect('admin/gantipassword');
		}else{
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal, terjadi kesalahan! pastikan ulangi password benar</div>');
			redirect('admin/gantipassword');
		}
	}
	
}
