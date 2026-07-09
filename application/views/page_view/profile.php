<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Profile</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-flui m-0">
        <div class="row">
            <?php
                $new = false;
                if($user["no_hp"] == ""){
                    $new = true;
                }
            ?>
            <div class="col-sm-6 <?php if($new){ echo "d-none"; } ?>">
                <div class="card mb-2">
                    <div class="card-header py-3">
                        <h5 class="font-weight-bold">Ganti Sandi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Sandi Lama</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="sandi_lama">
                                <button class="btn btn-outline-secondary show_password" type="button" id="show_password_1"><i class="fas fa-eye-slash"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sandi Baru</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="sandi_baru">
                                <button class="btn btn-outline-secondary show_password" type="button" id="show_password_2"><i class="fas fa-eye-slash"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Sandi Baru</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="konfirmasi_sandi_baru">
                                <button class="btn btn-outline-secondary show_password" type="button" id="show_password_3"><i class="fas fa-eye-slash"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right" id="change_pass">Simpan</button>
                    </div>
                    <div class="overlay dark" id="loading" hidden>
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card mb-2 <?php if($new){ echo "d-none"; } ?>" id="card_ganti_nomor">
                    <div class="card-header py-3">
                        <h5 class="font-weight-bold">Ganti Nomor Whatsapp</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor Whatsapp</label>
                            <input type="number" class="form-control" id="ganti_nomor_baru" placeholder="Format : (08123456789)">
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </div>
                    <div class="overlay dark" id="loading" hidden>
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
                <div class="card mb-2 <?php if(!$new){ echo "d-none"; } ?>" id="card_nomor_awal">
                    <div class="card-header py-3">
                        <h5 class="font-weight-bold">Konfirmasi Nomor Whatsapp</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor Whatsapp</label>
                            <input type="number" class="form-control" id="konfirmasi_nomor_awal" placeholder="Format : (08123456789)">
                        </div>
                        <button type="submit" class="btn btn-primary float-right" id="save_wa">Simpan</button>
                    </div>
                    <div class="overlay dark" id="loading" hidden>
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
                <input type="text" id="mode" hidden disabled>
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