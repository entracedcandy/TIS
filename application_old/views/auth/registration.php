<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
                            <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                            <div class="card-body">

                                <form method="post" action="<?= base_url('Login/registration');?>">
                                   
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">Username</label>
                                        <input class="form-control py-4" id="input_username" name="input_username" type="text" aria-describedby="emailHelp" placeholder="Enter Username" value="<?= set_value('input_username');?>" />
                                        <?= form_error('input_username',' <small class="text-danger pl-1">','</small>');?>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="input_reg_firstname">First Name</label>
                                                <input class="form-control py-4" id="input_reg_firstname" name="input_reg_firstname" type="text" placeholder="First Name" value="<?= set_value('input_reg_firstname');?>"  />
                                                 <?= form_error('input_reg_firstname',' <small class="text-danger pl-1">','</small>');?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="input_reg_lastname">Last Name</label>
                                                <input class="form-control py-4" id="input_reg_lastname" name="input_reg_lastname" type="text" placeholder="First Name" value="<?= set_value('input_reg_lastname');?>"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="input_vendor">Vendor</label>
                                        <select name="" id="" class="custom-select">
                                            <option value="" disabled selected></option>
                                            <?php
                                                foreach($vendor as $v){
                                                    ?>
                                                        <option value="<?= $v->vendor ?>"><?= $v->caption ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1">Department</label>
                                                <select name="" id="dept" class="custom-select">
                                                    <option value="" disabled selected></option>
                                                    <?php
                                                        foreach($dept as $d){
                                                            ?>
                                                                <option value="<?= $d->id_department ?>"><?= $d->caption . " | " . $d->cost_center ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="input_group_user">Bagian</label>
                                                <select name="" id="" class="custom-select" disabled>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="input_no_reg_nik">NIK / No Reg</label>
                                        <input class="form-control py-4" id="input_nik_no_reg" name="input_reg_email" type="text" placeholder="Enter NIK / No Reg" value="<?= set_value('input_reg_email');?>" />
                                       <?= form_error('input_reg_email',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                     
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control py-4" id="input_reg_email" name="input_reg_email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?= set_value('input_reg_email');?>" />
                                       <?= form_error('input_reg_email',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">Nomor HP (Gunakan Nomor yang Sudah Terdaftar Dengan Whatsapp)</label>
                                        <input class="form-control py-4" id="input_reg_no_hp" name="input_reg_no_hp" type="text" placeholder="Enter Number Phone" value="<?= set_value('input_reg_email');?>" />
                                       <?= form_error('input_reg_email',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" id="input_reg_password" name="input_reg_password" type="password" placeholder="Enter password" value="<?= set_value('input_reg_password');?>"/>
                                                 <?= form_error('input_reg_password',' <small class="text-danger pl-1">','</small>');?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                <input class="form-control py-4" id="input_reg_confpassword" name="input_reg_confpassword" type="password" placeholder="Confirm password" value="<?= set_value('input_reg_confpassword');?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4 mb-0">
                                        <button class="btn btn-primary btn-block" type="submit">Create Account</button>
                                    </div>
                                </form>

                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="<?= base_url('Login'); ?>">Have an account? Go to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
