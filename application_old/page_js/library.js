// $(window).on('beforeunload', function() {
//     $.ajax({
//         url: '../cpar/Home/bfUnload',
//         type: 'POST',
//         dataType: 'json',
//         success: function(data){
//         },
//         error: function() {
//             console.error("error getAjax");
//         }
//     });
// });

function ntf(status, msg){
    Swal.fire({
        icon: status,
        title: msg,
        showConfirmButton: false,
        timer: 1500
    });
}

function start_loading(){
    Swal.fire({
        title: "Harap Menunggu",
        // html: "Data Sedang di Unggah",
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
        }
      });
}

function stop_loading(){
    Swal.close();
}