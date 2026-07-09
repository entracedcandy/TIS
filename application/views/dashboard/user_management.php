<div id="layoutSidenav_content">
    <main class="content">
        <!--Heading-->
        <div class="container-fluid">
            
            <!--Title-->
            <h1 class="mt-4"><?= $title['caption'];?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"><?= $title['caption'];?></li>
            </ol>
            
            

            <!--content-->
            <div class="card mb-4">
                <div class="card-header-plus">
                    <div class="col-sm-2">
                        <select name="set_plant" id="set_plant" class="form-control" placeholder="" required>
                            <option value="">- Set Plant -</option>
                            <?php foreach($plant as $plant):?>
                            
                                <option value="<?= $plant["cost_center"];?>"><?= $plant["cost_center"];?></option>
                            <?php endforeach;?>

                        </select>
                        </div>
                    <!-- <div class="d-flex">
                        <div class="mt-auto p-2">
                            <button type="button" data-toggle="modal" data-target="#modal_add_user" role="button" aria-pressed="true"><i class='fas fa-user-edit'></i>&nbsp; Add User</button>
                        </div>
                        
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            
                            <a class="btn btn-app bg-success" data-toggle="modal" data-target="#modal_add_user" role="button" aria-pressed="true" style="margin-top: 10px;">
                            
                                <i class="fas fa-user-edit"></i> Add
                            </a>
                        

                            <a class="btn btn-app bg-info" data-toggle="modal" data-target="#modal_add_user" role="button" aria-pressed="true" style="margin-top: 10px;">
                            
                                <i class="fas fa-file-import"></i> Excel
                            </a>
                        
                            <a class="btn btn-app bg-danger" data-toggle="modal" data-target="#modal_add_user" role="button" aria-pressed="true" style="margin-top: 10px;">
                            
                                <i class="fa fa-download" aria-hidden="true"></i>Template
                            </a>
                            
                        </div>
                    </div>-->

                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table id="table_user" class="table table-striped table-bordered table-condensed" style="width:100%">
                            <thead>
                                <tr>
                                    <!--<th>No</th>-->
                                    <th>ID</th>
                                    <th>No Staff</th>
                                    <th>Nama</th>
                                    <th>Nik</th>
                                    <th>No Reg</th>
                                    <th>Bagian</th>
                                    <th>Department</th>
                                    <th>Cost Center</th>
                                    <th>Vendor</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>No Staff</th>
                                    <th>Nama</th>
                                    <th>Nik</th>
                                    <th>No Reg</th>
                                    <th>Bagian</th>
                                    <th>Department</th>
                                    <th>Cost Center</th>
                                    <th>Vendor</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

       
    </main>

    <!--footer-->
    <?php $this->load->view('templates/dash_footer');?>
</div>


<!--Area Modal-->



