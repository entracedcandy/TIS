<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title href="<?= base_url('Login');?>"><?= $title;?></title>
        <link href="<?= base_url('asset/');?>css/styles.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
    </head>
    <body class="bg-primary">
        <main>
            <div class="container">
                <div class="row justify-content-center align-items-center vh-100">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg my-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Login</h3>
                            </div>
                            <div class="card-body text-center">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogin" onclick="startFace()">Login</button>
                                <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalOTP">OTP</button> -->
                                <div id="fc">
                                    <input type="text" id="files" value="<?= $files ?>" hidden disabled>
                                    <input type="password" id="nm" value="<?= $this->security->get_csrf_token_name(); ?>" hidden disabled>
                                    <input type="password" id="ps" value="<?= $this->security->get_csrf_hash(); ?>" hidden disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="modalLogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center" id="video-frame">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOTP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="text-center my-4">Masukkan Kode OTP</h5>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_1" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_2" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_3" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_4" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_5" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="otp_6" style="text-align:center;" maxlength="1" oninput="inputOtp(this.value, this.id)">
                                </div>
                            </div>
                        </div>
                        <div class="my-4 text-center">
                            <button class="btn btn-primary" id="cnf_otp">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOpsi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <?= form_open("http://10.13.10.215/cp_hmis2/index.php?r=apiFaceLogin/MasukFace") ?>
                        <div class="row m-2" id="hmis">
                            <?php echo form_submit('hmis', 'HMIS', 'class="btn btn-primary text-center" id="hmis_login"'); ?>
                        </div>
                        <?= form_close() ?>
                        <?= form_open("../cpi2/home_fc.php") ?>
                        <div class="row m-2" id="attd">
                            <?php $pass = password_hash('Krian1234', PASSWORD_DEFAULT); ?>
                            <input type="password" name="pass_ps" value="<?= $pass ?>" hidden>
                            <?php echo form_submit('attd', 'Attandance Record', 'class="btn btn-primary text-center" id="attd_login"'); ?>
                        </div>
                        <?= form_close() ?>
                        <?= form_open("http://10.13.10.215/cp_integrasi3/index.php?r=apiFaceLogin/MasukFace") ?>
                        <div class="row m-2" id="integrasi">
                            <?php echo form_submit('ai', 'Auto Invoice', 'class="btn btn-primary text-center" id="ai_login"'); ?>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url('asset/');?>js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>adminlte/plugins/sweetalert2/sweetalert2.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/');?>face-api/face-api.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="<?= base_url('application/page_js/');?>home.js"></script>
    </body>
</html>