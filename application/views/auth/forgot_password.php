<div id="layoutSidenav_content">
    <main class="content">
        <!--Heading-->
        <div class="container-fluid">
            
            <!--Title-->
            <h1 class="mt-4"><?= $title;?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"><?= $title;?></li>
            </ol>
            
            

            <!--content-->

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">

                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Forgot Password</h3>
                            </div>

                            <div class="card-body">

                                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                                 <?= $this->session->flashdata('message'); ?>

                                <form action="<?= base_url('Login/forgot_password'); ?>" method="post">
                                    <div class="input-group mb-3">
                                        <input type="email" id="input_forgot_email_pass" name="input_forgot_email_pass" class="form-control" placeholder="Email">

                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <?= form_error('input_forgot_email_pass',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                   
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>

                              
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="<?= base_url('Login'); ?>">Back to login</a></div>
                                <div class="small"><a href="<?= base_url('Login/registration'); ?>">Register a new membership</a></div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
           
        </div>

       
    </main>

    <!--footer-->
    <?php $this->load->view('templates/dash_footer');?>
</div>





<!--b4<script src="<?= base_url('assets/');?>js/jquery-3.5.1.min.js" crossorigin="anonymous"></script>-->
<script src="<?= base_url('assets/AdminLTE/');?>jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/');?>js/jquery-ui.js"></script>

<script type="text/javascript" src="<?= base_url('assets/');?>datetimepicker/jquery-ui-timepicker-addon.js"></script>

<script src="<?= base_url('assets/');?>js/scripts.js"></script>
<!--b4<script src="<?= base_url('assets/');?>js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>-->
<script src="<?= base_url('assets/AdminLTE/');?>bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= base_url('assets/');?>js/adminlte.js" crossorigin="anonymous"></script>


 <!-- DataTables -->
 <script src="<?= base_url('assets/AdminLTE/');?>datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/AdminLTE/');?>datatable/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?= base_url('assets/AdminLTE/');?>datatable/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    

<script src="<?= base_url('assets/');?>toastr/toastr.min.js"></script>


<script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.js"></script>
<script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.min.js"></script>

  
</body>
</html>