<form id="update_user" action="" method="post">
    <div class="modal fade" id="modal_edit_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4>Edit User</h4>   
                        
                    </div>
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="edit_id_user" name="edit_id_user"/>
                        <input type="hidden" class="form-control" id="edit_id_detail" name="edit_id_detail"/>
                        <div class="row">
                            <label class="col-sm-1 justify-content-end">Nama</label>
                            <div class="col-sm-3 justify-content-between">
                                <input type="text" class="form-control" id="edit_nama" name="edit_nama"/>
                            </div>
                            <label class="col-sm-1 control-label small">Department</label>
                            <div class="col-sm-3">
                                <select name="edit_department" id="edit_department" class="form-control" placeholder="Departement" required>
                                    <option value=""></option>
                                    <?php foreach($departement as $depart):?>
                                    
                                        <option value="<?= $depart["department"];?>"><?= $depart["department"];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <label class="col-sm-1 control-label small">Vendor</label>
                            <div class="col-sm-3">
                                <select name="edit_vendor" id="edit_vendor" class="form-control" placeholder="" required>
                                    <option value=""></option>
                                    <?php foreach($vendor as $vend):?>
                                    
                                        <option value="<?= $vend["vendor"];?>"><?= $vend["caption"];?></option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                            
                        </div>

                        <div class="row">
                            <label class="col-sm-1 justify-content-end">Nik</label>
                            <div class="col-sm-3 justify-content-between">
                                <input type="text" class="form-control" id="edit_nik" name="edit_nik"/>
                            </div>
                            <label class="col-sm-1 control-label small">Bagian</label>
                            <div class="col-sm-3">  
                                <select name="edit_bagian" id="edit_bagian" class="form-control" placeholder="" required>
                                    <option value=""></option>
                                    <?php foreach($bagian_edit as $bag_edit):?>
                                    
                                        <option value="<?= $bag_edit["bagian"];?>"><?= $bag_edit["caption"];?></option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                            <label class="col-sm-1 justify-content-end">Start</label>
                            <div class="col-sm-3 justify-content-between input-group date" id="start_year" >
                                <input type="text" class="form-control datetimepicker-input" id="edit_start" name="edit_start"/>
                                
                            </div>
                            
                        </div>
                        <div class="row">
                            <label class="col-sm-1 justify-content-end">No reg</label>
                            <div class="col-sm-3 justify-content-between">
                                <input type="text" class="form-control" id="edit_reg" name="edit_reg"/>
                            </div>
                            <label class="col-sm-1 control-label small">Cost</label>
                            <div class="col-sm-3">
                                <select name="edit_cost_center" id="edit_cost_center" class="form-control" required>
                                    <option value=""></option>
                                    <?php foreach($cost_center as $cost):?>
                                        <option value="<?= $cost["id_cost_center"];?>"><?= $cost["cost_center"];?></option>
                                    <?php endforeach;?>

                                </select>
                            </div>
                            <label class="col-sm-1 control-label small">End</label>
                            <div class="col-sm-3 justify-content-between input-group date" id="end_year">
                                <input type="text" class="form-control datetimepicker-input" id="edit_end" name="edit_end"/>
                            </div>
                            
                            <label class="col-sm-1 control-label small">Status</label>
                            <div class="col-sm-3">
                                <select name="edit_status" id="edit_status" class="form-control" placeholder="" required>
                                    <option value=""></option>
                                    <option value="y">Aktif</option>
                                    <option value="n">Tidak Aktif</option>
                                </select>
                            </div>

                           
                            
                        </div>

                        <div class="row">
                            <!--<label class="col-sm-1 control-label small">Username</label>
                            <div class="col-sm-3 justify-content-between">
                                <input type="text" class="form-control" id="edit_user_name" name="edit_user_name"/>
                            </div>
                            
                            <label class="col-sm-1 control-label small">Password</label>
                            <div class="col-sm-3 justify-content-between">
                                <input type="text" class="form-control" id="edit_pass" name="edit_pass"/>
                            </div>-->
                           
                        </div>


                       

                     
                    </div>
                    <div class="card-footer">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed" id="table_history">
                                <thead class="">
                                    <tr>
                                        <th>ID</th>
                                        <th>Bagian</th>
                                        <th>Cost Center</th>
                                        <th>Nik</th>
                                        <th>No reg</th>
                                        <th>Vendor</th>
                                        <th>department</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_update_user" class="btn btn-success">Update</button>
                </div>
            
            </div>
        </div>
    </div>
</form>


