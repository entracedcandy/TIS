       
            <!--Body-->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-2 text-dark"><?= $title;?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?= $title;?></li>
                        </ol>   
                        
                        

                    </div>
                    
                
                </main>
                 <!--footer-->
                <?php $this->load->view('templates/dash_footer');?>

            </div>
            <!--End Body-->


<!--Script Footer=======================================================================================================-->
        </div>


        <!--index.php !-->     
        <script src="<?= base_url('assets/');?>js/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/scripts.js"></script>
       
        <script src="<?= base_url('assets/');?>js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        
        <script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/');?>context-menu/jquery.contextMenu.min.js"></script>

        <script type="text/javascript" src="<?= base_url('application/page_js/');?>index.js"></script>
        <script src="<?= base_url('assets/');?>js/Chart.min.js" crossorigin="anonymous"></script>

         

    </body>
</html>
       
