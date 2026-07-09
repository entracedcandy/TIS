$(document).ready(function() { 
    const notif = document.getElementById("notif").value;

    if(notif){
        ntf("error", notif);
    }
});

$('#send_otp').click(function(){
    const user = $('#user_fp').val();

    if(!user){
        ntf("error", "Harap Masukkan Username atau No HP");
    }else{
        start_loading();

        $.ajax({
            url: '../cpar/Home/forgot_pass',
            type: 'POST',
            dataType: 'json',
            data: {data:user},
            success: function(data){
                if(data){
                    stop_loading();
                    timer_count();
                    $('#modal_otp').modal('show');
                }else{
                    ntf("error", "Username atau No HP Tidak Terdaftar, Harap Menghubungi PGA!");
                }
            },
            error: function() {
                console.error("error getAjax");
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

$('#resend_otp').click(function(){
    $('#resend_otp').attr('hidden', true);

    const user = $('#user_fp').val();

    $.ajax({
        url: '../cpar/Home/forgot_pass',
        type: 'POST',
        dataType: 'json',
        data: {data:user},
        success: function(data){
            if(data){
                timer_count();
                $('#modal_otp').modal('show');
            }else{
                ntf("error", "Username atau No HP Tidak Terdaftar, Harap Menghubungi PGA!");
            }
        },
        error: function() {
            console.error("error getAjax");
        }
    });
});

$('#cnf_otp').click(function(){
    const user = $('#user_fp').val();

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
        let data_send;

        data_send = {
            "user" : user,
            "otp" : otp
        };
        
        getAjax("check_otp", data_send, function(result){
            if(result){
                ntf("success", "Sandi Baru Telah Dikirimkan pada No HP User");
                
                for(let i = 1; i <= 6; i++){
                    let id_check = "#otp_" + i;
                    $(id_check).val('');
                }
                
                $('#modal_otp').modal('hide');
                $('#forgotPass').modal('hide');
            }else{
                ntf("error", "Harap Mengirim Ulang OTP");
            }
        });
    }else{
        ntf("error", "Kode OTP Salah!");
    }
});

function getAjax(url, param, callback) {
    $.ajax({
        url: "../cpar/Home/" + url,
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