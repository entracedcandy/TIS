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

            <div class="row">
                <div class="col-md-12">
                    
                    <div class="card">
                            <div class="card-body">
                                <div class="card-body">
                                        <?= $this->session->flashdata('message'); ?>
                                    <form method="post" action="<?= base_url('Login/change_password');?>">
                                        
                                        <div class="form-group">
                                            <label for="input_current_password">Current Password</label>
                                            <input class="form-control" id="input_current_password" name="input_current_password" type="password" placeholder="Enter Current Password" value="<?= set_value('input_current_password');?>" />
                                            <?= form_error('input_current_password',' <small class="text-danger pl-1">','</small>');?>
                                        </div>

                                        <div class="form-group">
                                            <label for="input_new_password">New Password</label>
                                            <input class="form-control" id="input_new_password" name="input_new_password" type="password" placeholder="Enter New Password" value="<?= set_value('input_new_password');?>" />
                                            <?= form_error('input_new_password',' <small class="text-danger pl-1">','</small>');?>
                                        </div>

                                        <div class="form-group">
                                            <label for="input_repeat_password"> Password</label>
                                            <input class="form-control" id="input_repeat_password" name="input_repeat_password" type="password" placeholder="Enter Repeat Password" value="<?= set_value('input_repeat_password');?>" />
                                            <?= form_error('input_repeat_password',' <small class="text-danger pl-1">','</small>');?>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                            

                                        </div>

                                    </form>

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
