       
            <!--Body-->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">

                        <!--Title-->
                        <h1 class="mt-4"><?= $title['caption'];?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?= $title['caption'];?></li>
                        </ol>   

                        <!--content-->

                        <div class="row">
                            <div class="col-md-12 animated fadeInRight">
                                <div class="ui-bordered px-4 pt-4 mb-4">
                                    <form action="" method="post" accept-charset="utf-8">
                                        <input type="hidden" name="csrf_hrsale" value="" />                                                               

                                        <div class="form-row">
                                            <div class="col-md mb-3">
                                                <label class="form-label">Pilih Tanggal</label>
                                                <input class="form-control date" placeholder="Pilih Tanggal" readonly id="start_date" name="start_date" type="text" value="2022-11-22">
                                            </div>
                                            <div class="col-md mb-3">
                                                <label class="form-label">Pilih Tanggal</label>
                                                <input class="form-control date" placeholder="Pilih Tanggal" readonly id="end_date" name="end_date" type="text" value="2022-11-22">
                                            </div>
                                        
                                            <div class="col-md mb-3">
                                                <label class="form-label">Unit</label>
                                                <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Unit">
                                                    <option value="0">Semua</option>
                                                </select>
                                            </div>
                                            <div class="col-md mb-3" id="department_ajax">
                                                <label class="form-label">Metode Gaji</label>
                                                <select class="form-control" id="filter_department" name="department_id" data-plugin="select_hrm" data-placeholder="Metode Gaji" >
                                                    <option value="0">Semua</option>
                                                </select>
                                            </div>
                                            <div class="col-md mb-3" id="designation_ajax">
                                                <label class="form-label">Bagian</label>
                                                <select class="form-control" name="designation_id" data-plugin="select_hrm"  id="designation" data-placeholder="Bagian">
                                                    <option value="0">Semua</option>
                                                </select>
                                            </div>
                                            <div class="col-md mb-3" >
                                                <label class="form-label">Status Karyawan</label>
                                                <select name="active" id="active" class="form-control" data-plugin="" data-placeholder="Status Karyawan">
                                                    <option value="">Semua</option>
                                                    <option value="0">Tidak Aktif</option>
                                                    <option value="1">Aktif</option>
                                                </select>
                                            </div>
                                            <div class="col-md col-xl-2 mb-4">
                                            <label class="form-label d-none d-md-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-secondary btn-block">Search</button>
                                            </div>
                                        </div>
                                        
                                    </form>        
                                </div>
                            </div>
                        </div>
                        

                    </div>
                
                </main>
                 <!--footer-->
                <?php $this->load->view('templates/dash_footer');?>

            </div>
            <!--End Body-->


<!--Script Footer=======================================================================================================-->
        </div>


        <!--index.php !-->     
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
            




        <script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.min.js"></script>

         

    </body>
</html>
       
