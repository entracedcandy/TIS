        <script script src="<?= base_url('assets/');?>js/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() { 
                $('#dept').select2({width:'100%'});
                $('#halo').select2();
            });
        </script>
    </body>
</html>