<form id="add_user" action="" method="post">
    <div class="modal fade" id="modal_add_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <input class="form-control" id="au_no_staff" name="au_no_staff" type="hidden" >
                            <form id="add_user" action="" method="post">
                                <select name="au_select_ptb" id="au_select_ptb" class="form-control" placeholder="Select New PTB" required>
                                    <option value="">-Select PTB-</option>
                                   <!--<?php foreach($master_ptb_hmis as $hmis_row):?>
                                        <option value="<?= $hmis_row["id_finger"];?>"><?= $hmis_row["id_finger"]." / ".$hmis_row["nama"]." / ".$hmis_row["no_pkb"]." / ".$hmis_row["jab_kode"];?></option>
                                    <?php endforeach;?>-->

                                </select>
                            </form>
                        </div>

                        <div class="form-group">

                            <input class="form-control" id="au_username" name="au_username" type="text" placeholder="Enter Username" required >
                            <span id="error_au_username" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <input type="text" name="au_password" id="au_password" class="form-control" placeholder="Create Default Password" required>
                                <span id="error_au_password" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                                <input type="text" name="au_nama" id="au_nama" class="form-control" placeholder="Nama Karyawan" required>
                        </div>
                        <div class="form-group">
                                <input type="text" name="au_nopeg" id="au_nopeg" class="form-control" placeholder="No Pegawai" required>
                        </div>

                        <div class="form-group">
                            <select name="au_department" id="au_department" class="form-control" placeholder="Departement" required>
                                <option value="">-Select Department-</option>
                                <?php foreach($departement as $depart):?>
                                  
                                    <option value="<?= $depart["department"];?>"><?= $depart["department"];?></option>
                                <?php endforeach;?>

                            </select>
                        </div>
                        <div class="form-group">
                            <select name="au_bagian" id="au_bagian" class="form-control" placeholder="Group User" required>
                            <option value="">-Select Bagian-</option>
                                    <?php foreach($bagian as $bagian):?>
                                    
                                        <option value="<?= $bagian["bagian"];?>"><?= $bagian["caption"];?></option>
                                    <?php endforeach;?>

                            </select>
                        </div>

                        <div class="form-group">
                            <select name="au_cost_center" id="au_cost_center" class="form-control" placeholder="Group User" required>
                                <option value="">-Select Cost center-</option>
                                <?php foreach($cost_center as $cost):?>
                                  
                                    <option value="<?= $cost["id_cost_center"];?>"><?= $cost["cost_center"];?></option>
                                <?php endforeach;?>

                            </select>
                        </div>
                        <div class="form-group">
                            <select name="au_vendor" id="au_vendor" class="form-control" placeholder="Select Vendor" required>
                                <option value="">-Select vendor-</option>
                                <?php foreach($vendor as $vend):?>
                                  
                                    <option value="<?= $vend["vendor"];?>"><?= $vend["caption"];?></option>
                                <?php endforeach;?>

                            </select>
                            <span id="error_au_vendor" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="btn_add_user" class="btn btn-success">Save</button>
                    </div>
            
            </div>
        </div>
    </div>
</form>
<!--<form>
    <div class="modal fade" id="modal_delete_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-notify modal-danger">
                <div class="modal-content">
                    
                    <div class="modal-header" style=" border-bottom: 0px solid;">
                        <button type="button" class="close" data-dismiss="modal" style=" cursor: pointer;">&times;</button>
                    </div>
                   
                    <div class="modal-body text-center mb-5">
                        <img src="<?=base_url('assets/image/')?>right.png" class="img-responsive" style="max-width: 30%;">
                        <i class="fas fa-times fa-4x animated rotateIn"></i>
                        <h1 style="padding: 20px;">Are You Sure ?</h1>
                        <p style=" padding: 20px; text-align: center;">Apakah Yakin Data dengan Nama <label id="view_hapus_nama" name="view_hapus_nama"></label> di hapus ??</p>
                        <input type="hidden" name="hapus_no_staff" id="hapus_no_staff" class="form-control">
                        <input type="hidden" name="hapus_nama" id="hapus_nama" class="form-control">
                        <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-lg mr-2 rounded-lg" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_del_relasi" class="btn btn-danger btn-lg rounded-lg">Delete</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</form>-->


<div class="modal fade" id="modal_delete_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
        <!--Content-->
        <div class="modal-content text-center">
            <!--Header-->
            <div class="modal-header d-flex justify-content-center">
                <p class="heading">Are you sure?</p>
            </div>

            <!--Body-->
            <div class="modal-body">
                <i class="fas fa-times fa-4x animated rotateIn"></i>
                <p style="font-weight: bold;">Hapus Nama <label id="view_hapus_nama" name="view_hapus_nama"></label> </p>
                <input type="hidden" name="hapus_id_user" id="hapus_id_user" class="form-control">
                <input type="hidden" name="hapus_nama" id="hapus_nama" class="form-control">

            </div>

            <!--Footer-->
            <div class="modal-footer flex-center">
                <a href="" id="btn_hapus_user" class="btn btn-outline-danger">Yes</a>
                <a type="button" class="btn  btn-danger waves-effect" data-dismiss="modal">No</a>
            </div>
        </div>
        <!--/.Content-->
    </div>
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


    <script type="text/javascript">
        $('#edit_start').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('#edit_end').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    </script>

