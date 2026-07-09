function otpAction(value, id){
    // console.log(id);
    const id_now = id;
    let check_value = false;

    let position_pass = id_now.split("_");
    let position = parseInt(position_pass[1]);

    if(position > 0 || position < 6){
        position++;
    }

    if (parseInt(value) >= 0) {
        check_value = true;
    }

    if(!check_value){
        $('#' + id_now).val('');
    }else{
        if(position > 6){
            $('#cnf_otp').focus();
        }else{
            $('#otp_' + position).focus();
        }
    }
}

$('#cnf_otp').click(function(){
    const nomor_wa = $('#konfirmasi_nomor_awal').val();
    const pass = $('#sandi_baru').val();

    let otp = "";
    let valid = true;
    for(let i = 1; i <= 6; i++){
        let id_check = "#otp_" + i;
        let value_check = $(id_check).val();

        if(parseInt(value_check) >= 0){
            otp += value_check;
        }else{
            valid = false;
        }
    }

    if(valid){
        const mode = $('#mode').val();

        let data_send;

        if(mode === 'nomor'){
            data_send = {
                "mode" : mode,
                "nomor_wa" : nomor_wa,
                "otp" : otp
            };
        }else if(mode === 'pass'){
            data_send = {
                "mode" : mode,
                "pass" : pass,
                "otp" : otp
            };
        }

        getAjax("check_otp", data_send, function(result){
            if(result){
                if(mode === 'nomor'){
                    ntf("success", "Nomor Berhasil Disimpan");
                }else if(mode === 'pass'){
                    ntf("success", "Sandi Baru Berhasil Disimpan");
                }
                setInterval(function() {
                    location.reload();
                }, 1500);
            }else{
                ntf("error", "Harap Mengirim Ulang OTP");
            }
        });
    }else{
        ntf("error", "Kode OTP Salah!");
    }
});

$('#resend_otp').click(function(){
    $('#resend_otp').attr('hidden', true);

    const nomor_wa = $('#konfirmasi_nomor_awal').val();
    getAjax("save_wa", nomor_wa, function(result){
        if(result){
            for(let i = 1; i <= 6; i++){
                let id_check = "#otp_" + i;
                $(id_check).val('');
            }
            timer_count();
        }
    });
});

$('#save_wa').click(function(){
    const nomor_wa = $('#konfirmasi_nomor_awal').val();

    if(!nomor_wa){
        ntf("error", "Harap Memasukkan Nomor Whatsapp!");
    }else if(nomor_wa.length < 10 || nomor_wa.length > 13){
        ntf("error", "Format Nomor Whatsapp Tidak Tepat!");
    }else{
        getAjax("save_wa", nomor_wa, function(result){
            if(result){
                timer_count();
                $('#mode').val('nomor');
                $('#modal_otp').modal('show');
            }
        });
    }
});

function timer_count(){
    let seconds = 59;
    let x = setInterval(function() {

        document.getElementById("counter_time").innerHTML = seconds;

        seconds--;

        if (seconds < 0) {
            clearInterval(x);
            $('#resend_otp').removeAttr('hidden');
        }
    }, 1000);
}

$('#change_pass').click(function(){
    const pass_old = $('#sandi_lama').val();
    const pass_new = $('#sandi_baru').val();
    const pass_new_cnf = $('#konfirmasi_sandi_baru').val();

    if(!pass_old || !pass_new || !pass_new_cnf){
        ntf("error", "Harap Mengisi Semua Inputan!");
    }else if(pass_new !== pass_new_cnf){
        ntf("error", "Password Baru dan Konfirmasi Password Tidak Sama");
    }else{
        const data_send = {
            "pass_old" : pass_old,
            "pass_new" : pass_new,
            "pass_new_cnf" : pass_new_cnf
        };

        console.log(data_send);
        
        getAjax("save_pass", data_send, function(result){
            console.log(result);
            if(result){
                timer_count();
                $('#mode').val('pass');
                $('#modal_otp').modal('show');
            }else{
                ntf("error", "Password Lama Salah");
            }
        });
    }
});

$('.show_password').mousedown(function(){
    $(this).html('');
    $(this).html('<i class="fas fa-eye"></i>');

    if(this.id === 'show_password_1'){
        $('#sandi_lama').attr('type', 'text');
    }else if(this.id === 'show_password_2'){
        $('#sandi_baru').attr('type', 'text');
    }else if(this.id === 'show_password_3'){
        $('#konfirmasi_sandi_baru').attr('type', 'text');
    }

});

$('.show_password').mouseup(function(){
    $(this).html('');
    $(this).html('<i class="fas fa-eye-slash"></i>');
    
    if(this.id === 'show_password_1'){
        $('#sandi_lama').attr('type', 'password');
    }else if(this.id === 'show_password_2'){
        $('#sandi_baru').attr('type', 'password');
    }else if(this.id === 'show_password_3'){
        $('#konfirmasi_sandi_baru').attr('type', 'password');
    }
});

$('.show_password').mouseleave(function(){
    $(this).html('');
    $(this).html('<i class="fas fa-eye-slash"></i>');
    
    if(this.id === 'show_password_1'){
        $('#sandi_lama').attr('type', 'password');
    }else if(this.id === 'show_password_2'){
        $('#sandi_baru').attr('type', 'password');
    }else if(this.id === 'show_password_3'){
        $('#konfirmasi_sandi_baru').attr('type', 'password');
    }
});

function getAjax(url, param, callback) {
    $.ajax({
        url: "../index.php/profile/" + url,
        type: "POST",
        dataType: "json",
        data: {param:param},
        success: function (data) {
            callback(data);
        },
        error: function () {
            console.error("error getAjax");
        },
    });
}