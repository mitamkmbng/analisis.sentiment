<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form <?= $title?> Pesantren</h6>
        </div>
        <div class="card-body">
        <?php echo $this->session->flashdata('suces')?>

        <form class="form" action="<?= $action?>" method="post" enctype="multipart/form-data">
            <label>Nama Pesantren</label>
            <input class="form-control" placeholder="Nama Pesantren" value="<?php echo $nama_pesantren;?>" name="nama_pesantren" required reqdonly><br>
            <label>Jumlah Santriwan</label>
            <input type="number" class="form-control" placeholder="Jumlah Santriwan" value="<?php echo $jumlah_santriwan;?>" name="jumlah_santriwan" required><br>
            <label>Jumlah Santriwati</label>
            <input type="number" class="form-control" placeholder="Jumlah Santriwati" value="<?php echo $jumlah_santriwati;?>" name="jumlah_santriwati" required><br>
            <label>Jumlah Lulusan</label>
            <input type="number" class="form-control" placeholder="Jumlah Lulusan" value="<?php echo $jumlah_lulusan;?>" name="jumlah_lulusan" required><br>
            <hr>
            <input type="hidden" name="id_data_santri" value="<?= $id_data_santri?>">
            <input type="hidden" name="id_pesantren" value="<?= $id_pesantren?>">
            <button type="submit" class="btn btn-primary"><?= $button?></button>
            <a class="btn btn-danger" href="<?= site_url('admin/detail_pesantren/'.$id_pesantren)?>">Kembali</a>
        </form>      
            </div>
        </div>
</div>



       