<script type="text/javascript">
    var save_method; //for save method string
    var table;
    $(document).ready(function() {
    $('#table_user').DataTable({
        dom: "Bfrltip",
        "autoWidth": false,
        "lengthChange": true,
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": true, // Set true agar bisa di sorting
        "responsive" :true,
        "order": [[ 0, 'desc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
        "ajax": {
            "url": '<?php echo base_url('user_management/load_datatable_user'); ?>',
            "type": "POST"
        },
        language : {
    
            "lengthMenu":     "Show _MENU_",
            "FieldSeperator": "\t",
            
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                
                if ( aData['active'] == 'n')
                {
                    //$('td', nRow).css('background-color', 'hsla(14, 100%, 53%, 0.6)');
                    $('td', nRow).css('color', 'red');
                }
                
                
            },
        /* createdRow: function (row, data, index) {
        this.api().cell({ row:index, column:0 }).data(index+1)
        } , */
        "iDisplayLength": 10,
        "aLengthMenu": [[5,10, 50, 100],[5,10, 50, 100]],  // Combobox Limit
        "columns": [
            //{"data": null},
            {"data": "user_id",},
            {"data": "no_staff"},
            {"data": "caption"},
            {"data": "nik"},
            {"data": "no_reg"},
            {"data": "group_user"},
            {"data": "department",},
            {"data": "id_cost_center"},
            {"data": "vendor"},
            {"data": "periode_start"},
            {"data": "periode_end"},
        
            {"data": "action",width:90}
        ],

        //row number
       /* columnDefs: [{targets: 0,
            autoWidth: true,
            searchable: false   
            }],*/
        buttons: 
        [
            {
                text: '<i class="fas fa-user-edit"></i>',
                className: 'bg-success',
                titleAttr: 'Add User',
                action: function ( e, dt, node, config ) {
                   
                    $.ajax({
                        type : "POST",
                        url  : "<?php echo site_url('user_management/get_ptb_control') ?>",
                        dataType : "JSON",
                        cache: false,
                        success: function(data){
                            /*$.each(data,function(index,data){
                                $('#au_select_ptb').append('<option value="'+data['id_finger']+'">'+data['id_finger']+'/'+data['nama']+'/'+data['nopeg']+'/'+data['jab_kode']+'</option>');
                            });*/

                            var $subcarousel = $('#au_select_ptb').html('<option value = "">- Select PTB -</optiom>'); // remove previously loaded options
                            for (i in data)
                                $subcarousel.append('<option value = ' + data[i].id_finger + '>' + data[i].id_finger +'/'+ data[i].nama +'/'+ data[i].nopeg +'/'+ data[i].jab_kode + '</option>');
                        }
                    });
                    
               
                    $('#modal_add_user').modal('show');
                    empty_text();
                }
            },
            {   
                text:'<i class="fas fa-file-import"></i>',
                className: 'bg-info',
                titleAttr: 'Import Excel',
                action: function ( e, dt, node, config ) {
                    //$('#modal_add_user').modal('show');
                    toastr.warning('Masih dalam pengembangan !!');
                }
            },
            {
                text: '<i class="fas fa-download"></i>',
                className: 'bg-danger',
                titleAttr: 'Download Template',
                action: function ( e, dt, node, config ) {
                    //$('#modal_add_user').modal('show');
                    toastr.warning('Masih dalam pengembangan !!');
                }
            },
            {extend: "excel",className: 'bg-secondary',text:'<i class="fas fa-file-excel"></i>',exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10]}}
            
            /*{extend: "copy", exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7]}},
            {extend: "csv", exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7]}},
            {extend: "excel",exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11]}},
            {extend: "pdfHtml5", exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7]}},
            {extend: "print", exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6,7]}},*/
           
            
        ],
        responsive : true,
        fixedHeader: {
            header: true,
            footer: true
        }
    });

    TableManageButtons = function() 
    {
        "use strict";
        return {init: function() {handleDataTableButtons();}};
    }();              

        $('#table_user').on('click','.edit_record',function(){
            var id=$(this).data('id');
            var no_staff=$(this).data('staff');
            var active=$(this).data('staff');
            var cost=$(this).data('cost');


            $("#edit_cost_center").val($(this).data('cost'));
            $("#edit_id_user").val($(this).data('id'));
            $("#edit_id_detail").val($(this).data('detail'));
            $("#edit_nama").val($(this).data('nama'));
            $("#edit_department").val($(this).data('depart'));
            $("#edit_vendor").val($(this).data('vendor'));
            $("#edit_nik").val($(this).data('nik'));
            $("#edit_status").val($(this).data('active'));
            $("#edit_reg").val($(this).data('reg'));
            $("#edit_bagian").val($(this).data('group'));
            $("#edit_start").val($(this).data('start'));
            $("#edit_end").val($(this).data('end'));
            //$("#edit_user_name").val($(this).data('username'));
            //$("#edit_pass").val($(this).data('pass'));

        
            table_browse = $('#table_history').DataTable({
                "ajax":
                {
                    url: "<?php echo site_url('user_management/load_history_user');?>", // URL file untuk proses select datanya
                    type: "POST",
                    "data": {},
                    data: function ( d ) {
                        d.dirId_user = id;
                    },

                },
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "bDestroy": true,
                "bLengthChange":false,
                "bFilter":false,
                "autoWidth": false,
                "bPaginate": false,
                "bInfo": false,
                "responsive" :true,
                "order": [[ 8, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "deferRender": true, 
                
                "columns": [
                    {"data": "user_id_detail"},
                    {"data": "group_user"},
                    {"data": "id_cost_center"},
                    {"data": "nik"},
                    {"data": "no_reg"},
                    {"data": "vendor",},
                    {"data": "department"},
                    {"data": "periode_start"},
                    {"data": "periode_end"},
                    {"data": "action"}
                   

                ],
                responsive: true,
                
            });

            $('#table_history').on('click','.hapus_record_detail',function(){
            var id=$(this).data('id');
            

            alert(id);
            /* $('#modal_delete_user').modal('show');
            $("#hapus_id_user").val(id);
            $("#hapus_nama").val(caption);
            $("#view_hapus_nama").text(caption);*/

            });


            
           
            $('#modal_edit_user').modal('show');

           
        });

        $('#table_user').on('click','.hapus_record',function(){
            var id=$(this).data('id');
            var no_staff=$(this).data('staff');
            var caption=$(this).data('nama');

            $('#modal_delete_user').modal('show');
            $("#hapus_id_user").val(id);
            $("#hapus_nama").val(caption);
            $("#view_hapus_nama").text(caption);



        });
    }); 

    $(document).ready(function(){
        $("#au_select_ptb").change(function(){ 

                $.ajax({
                type: "POST", 
                url: "<?php echo site_url('user_management/list_nama');?>", 
                data: {id_finger : $("#au_select_ptb").val()},
                dataType: "json",
                beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
                },
                success: function(response){
                    //var vendor = "";
                    //var username = "";
                  
                   
                if(response == "")
                {
                    $("#au_nama").val("");
                    $("#au_nopeg").val("");
                    $("#au_vendor").val("");
                    $("#au_username").val("");
                    $("#au_password").val("");
                    $("#au_no_staff").val("");
                }
                else
                {

                    vendor = response.list_jab.substring(5, 8);
                    username = response.list_jab.substring(5, 8)+"_"+response.list_id;
                    password = response.list_jab.substring(5, 8)+"_"+response.list_id;

                    $("#au_nama").val(response.list_nama);
                    $("#au_nopeg").val(response.list_nopeg);
                    $("#au_vendor").val(vendor);
                    if(username != '_')
                    {
                        $("#au_username").val(username);
                    }
                    else
                    {
                        $("#au_username").val('temp'+response.list_nopeg);
                    }
                    if(password != '_')
                    {
                        $("#au_password").val(password);
                    }
                    else
                    {
                        $("#au_password").val('temp'+response.list_nopeg);
                    }
                    
                   
                    $("#au_no_staff").val(response.list_id);
                }
                   
                }
                ,
                error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });


    $('#btn_add_user').on('click',function(){
        var model = $('#add_user').serialize();

        //console.log(model);

        $.ajax({
        type : "POST",
        url  : "<?php echo site_url('user_management/add_user')?>",
        dataType : "JSON",
        //data : {au_username:au_username,au_password:au_password,au_nama:au_nama,au_nopeg:au_nopeg,au_bagian:au_bagian,au_vendor:au_vendor,au_no_staff:au_no_staff,au_department:au_department,au_cost_center:au_cost_center},
        data:model,
        success: function(data){

            if(data.error)
            {
                
                if(data.error_au_username != '')
                {
                    $('#error_au_username').html(data.error_au_username);
                }
                else
                {
                    $('#error_au_username').html('');
                }

                if(data.error_au_password != '')
                {
                    $('#error_au_password').html(data.error_au_password);
                }
                else
                {
                    $('#error_au_password').html('');
                }

                if(data.error_au_vendor != '')
                {
                    $('#error_au_vendor').html(data.error_au_vendor);
                }
                else
                {
                    $('#error_au_vendor').html('');
                }
            }

            if(data.success)
            {
                
               
                $('#modal_add_user').modal('hide');
                
                empty_text();
               
                $('#table_user').DataTable().ajax.reload();

                //document.location.href = data.redirect;
                //toastr.success('Data baru telah di tambahkan !!');

                //toastr.options.onHidden = function() { document.location.href = data.redirect; }
                //document.location.href = data.redirect;
                toastr.success("Data baru telah di tambahkan !!");
                //document.location.href = data.redirect;
              
                
            }

        }
        });
        return false;

    });

    $('#btn_hapus_user').on('click',function(){
        var id_user=$('#hapus_id_user').val();
        
        $.ajax({
            type : "POST",
            url  : "<?php echo site_url('user_management/delete_user') ?>",
            dataType : "JSON",
            data :
            {
                id_user:id_user
            },
            
            success: function(data){

                $('#modal_delete_user').modal('hide');
                
                //document.location.href = data.redirect;
                //toastr.success('Data berhasil di hapus!!');
                $('#table_user').DataTable().ajax.reload();
               // document.location.href = data.redirect; 
               //toastr.options.onHidden = function() { document.location.href = data.redirect; }
               //document.location.href = data.redirect;
               toastr.success("Data berhasil di hapus!!");
               
            }
        });
        return false;

       
    });

    $('#btn_update_user').on('click',function(){
    
     
        var update_user = $('#update_user').serialize();
        $.ajax({
            type : "POST",
            url  : "<?php echo site_url('user_management/update_user') ?>",
            dataType : "JSON",
            data:update_user,
            success: function(data){

                $('#modal_edit_user').modal('hide');
                
                //document.location.href = data.redirect;
                //toastr.success('Data berhasil di hapus!!');
                $('#table_user').DataTable().ajax.reload();
            // document.location.href = data.redirect; 
            //toastr.options.onHidden = function() { document.location.href = data.redirect; }
            //document.location.href = data.redirect;
            $('#table_user').DataTable().ajax.reload();
            toastr.success("Data berhasil di update!!");
            
            }
        });
        return false;

      
  });
 
  
     
</script>

<script>

    function empty_text()
    {
        $('[name="au_select_ptb"]').val("");
        $('[name="au_username"]').val("");
        $('[name="au_password"]').val("");
        $('[name="au_nama"]').val("");
        $('[name="au_nopeg"]').val("");
        $('[name="au_bagian"]').val("");
        $('[name="au_vendor"]').val("");
        $('[name="au_no_staff"]').val("");
        $('[name="au_department"]').val("");
        $('[name="au_cost_center"]').val("");

    }

   
</script>
</body>
</html>
