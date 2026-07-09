<body>
    <div id="layoutSidenav_content">
        <div class="container-fluid">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row my-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold"><?= $title ?></h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card border-secondary">
                        <div class="card-body">
                            <?php include 'grid-element.php'?>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-secondary float-right" onclick="save_data()" id="save_data">Simpan</button>
                        </div>
                    </div>
                    <div class="card border-secondary">
                        <div class="card-header">
                            <label>Data Input</label>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                        </tr>
                                        <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        </tr>
                                        <tr>
                                        <th scope="row">3</th>
                                        <td colspan="2">Larry the Bird</td>
                                        <td>@twitter</td>
                                        </tr>
                                    </tbody>
                                </table> -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <input type="text" id="id_elm_required" value="<?= $id_elm_required ?>" hidden>
                <input type="text" id="id_elm_all" value="<?= $id_elm_all ?>" hidden>
                <input type="text" id="type_elm_all" value="<?= $type_elm_all ?>" hidden>
                <input type="text" id="name_elm_all" value="<?= $name_elm_all ?>" hidden>
                <input type="text" id="func_pass" value="<?= $func_pass ?>" hidden>
            </section>
        </div>
    </div>
    
    <script src="<?= base_url('assets/AdminLTE/');?>jquery/jquery.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>jquery-ui/jquery-ui.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>moment/moment.min.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>daterangepicker/daterangepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/');?>js/scripts.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>select2/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= base_url('application/page_js/');?>dynamicInput.js?v=1"></script>
</body>
</html>