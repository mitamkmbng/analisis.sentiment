<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BAPENDA</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url("assets/admin/vendor/fontawesome-free/css/all.min.css");?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url("assets/admin/css/sb-admin-2.min.css");?>" rel="stylesheet">

    <link href="<?php echo base_url("assets/admin/vendor/datatables/dataTables.bootstrap4.min.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("assets/admin/vendor/jquery/jquery.min.js");?>"></script>
    
    <script type="text/javascript">
    function check() {
    var x = document.getElementById("pwd");
    var x1 = document.getElementById("pwd1");


    if (x.type === "password") {
    x.type = "text";
    } else {
    x.type = "password";
    }
    if (x1.type === "password") {
    x1.type = "text";
    } else {
    x1.type = "password";
    }
    }
    </script>
    <!-- Load Chart.js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@~4.3.0"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.0.0/wordcloud2.min.js"></script>
     

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                 <div class="pull-left image">
                     <img style="width: 150px;"src="<?php echo base_url() ?>/assets/image/lbapenda.png" class="img-fluid">
                </div>
                <div class="sidebar-brand-text mx-3"> </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("Dashboard");?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('admin')?>">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Dataset</span></a>
            </li>

            <?php if (empty($total->result())) { ?>

            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" disabled">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Preprocessing</span>
                </a>
            </li>


            <?php } else { ?>

            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Preprocessing</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        
                        <a class="collapse-item" href="<?php echo base_url("admin/data_cleaning");?>"> Data Cleaning</a>
                        <a class="collapse-item" href="<?php echo base_url("admin/data_stopwords");?>"> Data Stopwords</a>
                        <a class="collapse-item" href="<?php echo base_url("admin/data_stemming");?>"> Data Stemming</a>
                        
                    </div>
                </div>
            </li>

            <?php } ?>

            <?php if (empty($stemming->result())) { ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#"
                     aria-controls="collapseTwo" disabled>
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>TF-IDF</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                         <a class="collapse-item" href="javascript:void(0);" disabled>TF-IDF Tiap Data</a>
                        <a class="collapse-item" href="javascript:void(0);" disabled>Word Vocab TF-IDF</a>
                        <a class="collapse-item" href="javascript:void(0);" disabled>Word Cloud</a>
                    </div>
                </div>
            </li>

            <?php } else { ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                     aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>TF-IDF</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                         <a class="collapse-item" href="<?php echo base_url("admin/data_tfidf");?>">TF-IDF Tiap Data</a>
                        <a class="collapse-item" href="<?php echo base_url("admin/vocab");?>">Word Vocab TF-IDF</a>
                        <a class="collapse-item" href="<?php echo base_url("admin/wordcloud");?>">Word Cloud</a>
                    </div>
                </div>
            </li>

            <?php } ?>

            <!-- Heading -->
             
            <?php if (empty($tfidf->result())) { ?>

            <!-- Heading -->
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" disabled>
                   <i class="fas fa-fw fa-tag"></i>
                    <span>Labeling Data</span></a>
            </li>

            <?php } else { ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("admin/data_label");?>">
                   <i class="fas fa-fw fa-tag"></i>
                    <span>Labeling Data</span></a>
            </li>

            <?php } ?>

            <?php if (empty($hasildata->result())) { ?>

            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" disabled>
                   <i class="fas fa-fw fa-list"></i>
                    <span>Split Data</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" disabled>
                    <i class="fas fa-fw fa-edit"></i>
                    <span>Data Training</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" disabled>
                    <i class="fas fa-fw fa-pen"></i>
                    <span>Data Testing</span></a>
            </li>

            <?php } else { ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("admin/data_split");?>">
                   <i class="fas fa-fw fa-list"></i>
                    <span>Split Data</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("admin/data_training");?>">
                    <i class="fas fa-fw fa-edit"></i>
                    <span>Data Training</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("admin/data_testing");?>">
                    <i class="fas fa-fw fa-pen"></i>
                    <span>Data Testing</span></a>
            </li>

            <?php } ?>

            <!-- Heading -->
                
            <?php if (empty($training->result())) { ?>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" ref="javascript:void(0);" disabled>
                    <i class="fas fa-fw fa-bars"></i>
                    <span>Naive Bayes</span></a>
            </li>

            <?php } else { ?>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url("modeling/klasifikasi");?>">
                    <i class="fas fa-fw fa-bars"></i>
                    <span>Naive Bayes</span></a>
            </li>

            <?php } ?>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
               <!-- Main Content -->
   <div id="content">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $this->session->userdata('username');?></span>
                <img class="img-profile rounded-circle"
                    src="<?php echo base_url("assets/admin/img/undraw_profile.svg");?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= site_url('admin/gantipassword')?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Update Password
                </a>
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
