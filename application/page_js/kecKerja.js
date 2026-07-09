var jmlrow = 1;
var jmlidin = 1;
var arr_input = [];

function addFields() {
    if(jmlrow === 6){
        alert("Maksimal Progress saat ini 6!");
    }else{
        jmlrow++;
        jmlidin++;

        var objTo = document.getElementById('progressrow');
        var divtest = document.createElement('div');
        var namediv = 'newRow_'+jmlidin+'';
        arr_input.push(namediv);
        divtest.id = namediv;
        divtest.className = namediv;
        divtest.innerHTML = '<label id="lbl'+jmlidin+'"><b>Progress Ke ' + jmlrow +' : </b></label><div class="form-row"><input id="seq_prog_' + jmlidin +'" value="' + jmlidin +'" hidden><div class="form-group col-md-4"><label>Nama Progress</label><input class="form-control" id="nama_prog_' + jmlidin +'" style="height:39px;" required><div id="n_nama_' + jmlidin +'" class="d-block font-weight-bold" style="display:none"></div></div><div class="form-group col-md-7"><label>Deskripsi Progress</label><input class="form-control" id="desc_prog_' + jmlidin +'" style="height:39px;" required><div id="n_desc_' + jmlidin +'" class="d-block font-weight-bold" style="display:none"></div></div><div class="form-group col-md-1"><label for="inputPassword4">&nbsp</label><button type="button" class="form-control btn btn-outline-warning" id="delbut" style="height:39px;" onclick="delFields(\'newRow_'+jmlidin+'\');"><i class="fas fa-backspace"></i></button></div></div>';
        // console.log(divtest.innerHTML);
        objTo.appendChild(divtest)
    }
    console.log(arr_input);
}

function delFields(param) {
    // console.log(param);
    var index = arr_input.indexOf(param);
    if (index > -1) { 
        arr_input.splice(index, 1); 
    }
    
    // var div = document.getElementById('newRow_' + jmlrow);
    var div = document.getElementById(param);
    div.parentNode.removeChild(div);
    jmlrow--;
    
    for(var i = 0; i < arr_input.length; i++){
        var urutanIdPass = arr_input[i].split("_");
        var urutanId = urutanIdPass[1];
        var namaLabel = "lbl"+urutanId;
        var namaSeq = "seq_prog_"+urutanId;
        // console.log(urutanId);
        document.getElementById(namaLabel).innerHTML = "<b>Progress Ke " + (i+2) + " :</b>";
        document.getElementById(namaSeq).value = (i+2);
    }
    console.log(arr_input);
}

