<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Klasifikasi CNN</h6>
        </div>
        <div class="card-body">
            <?php echo $this->session->flashdata('suces') ?>
            <br>
                <a href="<?= site_url('user/download_hasil')?>" class="btn btn-light btn-icon-split">
                    <span class="icon text-gray-600">
                        <i class="fas fa-download"></i>
                    </span>
                    <span class="text">Download</span>
                </a> 
            <br>
            <br>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Classification Report
                        </div>
                        <div class="card-body">
                            <pre>Accuracy: <?php echo $accuracy; ?></pre>
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
                            Test Model
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo base_url('modeling/test_model'); ?>">
                                <div class="form-group">
                                    <label for="testData">Test Data</label>
                                    <textarea class="form-control" id="testData" name="testData" rows="3" placeholder="Masukkan data untuk diuji"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Uji Model</button>
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

