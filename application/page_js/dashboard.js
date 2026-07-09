let group_user = document.getElementById('gu').value;
let department = document.getElementById('dp').value;
let iduser     = document.getElementById('ki').value;

$(document).ready(function() { 
    // console.log(dashDOCATAS);
    // document.getElementById('table_content_atas').innerHTML = '';
    if(group_user === "ppl_tis" || group_user === "koor_tis"){
        document.getElementById('management').classList.add('d-none');
        document.getElementById('doc').classList.add('d-none');
    }else{
        document.getElementById('tis').classList.add('d-none');
    }
});

function getAjax(url, param, callback){
    $.ajax({
        url: '../trackingDoc/' + url,
        type: 'POST',
        dataType: 'json',
        data: {
            param:param
        },
        success: function(data){
            // console.log(data);
            callback(data);
        },
        error: function() {
            console.error("error getAjax");
        }
    });
}