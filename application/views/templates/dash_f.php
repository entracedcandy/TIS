            </div>
        </div>

        <script script src="<?= base_url('asset/');?>js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/jquery-ui/jquery-ui.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/moment/moment.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/daterangepicker/daterangepicker.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/select2/js/select2.full.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/sweetalert2/sweetalert2.all.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/fullcalendar/main.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('application/');?>page_js/library.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/clusterize.js@1.0.0/clusterize.min.js"></script>
        <script>
            
            function hideAddressBar()
            {
                if(!window.location.hash)
                { 
                    if(document.height <= window.outerHeight + 10)
                    {
                        document.body.style.height = (window.outerHeight + 50) +'px';
                        setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
                    }
                    else
                    {
                        setTimeout( function(){ window.scrollTo(0, 1); }, 0 ); 
                    }
                }
            } 
            window.addEventListener("load", hideAddressBar );
            window.addEventListener("orientationchange", hideAddressBar );    
        </script>

        <?php if(isset($js)){ 
            ?>
                <script script src="<?= base_url('application/');?>page_js/<?= $js ?>.js" crossorigin="anonymous"></script>
        <?php
        } ?>
    </body>
</html>