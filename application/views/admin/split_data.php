<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Splitting Data</h6>
        </div>
        <div class="card-body">
            <?php echo $this->session->flashdata('suces')?>
            <br>

            <div class="row">
                <div class="col-md-2">
                    <a href="<?= site_url('admin/mergeData')?>" class="btn btn-light btn-icon-split">
                        <span class="icon text-gray-600">
                            <i class="fas fa-download"></i>
                        </span>
                        <span class="text">Ambil Data</span>
                    </a>
                    
                </div>

                <div class="col-md-3">
                    <!-- Form untuk memilih rasio pembagian data -->
                    <form action="<?= site_url('admin/split_act/') ?>" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="split_ratio">Rasio:</label>
                        </div>
                        <div class="form-group mr-2">
                            <select class="form-control" name="split_ratio" id="split_ratio">
                                <option value="10">10:90</option>
                                <option value="20">20:80</option>
                                <option value="30">30:70</option>
                                <option value="40">40:60</option>
                                <option value="50">50:50</option>
                                <option value="60">60:40</option>
                                <option value="70">70:30</option>
                                <option value="80">80:20</option>
                                <option value="90">90:10</option>
                                <!-- Tambahkan pilihan lainnya sesuai kebutuhan -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-light btn-icon-split">
                            <span class="icon text-gray-600">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Proses</span>
                        </button>
                    </form>
                </div>


                <div class="col-md-2">
                    <a href="<?= site_url('modeling/trainModel')?>" class="btn btn-light btn-icon-split">
                        <span class="icon text-gray-600">
                            <i class="fas fa-cogs"></i>
                        </span>
                        <span class="text">Buat Model</span>
                    </a>
                </div>
            </div>

            <br><br>

            <?php if ($split->num_rows() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Text</th>
                                <th>Sentiment</th>
                                <th>TF-IDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($split->result() as $key): ?>
                                <tr>
                                    <td><?php echo $no++;?></td>
                                    <td><?php echo $key->text;?></td>
                                    <td><?php echo $key->sentimen;?></td>
                                    <td><?php echo $key->tfidf;?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
