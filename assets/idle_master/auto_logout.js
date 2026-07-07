 <script src="<?= base_url('assets/');?>js/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="<?= base_url('assets/');?>idle_master/jquery.idle.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/');?>idle_master/auto_logout.js"></script>
 $(document).idle({
    onIdle: function(){
        window.location="/Login/logout";                
    },
    idle: 10000
});
      
