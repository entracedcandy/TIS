<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CP - APPS</title>
        <link href="<?= base_url('asset/');?>bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>adminlte/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
        <link href="<?= base_url('asset/');?>css/style.css" rel="stylesheet" />
        <link rel="icon" href="<?= base_url('asset/');?>img/logo-pokphand-blue-01.png" type="image/png">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    </head>
    <body class="login-body">
        <div class="container">
            <div class="row vh-100 align-items-center justify-content-center">
                <div class="col-7 d-md-inline d-none text-center">
                    <img class="w-100 p-5 sdw-img" src="<?= base_url('asset/');?>img/logo-pokphand.png">
                </div>
                <div class="col-md-5">
                    <h2 class="text-center mb-3 fw-bold text-white sdw">Welcome to CP - APPS</h2>
                    <div class="card card-trnspt text-white mx-5">
                        <?= form_open("Home/login") ?>
                        <div class="card-body">
                            <div class="mb-3">
                                <h3 class="card-title text-center">Login</h3>
                            </div>
                            <div class="mb-3">
                                <label for="userName" class="form-label">Username</label>
                                <input type="text" class="form-control" id="userName" name="userName">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-4">
                                <a class="btn d-flex float-start mb-2 text-white" data-bs-toggle="modal" data-bs-target="#forgotPass">Lupa Sandi ?</a>
                                <button type="submit" class="btn btn-primary d-flex float-end mb-2">Login</button>
                            </div>
                            <input type="text" id="notif" value="<?= $notif ?>" hidden disabled>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="forgotPass" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mx-3">
                            <h1 class="modal-title fs-5 mb-3 text-center">Atur Ulang Sandi</h1>
                        </div>
                        <div class="row mx-3 mb-3">
                            <label for="userName" class="form-label m-0 p-0 mb-2">Masukkan Username / No HP</label>
                            <input type="text" class="form-control" id="user_fp">
                        </div>
                        <div class="row mx-5 justify-content-center">
                            <div class="col-sm-4 text-center">
                                <button class="btn btn-primary" id="send_otp">Submit</button>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_otp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mx-3">
                            <h1 class="modal-title fs-5 mb-3 text-center">Masukkan Kode OTP</h1>
                        </div>
                        <div class="row mx-3 mb-3">
                            <div class="col-sm-2">
                                <input type="text" id="otp_1" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="otp_2" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="otp_3" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="otp_4" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="otp_5" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="otp_6" class="form-control" maxlength="1" style="text-align:center;" onkeyup="otpAction(this.value, this.id)">
                            </div>
                        </div>
                        <div class="row mx-3">
                            <h6 class="modal-title fs-6 text-center">OTP Berlaku Dalam Waktu : <span id="counter_time">60</span></h6>
                            <p class="text-center"><a class="link-offset-2 link-underline link-underline-opacity-0" id="resend_otp" style="cursor:pointer;" hidden>Kirim Ulang OTP</a></p>
                        </div>
                        <div class="row mx-5 justify-content-center">
                            <div class="col-sm-4 text-center">
                                <button class="btn btn-primary" id="cnf_otp">Submit</button>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>

        <script script src="<?= base_url('asset/');?>js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>adminlte/plugins/sweetalert2/sweetalert2.all.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('asset/');?>bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('application/');?>page_js/library.js" crossorigin="anonymous"></script>
        <script script src="<?= base_url('application/');?>page_js/login.js" crossorigin="anonymous"></script>
    </body>
</html>

       