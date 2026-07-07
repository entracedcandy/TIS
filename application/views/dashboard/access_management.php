       
            <!--Body-->
            <div id="layoutSidenav_content">
                <main>
                    <!--Main-->
                    <div class="container-fluid">
                       <!--Title-->
                       <h1 class="mt-4"><?= $title['caption'];?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?= $title['caption'];?></li>
                        </ol>   

                        <!--content-->  

                        <div class="card mb-4">

                        <div class="card-header-plus">
                            <div class="col-md-12 animated fadeInRight">
                                <div class="ui-bordered px-4 pt-4 mb-4">
                                    <form action="" method="post" accept-charset="utf-8">
                                        <input type="hidden" name="csrf_hrsale" value="" />                                                               

                                        <div class="form-row">
                                            
                                            <div class="col-md mb-3" >
                                                <label class="form-label">Bagian</label>
                                                <select name="s_bagian" id="s_bagian" class="form-control" placeholder="" required>
                                        <option value="">- Pilih bagian -</option>
                                        <?php foreach($bagian as $bagian):?>
                                    
                                            <option value="<?= $bagian["bagian"];?>"><?= $bagian["bagian"];?></option>
                                        <?php endforeach;?>
                                    </select>
                                            </div>
                                            
                                        </div>
                                        
                                    </form>        
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                           
                            <div class="row" id="row_menu_container">
                                <div class="col-md-4">
                                    <ol class="tree-structure">
                                        <li class='active has-sub'><a><span>Input</span></a>
                                            <ol id="menu_input_list">
                                                
                                              
                                            </ol>
                                        </li>
                                        
                                    </ol>

                                </div>

                                <div class="col-md-4">
                                    <ol class="tree-structure">
                                        <li class='active has-sub'><a><span>Report</span></a>
                                            <ol id="menu_report_list">
                                            
                                               
                                                
                                            </ol>
                                        </li>
                                        
                                    </ol>

                                </div>
                                <div class="col-md-4">
                                    <ol class="tree-structure">
                                        <li class='active has-sub'><a><span>Document</span></a>
                                            <ol id='menu_document_list'>
                                                
                                             
                                                
                                            </ol>
                                        </li>
                                        
                                    </ol>

                                </div>
                            </div>


                        </div>
                    </div>

                <!--end main-->

                
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


       
        <!--treeview-->
  
       
        <script src="<?= base_url('assets/');?>js/treeview.min.js" crossorigin="anonymous"></script>

        <script>

            $(document).ready(function(){

                
                    var a = $.ajax({
                    type: "POST", 
                    url: "<?php echo site_url('access_management/list_menu');?>", 
                    data: {bagian : $("#s_bagian").val()},
                    dataType: "json",
                    success: function(response)
                    {
                        for(var i=0; i < response.length; i++)
                        {
                            if(response[i]['submenu'] == 0)
                            {
                                if (response[i]['has_sub'] == 1)
                                {
                                    HasSub = 'has-sub';
						            SubContainer = "<ul id='SubContainer,"+response[i]['id_menu']+"'></ul>";
                                }
                                else
                                {
                                    HasSub = '';
						            SubContainer = '';
                                }
                                
                                document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML = 
                                document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML +
                                '<li class="" type="'+response[i]['src']+'" id="menu_item_'+response[i]['id_menu']+'" >'+
                                ' <div class="row">';
                                       
                                
                                if(response[i]['id_akses'] == null)
                                {
                                    document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML =document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML +
                                        '<a class=""><span>'+response[i]['caption']+"-"+response[i]['bagian']+'</span></a>' +
                                        '<label class="custom-control custom-checkbox">'+
                                        '<input id='+response[i]['id']+'" type="checkbox" class="custom-control-input">'+
                                        '<span class="custom-control-indicator"></span>'+
                                        '</label>';
                                }
                                else
                                {
                                    document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML =document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML +
                                        '<a class=""><span>'+response[i]['caption']+"-"+response[i]['bagian']+'</span></a>' +
                                        '<label class="custom-control custom-checkbox">'+
                                        '<input id='+response[i]['id']+'" type="checkbox" checked="checked" class="custom-control-input">'+
                                        '<span class="custom-control-indicator"></span>'+
                                        '</label>';
                                }
                              

                                document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML =document.getElementById('menu_'+response[i]['menu_type']+'_list').innerHTML +
                                    '</div>'+
                                SubContainer+
                                '</li>';

                            }
                            else
                            {
                                document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML = 
                                document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML +
                                '<li class="" type="'+response[i]['src']+'" id="menu_item_'+response[i]['id_menu']+'" >'+
                                ' <div class="row">';
                                if(response[i]['id_akses'] == null)
                                {
                                    document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML =document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML +
                                        '<a class=""><span>'+response[i]['caption']+"-"+response[i]['bagian']+'</span></a>'+
                                        '<label class="custom-control custom-checkbox">'+
                                        '<input id='+response[i]['id']+'" type="checkbox" class="custom-control-input">'+
                                        '<span class="custom-control-indicator"></span>'+
                                        '</label>';
                                }
                                else
                                {
                                    document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML =document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML +
                                    '<a class=""><span>'+response[i]['caption']+"-"+response[i]['bagian']+'</span></a>'+
                                        '<label class="custom-control custom-checkbox">'+
                                        '<input id='+response[i]['id']+'" type="checkbox" checked="checked" class="custom-control-input">'+
                                        '<span class="custom-control-indicator"></span>'+
                                        '</label>';
                                }
                                
                                

                                document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML =document.getElementById('SubContainer,'+response[i]['submenu']+'').innerHTML +
                                    '</div>'+
                                '</li>';
                            

                            }
                          
                           
                        }
                        
                       
                        }, error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }

                    });

                   
                   
                });
           
                $("#s_bagian").change(function(){ 
                    a.ajax.reload();
                });
            $(document).on('change', 'input[type="checkbox"]', function(e){
        
                var id    = $(this).attr('id');
               // var id=$(this).data('id');
                
                if($(this).is(":checked"))
                {
                 
                   alert("on");
                   console.log(id);
                }
                else
                {
                    alert("off");
                    console.log(id);
                }
            });

           

            //https://www.webslesson.info/2017/05/make-treeview-using-bootstrap-treeview-ajax-jquery-with-php.html
            //https://codepen.io/akautsar/pen/yXZRdL?editors=1111

        </script>
    </body>
</html>
