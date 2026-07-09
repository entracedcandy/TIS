<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> -->
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title><?= $title ?></title>
        <!-- <link rel="icon" href="<?= base_url('asset/');?>img/logo-pokphand-blue-01.png" type="image/png"> -->
         <link rel="icon" href="<?= base_url('assets/');?>image/logo_TIS_192.png" type="image/png">

        <link href="<?= base_url('asset/');?>adminlte/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>css/style.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/fullcalendar/main.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/dist/css/adminlte.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/clusterize.js@1.0.0/clusterize.min.css" rel="stylesheet">
    </head>
    <body class="hold-transition sidebar-mini layout-fixed"> 
        <div class="wrapper">
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__fdin" src="<?= base_url('asset/');?>img/logo-pokphand-blue-01.png" alt="CP-Logo" height="200" width="200">
            </div>

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" role="button" href="<?= site_url("Home/logout"); ?>">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-navy elevation-4">
                <a href="<?= site_url('Dashboard_new') ?>" class="brand-link logo-switch">
                    <img src="<?= base_url('asset/');?>img/logo-pokphand.png" alt="AdminLTE Docs Logo Small" class="brand-image-xl logo-xs">
                    <img src="<?= base_url('asset/');?>img/logo-pokphand-web.png" alt="AdminLTE Docs Logo Large" class="brand-image-xl logo-xl ml-5" style="left: 12px">
                </a>

                <div class="sidebar">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex text-center">
                        <div class="info">
                            <?php
                                $this->load->model('M_Dash', 'dash');
                                $token = $this->session->userdata("token");
                                $user = $this->dash->getName($token)->result();
                                // var_dump($token);
                            ?>
                            <a href="<?php echo site_url('profile'); ?>" class="d-block"><?= $user[0]->caption ?></a>
                            <!-- <span class="d-block" style="color: white;"><?= $user[0]->caption ?></span>                         -->
                        </div>
                    </div>

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                            <?php
                                $head = $this->dash->getMenuHead($token)->result();
		                        $menu = $this->dash->getMenuChild($token)->result();

                                // var_dump($head);

                                foreach($head as $h){
                                    if($h->has_sub == 'y'){
                                    ?>
                                        <li class="nav-item menu-close">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon <?php if($h->icon == ""){ echo "fas fa-star"; }else{ echo $h->icon; } ?>"></i>
                                                <p> 
                                                    <?= $h->caption ?>
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?php
                                                    foreach($menu as $m){
                                                        if($m->submenu == $h->id_menu){
                                                ?>
                                                            <li class="nav-item">
                                                                <a href="<?= site_url($m->src); ?>" class="nav-link">
                                                                    <i class="far fa-circle nav-icon"></i>
                                                                    <p class="menu-font"><?= $m->caption ?></p>
                                                                </a>
                                                            </li>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <?php
                                    }
                                }
                            ?>
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                
            