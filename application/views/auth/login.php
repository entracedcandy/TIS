<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center vh-100 align-items-center">
                    <div class="col-lg-5">
                        <div class="card">

                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Login</h3>
                            </div>

                            <div class="card-body">

                                <?= $this->session->flashdata('message'); ?>
                                
                                <form method="post" action="<?= base_url(); ?>">
                                    <div class="form-group">
                                        <label class="small mb-1" for="input_user">Username</label>
                                        <input class="form-control py-4" id="input_user" name="input_user" type="text" placeholder="Enter Username" value="<?= set_value('input_user');?>" />
                                        <?= form_error('input_user',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">Password</label>
                                        <input class="form-control py-4" id="input_password" name="input_password" type="password" placeholder="Enter password" />
                                         <?= form_error('input_password',' <small class="text-danger pl-1">','</small>');?>
                                    </div>
                                

                            </div>
                            <div class="card-footer">
                                <div class="form-group float-right m-0">
                                    <button class="btn btn-primary" type="submit">Login</button>
				</form>
                                </div>
                                <!-- <div class="small"><a href="<?// base_url('Login/registration'); ?>">Need an account? Sign up!</a></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
       