      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid mb-4">

            <div class="content-header">
              <div class="container-fluid mt-4">
                <h1 id="title_report" class="font-weight-bold">
                  <?php echo $judulMenu; ?>
                </h1>
              </div>
            </div>

            <div class="content">
              <div class="container-fluid">
                <ol class="breadcrumb">
                  <?php
                    foreach ($breadcrumbs as $brd) {
                      ?>
                        <li class="breadcrumb-item"><?php echo $brd; ?></li>
                      <?php
                    }
                  ?>
                </ol>
              </div>
            </div>

            <div class="loading" id="loading">
                <img src="<?= base_url('assets/');?>image/loading.gif"  style="position:fixed; top:45%; left:45%;"/>
            </div>

            <div class="content">
              <div class="container-fluid">
                <?php include 'componentCreator.php'; ?>
              </div>
            </div>
          </div>
        </main>
        <?php $this->load->view('templates/dash_footer'); ?>

      </div>

    </div>
    
    <script>
      sessionStorage.setItem("allFunc", <?= json_encode(substr($allFunc, 0, strlen($allFunc) - 1)); ?>);
      sessionStorage.setItem("select2elm", <?= json_encode(substr($select2elm, 0, strlen($select2elm) - 1)); ?>);
      sessionStorage.setItem("requiredElement", <?= json_encode(substr($requiredElement, 0, strlen($requiredElement) - 1)); ?>);
      sessionStorage.setItem("tableParam", <?= json_encode(substr($tableParam, 0, strlen($tableParam) - 1)); ?>);
      sessionStorage.setItem("chartParam", <?= json_encode(substr($chartParam, 0, strlen($chartParam) - 1)); ?>);
      sessionStorage.setItem("totalCmp", <?= json_encode($totalCmp); ?>);
      sessionStorage.setItem("modelFuncName", <?= json_encode($modelFuncName); ?>);
    </script>
    <script src="<?= base_url('assets/AdminLTE/');?>jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/');?>js/jquery-ui.js"></script>
    <script src="<?= base_url('assets/');?>js/scripts.js"></script>

    <script src="<?= base_url('assets/');?>js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>uplot/uPlot.iife.min.js"></script>
    <script src="<?= base_url('assets/select2/js/');?>select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript" src="<?= base_url('application/page_js/');?>dynamic_report.js?v=4.1.1.1"></script>

  </body>
</html>
