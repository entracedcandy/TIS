<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">DOC Tracking</h1>
                </div>
                <div class="d-none">
                    <input type="text" value='<?= $group_user ?>' id='gu' hidden disabled>
                    <input type="text" value='<?= $department ?>' id='dp' hidden disabled>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-flui m-0">
            <div class="card card-dark mb-2">
                <div class="card-header">
                    <label class="ml-2">Silahkan pilih Pelanggan</label>
                    <div class="form-floating">
                        <div class="col-sm-4 col-xs-12">
                            <select class="form-select" id="allPT" onchange="PTChange(this.value)">
                                <option value="" selected></option>
                                <?php
                                    foreach($allPT as $ap){
                                        ?>
                                            <option value="<?= $ap->id ?>"><?= $ap->nama_pt ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class='align-middle text-center' scope="col">No</th>
                                    <th class='align-middle text-center' scope="col">Farm</th>
                                    <th class='align-middle text-center' scope="col">Alamat</th>
                                    <th class='align-middle text-center' scope="col">Tipe</th>
                                    <th class='align-middle text-center' scope="col">Kuota Farm</th>
                                    <th class='align-middle text-center' scope="col">DOC Chick-In</th>
                                    <th class='align-middle text-center' scope="col">Status</th>
                                    <th class='align-middle text-center' scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table_content">
                        
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="overlay dark d-none" id="loading_table">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="md_ci" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="md_ci" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Chick-In</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearInputCI()"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12" id='form_input_ci'>
                    <label>Input Data Chick-In</label>
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="tanggal_ci">
                        <label for="tanggal_ci">Date Chick In</label>
                    </div>
                    <div id="notif_tanggal_ci" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="number" class="form-control" id="total_ci">
                        <label for="total_ci">Total Chick In</label>
                    </div>
                    <div id="notif_total_ci" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <label>Input Fix Marketing</label>
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="tanggal_doc">
                        <label for="tanggal_doc">Date Survey</label>
                    </div>
                    <div id="notif_tanggal_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="estimate_doc">
                        <label for="estimate_doc">Date Estimate</label>
                    </div>
                    <div id="notif_estimate_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <select class="form-control" id="progress_doc">
                        <label for="progress_doc">Progress</label>
                            <option value="" disabled selected>Progress</option>
                            <option value="Kandang Belum Siap">Kandang Belum Siap</option>
                            <option value="Masih Proses Deal">Masih Proses Deal</option>
                            <option value="Sudah Deal dengan CPI">Sudah Deal dengan CPI</option>
                            <option value="Telah Deal dengan Kompetitor">Telah Deal dengan Kompetitor</option>
                        </select>
                    </div>
                    <div id="notif_progress_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="catatan_doc">
                        <label for="catatan_doc">Note Survey</label>
                    </div>
                    <div id="notif_catatan_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <input type="text" id="id_farm" hidden disabled>
                    <input type="number" id="status_prog_farm" hidden disabled>
                    <input type="text" id="id_progress" hidden disabled>
                </div>
            </div>
            <div class="modal-footer" id='btn_input_ci'>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearInputCI()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="save_ci()">Save Data</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="md_ci_old" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="md_ci_old" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Chick-In</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearInputCI()"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12" id='form_input_ci'>
                    <label>Input Data Chick-In</label>
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="tanggal_ci">
                        <label for="tanggal_ci">Date Chick In</label>
                    </div>
                    <div id="notif_tanggal_ci" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="number" class="form-control" id="total_ci">
                        <label for="total_ci">Total Chick In</label>
                    </div>
                    <div id="notif_total_ci" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-check form-switch ml-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="last_day_ci">
                        <label class="form-check-label" for="last_day_ci">Last Day Chick-In</label>
                    </div>

                    <input type="text" id="id_farm" hidden disabled>
                    <input type="number" id="status_prog_farm" hidden disabled>
                    <input type="text" id="id_progress" hidden disabled>
                </div>
                
                <div class="col-sm-12 mt-3">
                    <div class="row d-none" id="info_ci">
                        <label>Info Data Chick-In</label>
                        <div class="col-sm-6">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="far fa-flag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">First Date Chick-In</span>
                                    <span class="info-box-number" id='info_date_ci_pertama'></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="far fa-flag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Last Date Chick-In</span>
                                    <span class="info-box-number" id='info_date_ci_terakhir'></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-sm d-none mt-2" id='table_log_ci'>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class='align-middle text-center' scope="col">No</th>
                                    <th class='align-middle text-center' scope="col">Date</th>
                                    <th class='align-middle text-center' scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="table_log_ci_content">
                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id='btn_input_ci'>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearInputCI()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="save_ci()">Save Data</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="md_tis" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="md_tis" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Form TIS Survey</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearInputTIS()"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12" id='form_input_tis'>
                    <label>Input Survey Farm</label>
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="tanggal_tis">
                        <label for="tanggal_tis">Date Survey</label>
                    </div>
                    <div id="notif_tanggal_tis" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="estimate_tis">
                        <label for="estimate_tis">Date Estimate</label>
                    </div>
                    <div id="notif_estimate_tis" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="catatan_tis">
                        <label for="catatan_tis">Note Survey</label>
                    </div>
                    <div id="notif_catatan_tis" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-check form-switch ml-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="kosong_tis">
                        <label class="form-check-label" for="kosong_tis">Farm is Empty?</label>
                    </div>

                    <input type="text" id="id_farm_tis" hidden disabled>
                    <input type="number" id="status_prog_farm_tis" hidden disabled>
                    <input type="text" id="id_progress_tis" hidden disabled>
                    <input type="text" id="department_tis" hidden disabled>
                </div>
                
            </div>
            <div class="modal-footer" id='btn_input_tis'>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearInputTIS()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="save_tis()">Save Data</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="md_doc" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="md_doc" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Form DOC Survey</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearInputDOC()"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12" id='form_input_doc'>
                    <label>Input Fix Marketing</label>
                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="tanggal_doc">
                        <label for="tanggal_doc">Date Survey</label>
                    </div>
                    <div id="notif_tanggal_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="date" class="form-control" id="estimate_doc">
                        <label for="estimate_doc">Date Estimate</label>
                    </div>
                    <div id="notif_estimate_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <select class="form-control" id="progress_doc">
                        <label for="progress_doc">Progress</label>
                            <option value="" disabled selected>Progress</option>
                            <option value="Kandang Belum Siap">Kandang Belum Siap</option>
                            <option value="Masih Proses Deal">Masih Proses Deal</option>
                            <option value="Sudah Deal dengan CPI">Sudah Deal dengan CPI</option>
                            <option value="Telah Deal dengan Kompetitor">Telah Deal dengan Kompetitor</option>
                        </select>
                    </div>
                    <div id="notif_progress_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="catatan_doc">
                        <label for="catatan_doc">Note Survey</label>
                    </div>
                    <div id="notif_catatan_doc" class="d-block font-weight-bold valid-feedback mb-2 ml-1"></div>

                    <input type="text" id="id_farm_doc" hidden disabled>
                    <input type="number" id="status_prog_farm_doc" hidden disabled>
                    <input type="text" id="id_progress_doc" hidden disabled>
                    <input type="text" id="department_doc" hidden disabled>
                </div>
                
                <div class="col-sm-12 mt-3">
                    <div class="row d-none-doc" id="info_ci">
                        <label>List Date Estimate</label>
                        <div class="col-sm-6">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="far fa-flag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">First Date Estimate</span>
                                    <span class="info-box-number" id='info_date_doc_pertama'></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="far fa-flag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Last Date Estimate</span>
                                    <span class="info-box-number" id='info_date_doc_terakhir'></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-sm d-none-doc mt-2" id='table_log_doc'>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class='align-middle text-center' scope="col">No</th>
                                    <th class='align-middle text-center' scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody id="table_log_doc_content">
                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id='btn_input_doc'>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearInputDOC()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="save_doc()">Save Data</button>
            </div>
            </div>
        </div>
    </div>
</div>