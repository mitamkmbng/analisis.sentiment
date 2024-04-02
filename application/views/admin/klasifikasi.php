<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Klasifikasi Naive Bayes</h6>
        </div>
        <div class="card-body">
            <?php echo $this->session->flashdata('suces') ?>
            <a href="<?= site_url('modeling/evaluateModel') ?>" class="btn btn-light btn-icon-split">
                <span class="icon text-gray-600">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Proses</span>
            </a>
            <br><br>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Jumlah Evaluasi (Dari Seluruh Data)</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <canvas id="chart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Classification Report
                        </div>
                        <div class="card-body">
                            <pre>Accuracy: <?php echo $accuracy; ?></pre>
                            <pre>F1 Score: <?php echo $f1_score; ?></pre>
                            <pre>Recall: <?php echo $recall; ?></pre>
                            <pre>Precision: <?php echo $precision; ?></pre>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Confusion Matrix
                        </div>
                        <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Predict Negatif</th>
                                    <th>Predict Netral</th>
                                    <th>Predict Positif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Actual Negatif</th>
                                    <td><?php echo $confusionMatrix['Negatif']['Negatif']; ?></td>
                                    <td><?php echo $confusionMatrix['Negatif']['Netral']; ?></td>
                                    <td><?php echo $confusionMatrix['Negatif']['Positif']; ?></td>
                                </tr>
                                <tr>
                                    <th>Actual Netral</th>
                                    <td><?php echo $confusionMatrix['Netral']['Negatif']; ?></td>
                                    <td><?php echo $confusionMatrix['Netral']['Netral']; ?></td>
                                    <td><?php echo $confusionMatrix['Netral']['Positif']; ?></td>
                                </tr>
                                <tr>
                                    <th>Actual Positif</th>
                                    <td><?php echo $confusionMatrix['Positif']['Negatif']; ?></td>
                                    <td><?php echo $confusionMatrix['Positif']['Netral']; ?></td>
                                    <td><?php echo $confusionMatrix['Positif']['Positif']; ?></td>
                                </tr>
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            Data After Processing
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"">
                                <thead>
                                    <tr>
                                        <th>ID Split</th>
                                        <th>Text</th>
                                        <th>Sentimen</th>
                                        <th>Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($after->result() as $data) { ?>
                                        <tr>
                                            <td><?php echo $data->id_data; ?></td>
                                            <td><?php echo $data->text; ?></td>
                                            <td><?php echo $data->sentimen; ?></td>
                                            <td><?php echo $data->sesudah; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                               
                            </form>
                            <?php if (!empty($test) && $test) { ?>
                                <br>
                                <h5>Hasil Klasifikasi:</h5>
                                <?php if ($test_result) { ?>
                                    <br>
                                    <p><?php echo $test_result ?></p>
                                <?php } else { ?>
                                    <br>
                                    <p>Belum diuji</p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik
    var data = {
        labels: ['Negatif', 'Netral', 'Positif'],
        datasets: [{
            label: 'Jumlah Evaluasi',
            data: [
                <?php echo $jumlah_evaluasi['Negatif'] ?>,
                <?php echo $jumlah_evaluasi['Netral'] ?>,
                <?php echo $jumlah_evaluasi['Positif'] ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Opsi untuk grafik
    var options = {
        responsive: true,
        maintainAspectRatio: false
    };

    // Membuat grafik
    var ctx = document.getElementById('chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
</script>