function tambahProgress(){
    var allInputStep = [];
    var getInput1 = [];
    var flag_valid_tambah =true;
    var seq_now_1 = document.getElementById('seq_prog_1').value;
    var nama_now_1 = document.getElementById('nama_prog_1').value;
    var desc_now_1 = document.getElementById('desc_prog_1').value;
    var det_nya_1 = document.getElementById('det_nya_1').value;

        if(nama_now_1 === ""){
            flag_valid_tambah = false;
            document.getElementById('nama_prog_1').classList.remove("is-valid");
            document.getElementById('nama_prog_1').classList.add("is-invalid");

            document.getElementById('n_nama_1').classList.remove("valid-feedback");
            document.getElementById('n_nama_1').classList.add("invalid-feedback");
            document.getElementById('n_nama_1').innerHTML = "Harap Mengisi Nama Progress!";
        }else{
            // flag_valid_tambah = true;
            document.getElementById('nama_prog_1').classList.add("is-valid");
            document.getElementById('nama_prog_1').classList.remove("is-invalid");

            document.getElementById('n_nama_1').classList.add("valid-feedback");
            document.getElementById('n_nama_1').classList.remove("invalid-feedback");
            document.getElementById('n_nama_1').innerHTML = "";
        }

        if(desc_now_1 === ""){
            flag_valid_tambah = false;
            document.getElementById('desc_prog_1').classList.remove("is-valid");
            document.getElementById('desc_prog_1').classList.add("is-invalid");

            document.getElementById('n_desc_1').classList.remove("valid-feedback");
            document.getElementById('n_desc_1').classList.add("invalid-feedback");
            document.getElementById('n_desc_1').innerHTML = "Harap Mengisi Deskripsi Progress!";
        }else{
            // flag_valid_tambah = true;
            document.getElementById('desc_prog_1').classList.add("is-valid");
            document.getElementById('desc_prog_1').classList.remove("is-invalid");

            document.getElementById('n_desc_1').classList.add("valid-feedback");
            document.getElementById('n_desc_1').classList.remove("invalid-feedback");
            document.getElementById('n_desc_1').innerHTML = "";
        }

    getInput1.push(seq_now_1);
    getInput1.push(nama_now_1);
    getInput1.push(desc_now_1);
    getInput1.push(det_nya_1);
    
    allInputStep.push(getInput1);

    for(var i = 0; i < arr_input.length; i++){
        var getInput = [];
        var urutanIdPass = arr_input[i].split("_");
        var urutanId = urutanIdPass[1];
        var namaSeq = "seq_prog_"+urutanId;
        var namaProg = "nama_prog_"+urutanId;
        var namaDesc = "desc_prog_"+urutanId;
        var namaNnam = "n_nama_"+urutanId;
        var namaNdsc = "n_desc_"+urutanId;
        const det_nya = det_nya_1;
        
        // console.log(namaNnam);

        var seq_now = document.getElementById(namaSeq).value;
        var nama_now = document.getElementById(namaProg).value;
        var desc_now = document.getElementById(namaDesc).value;
        var nnam_now = document.getElementById(namaNnam).value;
        var ndsc_now = document.getElementById(namaNdsc).value;

        if(nama_now === ""){
            document.getElementById(namaProg).classList.remove("is-valid");
            document.getElementById(namaProg).classList.add("is-invalid");

            document.getElementById(namaNnam).classList.remove("valid-feedback");
            document.getElementById(namaNnam).classList.add("invalid-feedback");
            document.getElementById(namaNnam).innerHTML = "Harap Mengisi Nama Progress!";
            flag_valid_tambah = false;
        }else{
            document.getElementById(namaProg).classList.add("is-valid");
            document.getElementById(namaProg).classList.remove("is-invalid");

            document.getElementById(namaNnam).classList.add("valid-feedback");
            document.getElementById(namaNnam).classList.remove("invalid-feedback");
            document.getElementById(namaNnam).innerHTML = "";
            // flag_valid_tambah = true;
        }

        if(desc_now === ""){
            document.getElementById(namaDesc).classList.remove("is-valid");
            document.getElementById(namaDesc).classList.add("is-invalid");

            document.getElementById(namaNdsc).classList.remove("valid-feedback");
            document.getElementById(namaNdsc).classList.add("invalid-feedback");
            document.getElementById(namaNdsc).innerHTML = "Harap Mengisi Deskripsi Progress!";
            flag_valid_tambah = false;
        }else{
            document.getElementById(namaDesc).classList.add("is-valid");
            document.getElementById(namaDesc).classList.remove("is-invalid");

            document.getElementById(namaNdsc).classList.add("valid-feedback");
            document.getElementById(namaNdsc).classList.remove("invalid-feedback");
            document.getElementById(namaNdsc).innerHTML = "";
            // flag_valid_tambah = true;
        }

            // flag_valid_tambah = true;
            getInput.push(seq_now);
            getInput.push(nama_now);
            getInput.push(desc_now);
            getInput.push(det_nya);
            allInputStep.push(getInput);
        
    }
    // console.log(allInputStep);
    
    for(var i = 0; i < allInputStep.length; i++){
        cekSeq = allInputStep[i][1];
        cekNam = allInputStep[i][2];
        cekDes = allInputStep[i][3];
        cekDet = allInputStep[i][4];

        if(cekDes === '' || cekDes === ' '){
            flag_valid_tambah = false;
        }
        if(cekNam === '' || cekNam === ' '){
            flag_valid_tambah = false;
        }
        // console.table(cekSeq+"||"+cekNam+"||"+cekDes+"||"+cekDet);
        // console.log(flag_valid_tambah);
    }


    if(flag_valid_tambah === true){
        Swal.fire({
            title: 'Yakin menyimpan Master Progress',
            showCancelButton: true,
            confirmButtonText: 'Save',
            denyButtonText: `Don't save`,
            }).then((result) => {
            if (result.isConfirmed) {
                console.table(allInputStep);
                getAjax('tambahProgress', allInputStep, function(result){
                    console.log(result);
                    if(result > 0){
                        Swal.fire({
                            icon: 'success',
                            title: 'Progress Berhasil Dibuat',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modal_buat').modal('hide');
                        renderData();
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Progress gagal Dibuat!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
                // Swal.fire('Saved!', '', 'success')
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
              }
        })
    }else{
            Swal.fire({
                        icon: 'warning',
                        title: 'Kolom ada yang Kosong!',
                        showConfirmButton: false,
                        timer: 2000
                    });
    }

}

function openBaru(param){
    // console.log(param);
    $('#modal_buat').modal('show');
    document.getElementById("det_nya_1").value = param;
    document.getElementById("nama_prog_1").classList.remove("is-valid");
    document.getElementById("nama_prog_1").classList.remove("is-invalid");
    document.getElementById("n_nama_1").classList.remove("valid-feedback");
    document.getElementById("n_nama_1").classList.remove("invalid-feedback");
    document.getElementById("n_nama_1").innerHTML = "";
    document.getElementById("desc_prog_1").classList.remove("is-valid");
    document.getElementById("desc_prog_1").classList.remove("is-invalid");
    document.getElementById("n_desc_1").classList.remove("valid-feedback");
    document.getElementById("n_desc_1").classList.remove("invalid-feedback");
    document.getElementById("n_desc_1").innerHTML = "";
    // console.log(arr_input.length + arr_input);
    if( 0 < arr_input.length ){
        for(var i = 0; i < arr_input.length; i+1){
            delFields(arr_input[i]);
        }
    }
}

function openAdd(){
    document.getElementById('jenis_doku').value = "";
    document.getElementById('tipe_doku').value = "";
    document.getElementById('tipe_doku').disabled = true;
    document.getElementById('nama_doku').disabled = true;
    document.getElementById('nama_doku').value = "";
    document.getElementById('dur_doku').disabled = true;
    document.getElementById('dur_doku').value = "";
    document.getElementById("jenis_doku").classList.remove("is-valid");
    document.getElementById("jenis_doku").classList.remove("is-invalid");
    document.getElementById("notif_jenis_doku").classList.remove("valid-feedback");
    document.getElementById("notif_jenis_doku").classList.remove("invalid-feedback");
    document.getElementById("notif_jenis_doku").innerHTML = "";
    document.getElementById("tipe_doku").classList.remove("is-valid");
    document.getElementById("tipe_doku").classList.remove("is-invalid");
    document.getElementById("notif_tipe_doku").classList.remove("valid-feedback");
    document.getElementById("notif_tipe_doku").classList.remove("invalid-feedback");
    document.getElementById("notif_tipe_doku").innerHTML = "";
    document.getElementById("nama_doku").classList.remove("is-valid");
    document.getElementById("nama_doku").classList.remove("is-invalid");
    document.getElementById("notif_nama_doku").classList.remove("valid-feedback");
    document.getElementById("notif_nama_doku").classList.remove("invalid-feedback");
    document.getElementById("notif_nama_doku").innerHTML = "";
    document.getElementById("dur_doku").classList.remove("is-valid");
    document.getElementById("dur_doku").classList.remove("is-invalid");
    document.getElementById("notif_dur_doku").classList.remove("valid-feedback");
    document.getElementById("notif_dur_doku").classList.remove("invalid-feedback");
    document.getElementById("notif_dur_doku").innerHTML = "";
    $('#modal_add').modal('show');
}

function tambahDokumen(){
    var jenis_doku = document.getElementById("jenis_doku").value;
    var tipe_doku = document.getElementById("tipe_doku").value;
    var nama_doku = document.getElementById("nama_doku").value;
    var doku_single = document.getElementById("doku_single").value;
    var dur_doku = document.getElementById("dur_doku").value;
    var flag_valid_tambah = true;
    
    if(jenis_doku === ""){
        flag_valid_tambah = false;
        document.getElementById("jenis_doku").classList.remove("is-valid");
        document.getElementById("jenis_doku").classList.add("is-invalid");

        document.getElementById("notif_jenis_doku").classList.remove("valid-feedback");
        document.getElementById("notif_jenis_doku").classList.add("invalid-feedback");
        document.getElementById("notif_jenis_doku").innerHTML = "Harap Memilih Jenis Dokumen";
    }else{
        document.getElementById("jenis_doku").classList.add("is-valid");
        document.getElementById("jenis_doku").classList.remove("is-invalid");

        document.getElementById("notif_jenis_doku").classList.add("valid-feedback");
        document.getElementById("notif_jenis_doku").classList.remove("invalid-feedback");
        document.getElementById("notif_jenis_doku").innerHTML = "";
    }

    if(tipe_doku === ""){
        flag_valid_tambah = false;
        document.getElementById("tipe_doku").classList.remove("is-valid");
        document.getElementById("tipe_doku").classList.add("is-invalid");

        document.getElementById("notif_tipe_doku").classList.remove("valid-feedback");
        document.getElementById("notif_tipe_doku").classList.add("invalid-feedback");
        document.getElementById("notif_tipe_doku").innerHTML = "Harap Memilih Tipe Dokumen";
    }else{
        document.getElementById("tipe_doku").classList.add("is-valid");
        document.getElementById("tipe_doku").classList.remove("is-invalid");

        document.getElementById("notif_tipe_doku").classList.add("valid-feedback");
        document.getElementById("notif_tipe_doku").classList.remove("invalid-feedback");
        document.getElementById("notif_tipe_doku").innerHTML = "";
    }

    if(nama_doku === ""){
        console.log("a");
        flag_valid_tambah = false;
        document.getElementById("nama_doku").classList.remove("is-valid");
        document.getElementById("nama_doku").classList.add("is-invalid");
        
        document.getElementById("notif_nama_doku").classList.remove("valid-feedback");
        document.getElementById("notif_nama_doku").classList.add("invalid-feedback");
        document.getElementById("notif_nama_doku").innerHTML = "Harap mengisi Master Judul Dokumen";
    }else{
        document.getElementById("nama_doku").classList.add("is-valid");
        document.getElementById("nama_doku").classList.remove("is-invalid");

        document.getElementById("notif_nama_doku").classList.add("valid-feedback");
        document.getElementById("notif_nama_doku").classList.remove("invalid-feedback");
        document.getElementById("notif_nama_doku").innerHTML = "";
    }

    if(doku_single === ""){
        flag_valid_tambah = false;
        document.getElementById("doku_single").classList.remove("is-valid");
        document.getElementById("doku_single").classList.add("is-invalid");

        document.getElementById("notif_doku_single").classList.remove("valid-feedback");
        document.getElementById("notif_doku_single").classList.add("invalid-feedback");
        document.getElementById("notif_doku_single").innerHTML = "Harap Memilih Antara Iya Atau Tidak";
    }else{
        document.getElementById("doku_single").classList.add("is-valid");
        document.getElementById("doku_single").classList.remove("is-invalid");

        document.getElementById("notif_doku_single").classList.add("valid-feedback");
        document.getElementById("notif_doku_single").classList.remove("invalid-feedback");
        document.getElementById("notif_doku_single").innerHTML = "";
    }

    if(dur_doku === "" && doku_single === 'y'){
        flag_valid_tambah = false;
        document.getElementById("dur_doku").classList.remove("is-valid");
        document.getElementById("dur_doku").classList.add("is-invalid");

        document.getElementById("notif_dur_doku").classList.remove("valid-feedback");
        document.getElementById("notif_dur_doku").classList.add("invalid-feedback");
        document.getElementById("notif_dur_doku").innerHTML = "Harap mengisi durasi Dokumen";
    }else if(dur_doku <= 2 && doku_single === 'y'){
        flag_valid_tambah = false;
        document.getElementById("dur_doku").classList.remove("is-valid");
        document.getElementById("dur_doku").classList.add("is-invalid");

        document.getElementById("notif_dur_doku").classList.remove("valid-feedback");
        document.getElementById("notif_dur_doku").classList.add("invalid-feedback");
        document.getElementById("notif_dur_doku").innerHTML = "Durasi Minimum adalah 3 Bulan!";
    }else{
        document.getElementById("dur_doku").classList.add("is-valid");
        document.getElementById("dur_doku").classList.remove("is-invalid");

        document.getElementById("notif_dur_doku").classList.add("valid-feedback");
        document.getElementById("notif_dur_doku").classList.remove("invalid-feedback");
        document.getElementById("notif_dur_doku").innerHTML = "";
    }

    if(flag_valid_tambah === true){
            var send_param = [jenis_doku, tipe_doku, nama_doku, dur_doku];
            // console.log(send_param);
            getAjax('tambahMasDoku', send_param, function(result){
                // console.log(result);
                if(result > 0){
                    Swal.fire({
                        icon: 'success',
                        title: 'Dokumen Berhasil Tertambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#modal_add').modal('hide');
                    renderData();
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Ada Data yang Ditambahkan!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
    }
}

function ubahMaster(){
    var idnyaeditmd = document.getElementById("idnyaeditmd").value;
    var idnyajenis  = document.getElementById("idnyajenis").value;
    var idnyatipe   = document.getElementById("idnyatipe").value;
    var idnyadept   = document.getElementById("idnyadept").value;
    var nama_doku   = document.getElementById("nama_doku_e").value;
    var dur_doku    = document.getElementById("dur_doku_e").value;
    var flag_valid_ubah = true;
    var flag_pasti_ubah = true;
    
    if(nama_doku === "" || nama_doku === " "){
        // flag_valid_ubah = false;
        document.getElementById("nama_doku_e").classList.remove("is-valid");
        document.getElementById("nama_doku_e").classList.add("is-invalid");

        document.getElementById("notif_nama_doku_e").classList.remove("valid-feedback");
        document.getElementById("notif_nama_doku_e").classList.add("invalid-feedback");
        document.getElementById("notif_nama_doku_e").innerHTML = "Harap mengisi Master Judul Dokumen";
    }else{
        document.getElementById("nama_doku_e").classList.add("is-valid");
        document.getElementById("nama_doku_e").classList.remove("is-invalid");

        document.getElementById("notif_nama_doku_e").classList.add("valid-feedback");
        document.getElementById("notif_nama_doku_e").classList.remove("invalid-feedback");
        document.getElementById("notif_nama_doku_e").innerHTML = "";
    }

    if(dur_doku === ""){
        // flag_valid_ubah = false;
        document.getElementById("dur_doku_e").classList.remove("is-valid");
        document.getElementById("dur_doku_e").classList.add("is-invalid");

        document.getElementById("notif_dur_doku_e").classList.remove("valid-feedback");
        document.getElementById("notif_dur_doku_e").classList.add("invalid-feedback");
        document.getElementById("notif_dur_doku_e").innerHTML = "Harap mengisi durasi Dokumen";
    }else if(dur_doku <= 2){
        // flag_valid_ubah = false;
        document.getElementById("dur_doku_e").classList.remove("is-valid");
        document.getElementById("dur_doku_e").classList.add("is-invalid");

        document.getElementById("notif_dur_doku_e").classList.remove("valid-feedback");
        document.getElementById("notif_dur_doku_e").classList.add("invalid-feedback");
        document.getElementById("notif_dur_doku_e").innerHTML = "Durasi Minimum adalah 3 Bulan!";
    }else{
        document.getElementById("dur_doku_e").classList.add("is-valid");
        document.getElementById("dur_doku_e").classList.remove("is-invalid");

        document.getElementById("notif_dur_doku_e").classList.add("valid-feedback");
        document.getElementById("notif_dur_doku_e").classList.remove("invalid-feedback");
        document.getElementById("notif_dur_doku_e").innerHTML = "";
    }

    if(nama_doku === "" || dur_doku === "" || idnyaeditmd === "" || nama_doku === " "){
        flag_valid_ubah = false;
    }else{
        var send_param = [nama_doku, dur_doku, idnyaeditmd, idnyajenis, idnyatipe, idnyadept];
        console.log(send_param);
        getAjax('getMasDokuCek', send_param, function(result){
            // console.log(result);
            if(result[0].hasil === "1"){
                flag_pasti_ubah = false;
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada perubahan data.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                let timerInterval

                const swalWithBootstrapButtons = Swal.mixin({
                    // customClass: {
                    //     confirmButton: 'btn btn-success',
                    //     cancelButton: 'btn btn-danger'
                    // },
                    buttonsStyling: true
                })
                swalWithBootstrapButtons.fire({
                    title: 'Konfirmasi Update Master Dokumen',
                    text: "Anda akan melakukan pengubahan MASTER DOKUMEN mohon untuk berhati-hati!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '  &nbsp; Iya &nbsp;',
                    cancelButtonText: ' &nbsp; Tidak &nbsp;',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        getAjax('ubahMasDoku', send_param, function(result){
                            console.log(result);
                            if(result > 0){
                                swalWithBootstrapButtons.fire({
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'success',
                                title: 'Dokumen Berhasil Diubah',
                                html: 'Melakukan Refresh data dalam waktu <b></b> milliseconds.',
                                didOpen: () => {
                                Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },willClose: () => {
                                    clearInterval(timerInterval)
                                }
                                }).then((result) => {
                                /* Read more about handling dismissals below */
                                renderData();
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    $('#modal_edit_master').modal('hide');
                                    // $('#modal_buat').modal('show');
                                }
                                // showConfirmButton: false
                                });   
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Terjadi Error! silahkan refresh kembali',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });

                    } else {
                        swalWithBootstrapButtons.fire(
                        'Dibatalkan',
                        'Batal menyimpan data',
                        'error'
                        )
                    }
                });
            }
            // console.log("~~~~"+flag_pasti_ubah);
        });
    }
}

function openEdit(param){
    // console.log(param);
    $('#modal_edit_master').modal('hide');
    $('#modal_editpro').modal('hide');
    $('#modal_edit').modal('show');
    document.getElementById("idnyaedit").value = param;
}

function editMasterOpen(){
    // console.log(param);idnyaeditmd
    param = document.getElementById("idnyaedit").value;
    document.getElementById("idnyaeditmd").value = param;
    
    getAjax('getEdit', param, function(result){
        document.getElementById("jenis_doku_e").value = result[0].jenis;
        document.getElementById("tipe_doku_e").value = result[0].tipe;
        document.getElementById("nama_doku_e").value = result[0].judul;
        document.getElementById("dur_doku_e").value = result[0].durasi;
        document.getElementById("idnyajenis").value = result[0].iddoku;
        document.getElementById("idnyatipe").value = result[0].idtipe;
        document.getElementById("idnyadept").value = result[0].iddept;
    });

    $('#modal_edit').modal('hide');
    $('#modal_edit_master').modal('show');
}

function renderData(){
    var level = document.getElementById('level_access').value;
    getAjax('getRender', level, function(result){
        console.log(result);
        document.getElementById('table_content').innerHTML = "";

            var nomor = 1;
            var table_content = "";

            for(var i = 0; i < result.length; i++){
                table_content += "<tr>";

                table_content += "<th>" + nomor + "</th>";
                table_content += "<td>" + result[i].judul + "</td>";
                table_content += "<td>" + result[i].jenis + "</td>";
                table_content += "<td>" + result[i].tipe + "</td>";
                table_content += "<td>" + result[i].durasi + "</td>";

                if(level >= 3){
                    table_content += "<td>";
                    
                    table_content += "<button type='submit' class='btn btn-primary mr-1' onclick='openUpdate(" + result[i].id + ")'><i class='fas fa-clipboard-check fa-lg'></i></button>";

                    if(result[i].progress == 1){
                        table_content += "<button type='submit' class='btn btn-warning' onclick='openEdit(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }else{
                        table_content += "<button type='submit' class='btn btn-success' onclick='openBaru(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }             

                    table_content += "</td>";
                }

                nomor++;
            }

            document.getElementById('table_content').innerHTML = table_content;
    });
}

function openUpdate(param){
    $('#modal_update').modal('show');

    var param_pass = [];
    param_pass.push(param);

    getAjax('getTitleDoku', param_pass, function(result){
        // console.log(result);
        document.getElementById("title_doku").innerText = "";
        document.getElementById("title_doku").innerText = result;
    });

    getAjax('getDataLog', param_pass, function(result){
        // console.log(result);
        document.getElementById("accordion_log").innerHTML = "";
        document.getElementById("accordion_log").innerHTML = result;
    });

}

function editProgress(){
    param = document.getElementById("idnyaedit").value;
    document.getElementById("idnyaeditmp").value = param;

    var param_pass = [];
    param_pass.push(param);

    getAjax('getDataProg', param_pass, function(result){
        // console.log(result);
        document.getElementById("progressrowedit").innerHTML = "";
        document.getElementById("progressrowedit").innerHTML = result;
    });

    $('#modal_edit').modal('hide');
    $('#modal_editpro').modal('show');

}

function simpanProgress(){
    var maxseq =  document.getElementById("maxseq").value;
    var maxid = document.getElementById("idnyaeditmp").value;

    var flag_valid_ubah = true;
    var allInputStepEdit = [];

    for(var i = 0; i < maxseq; i++){
        var array_result = [];
        var namaSeq = "seq_prog_ed_"+(i+1);
        var namaProg = "nama_prog_ed_"+(i+1);
        var namaDesc = "desc_prog_ed_"+(i+1);
        var namaNnam = "n_nama_ed_"+(i+1);
        var namaNdsc = "n_desc_ed_"+(i+1);
        const det_nya_edit = maxid;
        
        var now_seq  = document.getElementById(namaSeq).value;
        var now_nama = document.getElementById(namaProg).value;
        var now_desc = document.getElementById(namaDesc).value;
        var now_nnam = document.getElementById(namaNnam).value;
        var now_ndsc = document.getElementById(namaNdsc).value;

        if(now_nama === "" || !/\S/.test(now_nama)){
            document.getElementById(namaProg).classList.remove("is-valid");
            document.getElementById(namaProg).classList.add("is-invalid");

            document.getElementById(namaNnam).classList.remove("valid-feedback");
            document.getElementById(namaNnam).classList.add("invalid-feedback");
            document.getElementById(namaNnam).innerHTML = "Harap Mengisi Nama Progress!";
            flag_valid_ubah = false;
        }else{
            document.getElementById(namaProg).classList.add("is-valid");
            document.getElementById(namaProg).classList.remove("is-invalid");

            document.getElementById(namaNnam).classList.add("valid-feedback");
            document.getElementById(namaNnam).classList.remove("invalid-feedback");
            document.getElementById(namaNnam).innerHTML = "";
        }

        if(now_desc === "" || !/\S/.test(now_desc)){
            document.getElementById(namaDesc).classList.remove("is-valid");
            document.getElementById(namaDesc).classList.add("is-invalid");

            document.getElementById(namaNdsc).classList.remove("valid-feedback");
            document.getElementById(namaNdsc).classList.add("invalid-feedback");
            document.getElementById(namaNdsc).innerHTML = "Harap Mengisi Deskripsi Progress!";
            flag_valid_ubah = false;
        }else{
            document.getElementById(namaDesc).classList.add("is-valid");
            document.getElementById(namaDesc).classList.remove("is-invalid");

            document.getElementById(namaNdsc).classList.add("valid-feedback");
            document.getElementById(namaNdsc).classList.remove("invalid-feedback");
            document.getElementById(namaNdsc).innerHTML = "";
        }

        array_result.push(now_seq);
        array_result.push(now_nama);
        array_result.push(now_desc);
        array_result.push(det_nya_edit);
        allInputStepEdit.push(array_result);

    }

    // console.table(allInputStepEdit);
    
    for(var i = 0; i < allInputStepEdit.length; i++){
        cekSeq = allInputStepEdit[i][1];
        cekNam = allInputStepEdit[i][2];
        cekDes = allInputStepEdit[i][3];
        cekDet = allInputStepEdit[i][4];

        if(cekDes === '' || !/\S/.test(cekDes)){
            flag_valid_ubah = false;
        }
        if(cekNam === '' || !/\S/.test(cekNam)){
            flag_valid_ubah = false;
        }
    }

    if(flag_valid_ubah === true){
        Swal.fire({
            title: 'Yakin simpan perubahan Master Progress',
            showCancelButton: true,
            confirmButtonText: 'Save',
            denyButtonText: `Don't save`,
            }).then((resultUbah) => {
            if (resultUbah.isConfirmed) {
                // console.table(allInputStepEdit);
                getAjax('ubahProgress', allInputStepEdit, function(resultUbah){
                    // console.log(resultUbah);
                    if(resultUbah > 0){
                        Swal.fire({
                            icon: 'success',
                            title: 'Progress Berhasil Diubah',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modal_editpro').modal('hide');
                        renderData();
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Progress gagal Diubah',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
                Swal.fire('Saved!', '', 'success')
            } else if (resultUbah.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
              }
        })
    }else{
            Swal.fire({
                        icon: 'warning',
                        title: 'Kolom ada yang Kosong!',
                        showConfirmButton: false,
                        timer: 2000
                    });
    }
}

function openParent(param){
    $('#modal_info_master').modal('show');

    var param_pass = [];
    param_pass.push(param);

    getAjax('getTitleInfoMaster', param_pass, function(result){
        // console.log(result);
        document.getElementById("title_info_master").innerText = "";
        document.getElementById("title_info_master").innerText = "Data Parent Dokumen - "+result;
    });

    getAjax('getDataLogInfoMaster', param_pass, function(result){
        // console.log(result);
        document.getElementById("masterinfo_log").innerHTML = "";
        document.getElementById("masterinfo_log").innerHTML = result;
    });

}

function getTipeValid(){
    var tipeDoku = document.getElementById('tipe_doku').value;
    if(tipeDoku == ""){
        document.getElementById('nama_doku').disabled = true;
        document.getElementById('nama_doku').value = "";
        document.getElementById('doku_single').disabled = true;
        document.getElementById('doku_single').value = "";
        document.getElementById('dur_doku').disabled = true;
        document.getElementById('dur_doku').value = "";
    }else{
        document.getElementById('nama_doku').disabled = false;
        document.getElementById('doku_single').disabled = false;
    }
}

function getDokuSingle(){
    var dokuSingle = document.getElementById('doku_single').value;
    if(dokuSingle == ""){
        document.getElementById('dur_doku').disabled = true;
        document.getElementById('dur_doku').value = "";
    }else{
        if(dokuSingle === "y"){
            document.getElementById('dur_doku').disabled = false;
        }else{
            document.getElementById('dur_doku').disabled = true;
        document.getElementById('dur_doku').value = "";
        }
    }
}

function getTipeDoku(){
    var jenisDoku = document.getElementById('jenis_doku').value;

    if(jenisDoku == ""){
        document.getElementById('tipe_doku').disabled = true;
        document.getElementById('tipe_doku').value = "";
        document.getElementById('nama_doku').disabled = true;
        document.getElementById('nama_doku').value = "";
        document.getElementById('dur_doku').disabled = true;
        document.getElementById('dur_doku').value = "";
    }else{
        document.getElementById('tipe_doku').disabled = false;
        
        $.ajax({
            url: '../cpar/ArsipDataMaster_new/getTipeDoku',
            type: 'POST',
            dataType: 'json',
            data:   {
                id_jenis:jenisDoku
            },
            success: function(data){
                var value_tipeDokumen = "<option value=''></option>";

                if(data.length > 0){
                    document.getElementById('tipe_doku').innerHTML = "";
                    
                    for(var i = 0; i < data.length; i++){
                        value_tipeDokumen += "<option value=" + data[i].id_type + ">";
                        value_tipeDokumen += data[i].tipe;
                        value_tipeDokumen += "</option>";
                    }

                    document.getElementById('tipe_doku').innerHTML = value_tipeDokumen;
                }else{
                    document.getElementById('tipe_doku').innerHTML = value_tipeDokumen;
                    document.getElementById('tipe_doku').disabled = true;
                    document.getElementById('tipe_doku').value = "";
                }
            },
            error: function() {
                alert("error getTipeDoku");
            }
        });
    }
}

function filterJudul(){
    var id_judul = document.getElementById('filter_judul').value;
    var ket = document.getElementById('filter_ket2').value;
    var level = document.getElementById('level_access').value;

    // console.log(id_judul);
    // console.log(ket);
    
    document.getElementById('cancel_filter_judul').disabled = false;
    document.getElementById('cancel_filter_judul2').disabled = false;
    
    $.ajax({
        url: '../cpar/ArsipDataMaster_new/getDataFilterJudul',
        type: 'POST',
        dataType: 'json',
        data:   {
            id_judul:id_judul,
            ket:ket
        },
        success: function(data){
            console.log(data);
            document.getElementById('table_content').innerHTML = "";

            var nomor = 1;
            var table_content = "";

            for(var i = 0; i < data.length; i++){
                table_content += "<tr>";

                table_content += "<th>" + nomor + "</th>";
                table_content += "<td>" + data[i].judul + "</td>";
                table_content += "<td>" + data[i].jenis + "</td>";
                table_content += "<td>" + data[i].tipe + "</td>";
                table_content += "<td>" + data[i].durasi + "</td>";

                if(level === '3'){
                    table_content += "<td>";
                    
                    table_content += "<button type='submit' class='btn btn-primary mr-1' onclick='openUpdate(" + data[i].id + ")'><i class='fas fa-clipboard-check fa-lg'></i></button>";
                    
                    if(result[i].progress == 1){
                        table_content += "<button type='submit' class='btn btn-warning' onclick='openEdit(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }else{
                        table_content += "<button type='submit' class='btn btn-success' onclick='openBaru(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }             
                    
                    table_content += "</td>";
                }

                nomor++;
            }

            document.getElementById('table_content').innerHTML = table_content;

        },
        error: function() {
            alert("error getDataFilterJudul - Master");
        }
    });
}

function cancelFilter(){
    var id_judul = '0';
    document.getElementById('filter_judul').value = "";
    document.getElementById('filter_ket2').value = "";
    var level = document.getElementById('level_access').value;
    
    document.getElementById('cancel_filter_judul').disabled = true;
    document.getElementById('cancel_filter_judul2').disabled = true;
    
    $.ajax({
        url: '../cpar/ArsipDataMaster_new/getDataFilterJudul',
        type: 'POST',
        dataType: 'json',
        data:   {
            id_judul:id_judul
        },
        success: function(data){
            // console.log(data);
            document.getElementById('table_content').innerHTML = "";

            var nomor = 1;
            var table_content = "";

            for(var i = 0; i < data.length; i++){
                table_content += "<tr>";

                table_content += "<th>" + nomor + "</th>";
                table_content += "<td>" + data[i].judul + "</td>";
                table_content += "<td>" + data[i].jenis + "</td>";
                table_content += "<td>" + data[i].tipe + "</td>";
                table_content += "<td>" + data[i].durasi + "</td>";

                if(level === '3'){
                    table_content += "<td>";
                    
                    table_content += "<button type='submit' class='btn btn-primary mr-1' onclick='openUpdate(" + data[i].id + ")'><i class='fas fa-clipboard-check fa-lg'></i></button>";
                    
                    if(result[i].progress == 1){
                        table_content += "<button type='submit' class='btn btn-warning' onclick='openEdit(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }else{
                        table_content += "<button type='submit' class='btn btn-success' onclick='openBaru(" + result[i].id + ")'><i class='fas fa-wrench'></i></button>";
                    }             
                    
                    table_content += "</td>";
                }

                nomor++;
            }

            document.getElementById('table_content').innerHTML = table_content;

        },
        error: function() {
            alert("error getDataFilterJudul");
        }
    });
}

function getAjax(url, param, callback){
    $.ajax({
        url: '../cpar/ArsipDataMaster_new/' + url,
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
            alert("error getAjax");
        }
    });
}