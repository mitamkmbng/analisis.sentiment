<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form <?= $title?> Data</h6>
        </div>
        <div class="card-body">
        <form class="form" action="<?= $action?>" method="post" enctype="multipart/form-data">
            <label>NO</label>
            <input class="form-control" placeholder="NO" value="<?php echo $no;?>" name="no"><br>
            <label>EQUIPMENT</label>
            <input class="form-control" placeholder="EQUIPMENT" value="<?php echo $equipment;?>" name="equipment"><br>
            <label>DESKRIPSI</label>
            <textarea class="form-control" placeholder="DESKRIPSI" value="" name="desk"><?php echo $desk;?></textarea><br>
            <label>DEL</label>
            <input class="form-control" placeholder="DEL" value="<?php echo $del;?>" name="del"><br>
            <label>FIS</label>
            <input class="form-control" placeholder="FIS" value="<?php echo $fis;?>" name="fis"><br>
            <label>ORDER</label>
            <input class="form-control" placeholder="ORDER" value="<?php echo $order;?>" name="order"><br>
            <label>TGL RELEASE ORDER</label>
            <input class="form-control" type="date" placeholder="TGL RELEASE ORDER" value="<?php echo $tgl_release_order;?>" name="tgl_release_order"><br>
            <label>TGL CHANGE ORDER</label>
            <input class="form-control" type="date" placeholder="TGL CHANGE ORDER" value="<?php echo $tgl_change_order;?>" name="tgl_change_order"><br>
            <label>ITEM NO</label>
            <input class="form-control" placeholder="ITEM NO" value="<?php echo $item_no;?>" name="item_no"><br>
            <label>MATERIAL</label>
            <input class="form-control" placeholder="MATERIAL" value="<?php echo $material;?>" name="material"><br>
            <label>MATERIAL DESKRIPSI</label>
            <input class="form-control" placeholder="MATERIAL DESKRIPSI" value="<?php echo $material_deskripsi;?>" name="material_deskripsi"><br>
            <label>PO TEXT</label>
            <textarea class="form-control" placeholder="PO TEXT" value="" name="po_text"><?php echo $po_text;?></textarea><br>
            <label>MANUFACTURE</label>
            <input class="form-control" placeholder="MANUFACTURE" value="<?php echo $manufacture;?>" name="manufacture"><br>
            <label>QTY</label>
            <input class="form-control" placeholder="QTY" value="<?php echo $qty;?>" name="qty"><br>
            <label>BUN</label>
            <input class="form-control" placeholder="BUN" value="<?php echo $bun;?>" name="bun"><br>
            <label>DIFF</label>
            <input class="form-control" placeholder="DIFF" value="<?php echo $diff;?>" name="diff"><br>
            <label>REVISION</label>
            <input class="form-control" placeholder="REVISION" value="<?php echo $revision;?>" name="revision"><br>
            <label>PG</label>
            <input class="form-control" placeholder="PG" value="<?php echo $pg;?>" name="pg"><br>
            <label>KATEGORI MATERIAL</label>
            <input class="form-control" placeholder="KATEGORI MATERIAL" value="<?php echo $kategori_material;?>" name="kategori_material"><br>
            <label>ANALIS</label>
            <input class="form-control" placeholder="ANALIS" value="<?php echo $analis;?>" name="analis"><br>
            <label>AST ANALIS</label>
            <input class="form-control" placeholder="AST ANALIS" value="<?php echo $ass_analis;?>" name="ass_analis"><br>
            <label>PROGRESS</label>
            <input class="form-control" placeholder="PROGRESS" value="<?php echo $progress;?>" name="progress"><br>
            <label>SAP MB52</label>
            <input class="form-control" placeholder="SAP MB52" value="<?php echo $sap_mb52;?>" name="sap_mb52"><br>
            <label>STATUS PROGRESS</label>
            <input class="form-control" placeholder="STATUS PROGRESS" value="<?php echo $status_progress;?>" name="status_progress"><br>
            <label>WO TO PR</label>
            <input class="form-control" placeholder="WO TO PR" value="<?php echo $wo_to_pr;?>" name="wo_to_pr"><br>
            <label>TGL UPDATE STATUS PROGRESS</label>
            <input class="form-control" type="date" placeholder="TGL UPDATE STATUS PROGRESS" value="<?php echo $tgl_update_status_progress;?>" name="tgl_update_status_progress"><br>
            <label>RENCANA DICADANGKAN</label>
            <input class="form-control" placeholder="RENCANA DICADANGKAN" value="<?php echo $rencana_dicadangkan;?>" name="rencana_dicadangkan"><br>
            <label>REALISASI PENCADANGAN</label>
            <input class="form-control" placeholder="REALISASI PENCADANGAN" value="<?php echo $realisasi_pencadangan;?>" name="realisasi_pencadangan"><br>
            <label>LOKASI</label>
            <input class="form-control" placeholder="LOKASI" value="<?php echo $lokasi;?>" name="lokasi"><br>
            <label>KETERANGAN WH</label>
            <textarea class="form-control" placeholder="KETERANGAN WH" value="" name="ket_wh"><?php echo $ket_wh;?></textarea><br>
            
            <label>PR</label>
            <input class="form-control" placeholder="PR" value="<?php echo $pr;?>" name="pr"><br>
            <label>ITEM PR</label>
            <input class="form-control" placeholder="ITEM PR" value="<?php echo $item_pr;?>" name="item_pr"><br>
            <label>QPR</label>
            <input class="form-control" placeholder="QPR" value="<?php echo $qpr;?>" name="qpr"><br>
            <label>TGL PR</label>
            <input type="date" class="form-control" placeholder="TGL PR" value="<?php echo $tgl_pr;?>" name="tgl_pr"><br>
            <label>TKDN</label>
            <input class="form-control" placeholder="TKDN" value="<?php echo $tkdn;?>" name="tkdn"><br>
            <label>TGL TKDN</label>
            <input type="date" class="form-control" placeholder="TGL TKDN" value="<?php echo $tgl_tkdn;?>" name="tgl_tkdn"><br>
            
            <label>TGL TERIMA</label>
            <input class="form-control" placeholder="TGL TERIMA" value="<?php echo $tgl_terima_pr;?>" name="tgl_terima_pr"><br>
            <label>PIC BUYER</label>
            <input class="form-control" placeholder="PIC BUYER" value="<?php echo $pic_buyer;?>" name="pic_buyer"><br>
            <label>ASS BUYER</label>
            <input class="form-control" placeholder="ASS BUYER" value="<?php echo $ass_buyer;?>" name="ass_buyer"><br>
            <label>COLL NO</label>
            <input class="form-control" placeholder="COLL NO" value="<?php echo $coll_no;?>" name="coll_no"><br>
            <label>JENIS RAPAT</label>
            <select class="form-control" placeholder="JENIS RAPAT" name="jenis_rapat">
                <?php if($jenis_rapat){?>
                <option value="<?= $jenis_rapat?>"><?= $jenis_rapat?></option>
                <?php }else{?>
                <option value="">-pilih-</option>
                <?php }?>
                <option value="KEHANDALAN">KEHANDALAN</option>
                <option value="WAM DTU">WAM DTU</option>
                <option value="HSC">HSC</option>
                <option value="NPU">NPU</option>
                <option value="WAM UTL">WAM UTL</option>
                <option value="WAM RCC">WAM RCC</option>
                <option value="WAM OM">WAM OM</option>
            </select><br>
            <label>STATUS PENGADAAN</label>
            <select class="form-control" placeholder="STATUS PENGADAAN" name="status_pengadaan">
                <?php if($status_pengadaan){?>
                <option value="<?= $status_pengadaan?>"><?= $status_pengadaan?></option>
                <?php }else{?>
                <option value="">-pilih-</option>
                <?php }?>
                <option value="EVALUASI DP3">EVALUASI DP3</option>
                <option value="PRA TENDER">PRA TENDER</option>
                <option value="INQUIRY">INQUIRY</option>
                <option value="HPS/OE">HPS/OE</option>
                <option value="BIDDER LIST">BIDDER LIST</option>
                <option value="PENDAFTARAN">PENDAFTARAN</option>
                <option value="PENILAIAN KUALIFIKASI">PENILAIAN KUALIFIKASI</option>
                <option value="PREBID MEETING">PREBID MEETING</option>
                <option value="PEMASUKAN PENAWARAN">PEMASUKAN PENAWARAN</option>
                <option value="EVALUASI PENAWARAN">EVALUASI PENAWARAN</option>
                <option value="NEGOSIASI">NEGOSIASI</option>
                <option value="LHP">LHP</option>
                <option value="PENGUMUMAN">PENGUMUMAN</option>
                <option value="MASA SANGGAH">MASA SANGGAH</option>
                <option value="PENUNJUKAN PEMENANG">PENUNJUKAN PEMENANG</option>
                <option value="PURCHASE ORDER">PURCHASE ORDER</option>
            </select><br>
            <label>KETERANGAN</label>
            <textarea class="form-control" rows="3" placeholder="KETERANGAN" value="" name="keterangan"><?php echo $keterangan;?></textarea><br>
            <label>TGL PENUNJUKAN</label>
            <input type="date" class="form-control" placeholder="TGL PENUNJUKAN" value="<?php echo $tgl_penunjukan;?>" name="tgl_penunjukan"><br>
            <label>PO</label>
            <input class="form-control" placeholder="PO" value="<?php echo $po;?>" name="po"><br>
            <label>TGL PO</label>
            <input type="date" class="form-control" placeholder="TGL PO" value="<?php echo $tgl_po;?>" name="tgl_po"><br>
            <label>NILAI PO</label>
            <input class="form-control" placeholder="NILAI PO" value="<?php echo $nilai_po;?>" name="nilai_po"><br>
            <label>TGL DELIVERY</label>
            <input type="date" class="form-control" placeholder="TGL DELIVERY" value="<?php echo $tgl_delv;?>" name="tgl_delv"><br>
            <label>PERUSAHAAN</label>
            <input class="form-control" placeholder="PERUSAHAAN" value="<?php echo $perusahaan;?>" name="perusahaan"><br>
            <label>TENDER ULANG</label>
            <select class="form-control" placeholder="TENDER ULANG" name="tender_ulang">
                <?php if($tender_ulang){?>
                <option value="<?= $tender_ulang?>"><?= $tender_ulang?></option>
                <?php }else{?>
                <option value="">-pilih-</option>
                <?php }?>
                <option value="YES">YES</option>
                <option value="NO">NO</option>
            </select><br>
            <label>APPROVAL DRAWING</label>
            <input type="date" class="form-control" placeholder="APPROVAL DRAWING" value="<?php echo $approval_drawing;?>" name="approval_drawing"><br>
            <label>NO LC</label>
            <input class="form-control" placeholder="NO LC" value="<?php echo $no_lc;?>" name="no_lc"><br>
            <label>TGL LC</label>
            <input type="date" class="form-control" placeholder="TGL LC" value="<?php echo $tgl_lc;?>" name="tgl_lc"><br>
            <label>EXP. DATE LC</label>
            <input type="date" class="form-control" placeholder="EXP. DATE LC" value="<?php echo $exp_date_lc;?>" name="exp_date_lc"><br>
            <label>JAMINAN PELAKSANAAN</label>
            <select class="form-control" placeholder="JAMINAN PELAKSANAAN" name="jaminan_pelaksanaan">
                <?php if($jaminan_pelaksanaan){?>
                <option value="<?= $jaminan_pelaksanaan?>"><?= $jaminan_pelaksanaan?></option>
                <?php }else{?>
                <option value="">-pilih-</option>
                <?php }?>
                <option value="YES">YES</option>
                <option value="NO">NO</option>
            </select><br>
            <label>NO JAMINAN PELAKSANAAN</label>
            <input class="form-control" placeholder="NO JAMINAN PELAKSANAAN" value="<?php echo $no_jaminan_pelaksanaan;?>" name="no_jaminan_pelaksanaan"><br>
            <label>NILAI JAMINAN</label>
            <input class="form-control" placeholder="NILAI JAMINAN" value="<?php echo $nilai_jaminan;?>" name="nilai_jaminan"><br>
            <label>EXP. DATE JAMINAN</label>
            <input type="date" class="form-control" placeholder="EXP. DATE JAMINAN" value="<?php echo $exp_date_jaminan;?>" name="exp_date_jaminan"><br>
            <label>COST SAVING</label>
            <input class="form-control" placeholder="COST SAVING" value="<?php echo $cost_saving;?>" name="cost_saving"><br>
            <label>SLA</label>
            <input class="form-control" placeholder="SLA" value="<?php echo $sla;?>" name="sla"><br>
            
            <hr>
            <input type="hidden" name="id_chass" value="<?= $id_chass?>">
            <button type="submit" class="btn btn-primary"><?= $button?></button>
            <a href="<?= site_url('admin')?>" class="btn btn-default">Kembali</a> 
        </form>      
            </div>
        </div>
</div>



       