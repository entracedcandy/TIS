<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        
        <title><?php 
        if(empty($title) || empty($title['caption']))
        {
            echo $title;
        }
        else
        {
             echo $title['caption'];
           
        }


        ?></title>

        <link href="<?= base_url('assets/');?>css/styles.css" rel="stylesheet" />
        <link href="<?= base_url('assets/AdminLTE/');?>datatables-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>datatables-responsive/css/responsive.bootstrap4.min.css">
        <link href="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url('assets/');?>css/loader.css" rel="stylesheet" />

        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>datatable/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>overlayScrollbars/css/OverlayScrollbars.min.css">
       
        <!--Admin Lte CSS-->
        <link rel="stylesheet" href="<?= base_url('assets/');?>css/adminlte/ionicons.min.css">
        
        
        <link rel="stylesheet" href="<?= base_url('assets/');?>css/adminlte/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="<?= base_url('assets/');?>/toastr/toastr.min.css">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        
        <!--context Menu-->
        <link href="<?= base_url('assets/');?>context-menu/jquery.contextMenu.css" rel="stylesheet" ></link>
        <link href="<?= base_url('assets/');?>context-menu/jquery.contextMenu.min.css" rel="stylesheet" />
        
        
        <link rel="stylesheet" href="<?= base_url('assets/');?>datepicker/jquery-ui.css">
        <link rel="stylesheet" href="<?= base_url('assets/');?>datetimepicker/jquery-ui-timepicker-addon.css" />
        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/');?>select2/css/select2.min.css">
        <script src="<?= base_url('assets/');?>js/masked_input_1.3-min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/masked_input_ex.js" crossorigin="anonymous"></script>
        <script src="<?=base_url('assets/AdminLTE/');?>moment/moment.min.js"></script>
        <link rel="stylesheet" href="<?= base_url('assets/');?>css/adminlte/adminlte.css">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
        <style>
            /* width */
            ::-webkit-scrollbar {
                width: 10px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
                background: #f1f1f1; 
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
                background: #888; 
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
                background: #555; 
            }

            div.loading
            {
                display:none;
                width:100%;
                height:100%;
                position:absolute;
                background-color:#FFF;
                opacity:0.8;
                z-index:20;
            }

            th { font-size: 15px; }
            td { font-size: 13px; }

            .myFont{
                font-size:12px;
            }

            /* .select2-selection__rendered {
                line-height: 31px !important;
            }
            .select2-container .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__arrow {
                height: 34px !important;
            } */

            /* .scrollbox {
                border: 1px solid red;
                overflow-y: auto;
                max-height: calc(100vh - 150px);
            } */

            .maroon {
                background: #B04759;
            }
            .yellow {
                background-color: #FEFF86;
            }
            .yellow2 {
                background-color: #FFEA20;
            }
            .red-notif {
                background-color: #F45050;
            }
            .tooltip-inner {
                max-width: 400px;
                min-width: 100px;
            }

            .ui-autocomplete { z-index:2147483647; }

            .modal {
                overflow-y:auto;
            }
        </style>
  
    </head>
    <body class="sb-nav-fixed"> 

        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" 
            href="
                   
                        <?= base_url('Dashboard') ?>
                "

            >CPI Jatim Multi Tools</a>

            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href=""><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
           <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <div class="">

                    </div>


                </div>
            </form>
             <ul class="navbar-nav ml-auto ml-md-0">
                <span class="font-weight-normal" style="color:white; font-size:12px;" id="" href="#" aria-expanded="false"> <?= $user['caption']; ?></span>
            </ul>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="<?= base_url('Login/change_password');?>">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('Login/logout') ?>">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>