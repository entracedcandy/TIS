<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Permit</title>
    
    <style>
        @page {
            margin: 50px 50px;
            @top-center {
                content: element(header);
            }
        }

        footer {
            position: fixed;
            bottom: -30px; 
            left: 0px; 
            right: 0px;
        }

        #header {
            position: running(header);
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            /* height: 100vh; */
            /* margin: 0; */
            font-size: 11pt;
        }

        .form-group {
            margin-bottom: 1rem; /* Margin antar form-group */
        }

        label {
            display: block; /* Membuat label menjadi blok agar memiliki margin atas dan bawah */
            margin-bottom: 0.5rem; /* Margin bawah untuk label */
        }

        input.form-control {
            width: 100%; /* Lebar input 100% */
            padding: 0.375rem 0.75rem; /* Padding input */
            font-size: 1rem; /* Ukuran font input */
            line-height: 1.5; /* Tinggi baris input */
            color: #495057; /* Warna teks input */
            background-color: #fff; /* Warna latar belakang input */
            background-clip: padding-box; /* Penyebaran latar belakang input */
            border: 1px solid #ced4da; /* Warna border input */
            border-radius: 0.25rem; /* Sudut border input */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; /* Transisi saat hover input */
        }

        input.form-control::placeholder {
            color: #6c757d; /* Warna placeholder input */
            opacity: 1; /* Opasitas placeholder input */
        }

        input.form-control:focus {
            color: #495057; /* Warna teks input saat fokus */
            background-color: #fff; /* Warna latar belakang input saat fokus */
            border-color: #80bdff; /* Warna border input saat fokus */
            outline: 0; /* Hapus outline saat fokus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Shadow saat fokus */
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
        }
        
        .col-3 {
            flex: 0 0 calc(33.33% - 1rem); /* Lebar 33.33% dengan jarak margin 1rem */
            margin-right: 1rem; /* Margin kanan */
        }

        .biru{
            background-color: #3282F6;
        }
        
        .label-border {
            /* border: 1px solid black; */
            padding: 5px;
        }  

        .container {
            /* border: 1px solid black; */
            /* padding: 20px; */
            padding-top: 50px;
            padding-bottom: 50px;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        .form-group {
            margin-bottom: 0.5rem;
        }
        
        label {
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .worker {
            margin-right: 20px;
        }
        
        .worker label {
            display: block;
            font-weight: bold;
        }
        
        .worker section {
            margin: 5px 0;
        }
        
        .next{
            display: flex;
        }
        
        .info {
            display: flex;
            flex-direction: column;
        }
        
        .next1 {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .form-section, .work-permit-section {
            display: flex;
            align-items: center;
        }
        
        .form-section h3, .work-permit-section h3 {
            margin-right: 20px;
        }
        
        .label-border {
            font-weight: bold;
        }
        
        .form-table {
            width: 100%;
            border-collapse: collapse;
            /* margin: auto; */
        }
        
        .form-table th, .form-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            margin: auto;
        }
        
        .form-table th {
            background-color: #f2f2f2;
        }

        .form-table-header {
            width: 100%;
            border-collapse: collapse;
            /* margin: auto; */
        }
        
        .form-table-header th, .form-table-header td {
            border: 1px solid black;
            padding: 0;
            text-align: left;
            margin: 0;
            line-height: 1px;
        }
        
        .form-table-header th {
            background-color: #f2f2f2;
        }
        
        .form-table1 {
            width: 80%;
            border-collapse: collapse;
            margin: auto;
        }
        
        .form-table1 th, .form-table1 td {
            border: 12px solid #C22C1B;
            color: #C22C1B;
            font-weight: 900;
            padding: 0px;
            padding: 0px 0px 0px 8px;
            text-align: left;
            margin: auto;
        }
        
        .form-table1 th {
            background-color: #f2f2f2;
        }
        
        .table-hidden{
            width: 100%;
        }
        
        .table-hidden th, .table-hidden td{
            border-style: none;
            padding:0px;
            margin: 0px;
        }
        
        .table-red{
            width: 100%;
            /* margin: auto; */
        }
        
        .table-red td{
            border: 12px solid #C22C1B;
            color: #C22C1B;
            font-weight: bolder;
            margin: auto;
            padding: 0px;
            padding: 0px 0px 0px 8px;
        }
        
        .div-red{
            background-color:#E2BAC0;
            color:#350200;
            font-family: 'Source Sans Pro', sans-serif;
            font-size:20px;
            /* padding:6px; */
            /* margin-top:4px; */
            border-radius:5px;
            border: 1px solid #67474C;
            /* word-spacing:10px; */
            width: 207px;
        }
        
        .div-red1{
            background-color:#E2BAC0;
            color:#350200;
            font-family: 'Source Sans Pro', sans-serif;
            font-size:20px;
            /* padding:6px; */
            /* margin-top:4px; */
            border-radius:5px;
            border: 1px solid #67474C;
            /* word-spacing:10px; */
            width: 120px;
        }
        
        .abu-abu{
            background-color: #CFCECA;
            padding: 8px 0px 8px 8px;
        }

        .garis {
            text-decoration: line-through;
        }

        .bg-blue{
            background-color: #3282F6;
        }

        .font-header{
            font-size: 12px;
        }

        .clear-side{
            margin: 0;
            padding: 0;
        }

        .jawaban-text{
            text-decoration: underline;
        }

        .list-item-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        
    </style>
</head>
<body>
    <footer>
        <p>Dokumen ini telah disetujui secara elektronik dengan menggunakan stempel digital.</p>
    </footer>
    <div class="container">
        <div class="header-row">
            <div class="info">
                <table class="form-table-header">
                    <tr>
                        <td rowspan="4" style="text-align:center; width:15%;">
                            <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                        </td>
                        <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                        <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                        <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?= $no_dokumen ?></a> &nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                        <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $berlaku ?></a> &nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                        <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                        <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $edisi ?> / <?= $revisi ?></a> &nbsp;&nbsp;</td>
                    </tr>
                    <tr> 
                        <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                        <td class="font-header clear-side">&nbsp;&nbsp;<a class="">1 dari 5</a> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <div class="biru">
                <h4 style="text-align: center; font-size: larger;" class="label-border">IJIN KERJA</h4>
            </div>
            <table class="table-hidden">
                <b>A. Jenis Ijin Kerja</b>
                <?php 
                    $jawaban_30 = explode(",",$answer['ip_30']);

                    for($i = 1; $i <= 6; $i++){
                        $ik[$i] = false;

                        foreach($jawaban_30 as $jwb){
                            if($i === (int)$jwb){
                                $ik[$i] = true;
                            }
                        }
                    }
                ?>
                <tr>
                    <td><input type="checkbox" <?php echo ($ik[1]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Ijin Kerja Panas</td>
                    <td><input type="checkbox" <?php echo ($ik[2]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Ijin Kerja di Ruang Tertutup</td>
                    <td><input type="checkbox" <?php echo ($ik[3]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Ijin Kerja di Ketinggian</td>
                </tr>
                <tr>
                    <td><input type="checkbox" <?php echo ($ik[4]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Ijin Kerja Listrik</td>
                    <td><input type="checkbox" <?php echo ($ik[5]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Penggalian (excavasi)</td>
                    <td><input type="checkbox" <?php echo ($ik[6]) ? "checked='true'" : ""; ?> style="vertical-align: text-bottom;"> Lain - lain (Jelaskan)</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><p><?= $answer['ip_sub_30'] ?></p></td>
                </tr>
            </table>
        </div>
        <br>
        <div>
            <b>B. Informasi Umum</b>
            <table class="form-table" style="width: 100%; margin-top:10px;">
                <tr>
                    <td>Tanggal Pengerjaan : <a class="jawaban-text"><?= $answer['ip_31'] ?></a></td>
                    <!-- <td>No Ijin : <a class="jawaban-text"><?php // echo $no_ijin ?></a></td> -->
                    <td>No Ijin : <a class="jawaban-text"><?= $answer['ip_46'] ?></a></td>
                </tr>
                <tr>
                    <td colspan="2">Waktu Pengerjaan : &nbsp;&nbsp;<a class="jawaban-text"><?= $answer['ip_32'] ?></a>&nbsp;&nbsp;&nbsp;Sampai :&nbsp;&nbsp;&nbsp;<a class="jawaban-text"><?= $answer['ip_33'] ?></a></td>
                </tr>
                <tr>
                    <td colspan="2">Pemohon Ijin : &nbsp;&nbsp;<a class="jawaban-text"><?= $pemohon->caption ?></a></td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            <tr>
                                <td style="width: 95%;">Kontraktor/Outsource</td>
                                <td><input type="checkbox" <?php echo ($answer['ip_34'] == "Kontraktor / Outsource") ? "checked='true'" : ""; ?>></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="table-hidden">
                            <tr>
                                <td style="width: 95%;">Karyawan</td>
                                <td><input type="checkbox" <?php echo ($answer['ip_34'] == "Karyawan") ? "checked='true'" : ""; ?>></td>
                            </tr>
                        </table>                   
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">Nama PT/CV :&nbsp;&nbsp;<a class="jawaban-text"><?php echo ($answer['ip_34'] == "Kontraktor / Outsource") ? $pemohon->vendor : ""; ?></a> &nbsp;&nbsp;</td>
                    <td style="width:50%;">Department :&nbsp;&nbsp;<a class="jawaban-text"><?php echo ($answer['ip_34'] == "Karyawan") ? $pemohon->department : ""; ?></a> &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        <a>Nama Pekerja</a>
                        <ol style="margin:0; padding-left:20px;">
                            <?php
                                if($answer['ip_34'] == "Kontraktor / Outsource"){
                                    $break = explode("|", $answer['ip_35']);
                                    foreach($break as $b){
                                        echo "<li>". $b ."</li>";
                                    }
                                }else{
                                    echo "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
                                }
                            ?>
                        </ol>
                    </td>
                    <td style="width:50%;">
                        <a>Nama Pekerja</a>
                        <ol style="margin:0; padding-left:20px;">
                            <?php
                                if($answer['ip_34'] == "Karyawan"){
                                    $break = explode("|", $answer['ip_35']);
                                    foreach($break as $b){
                                        echo "<li>". $b ."</li>";
                                    }
                                }else{
                                    echo "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
                                }
                            ?>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">Mandor/Pengawas :&nbsp;&nbsp;<a class="jawaban-text"><?php echo ($answer['ip_34'] == "Kontraktor / Outsource") ? $answer['ip_36'] : ""; ?></a> &nbsp;&nbsp;</td>
                    <td style="width:50%;">Supervisor :&nbsp;&nbsp;<a class="jawaban-text"><?php echo ($answer['ip_34'] == "Karyawan") ? $answer['ip_36'] : ""; ?></a> &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">Lokasi Kerja :&nbsp;&nbsp;<a class="jawaban-text"><?= $answer['ip_37'] ?></a> &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="table-hidden">
                            <tr>
                                <td style="width: 25%;">Deskripsi Kerja :</td>
                                <td>&nbsp;&nbsp;<a class="jawaban-text"><?= $answer['ip_38'] ?></a> &nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Ketinggian :</td>
                                <td>&nbsp;&nbsp;<a class="jawaban-text"><?= $answer['ip_39'] ?></a> &nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td>No Work Order :</td>
                                <td style="width: 84%;">&nbsp;&nbsp;<a class="jawaban-text"><?php // echo $answer['ip_51'] ?></a> &nbsp;&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="header-row" style="page-break-before: always;">
                <div class="info">
                    <table class="form-table-header">
                        <tr>
                            <td rowspan="4" style="text-align:center; width:15%;">
                                <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                            </td>
                            <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                            <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                            <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?= $no_dokumen ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $berlaku ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                            <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $edisi ?> / <?= $revisi ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr> 
                            <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class="">2 dari 5</a> &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div> 
            <table class="form-table" style="width: 100%; margin-top:10px;">
                <tr>
                    <td colspan="2">
                        <a>Penilaian Tingkat Resiko :</a>
                        <table class="form-table" style="margin-top:5px;">
                            <tr>
                                <td style="width:50%; padding:0;">
                                    <table style="border:0; width:100%; border-collapse:collapse;">
                                        <tr>
                                            <td style="border:0; width:33%;"><input type="checkbox" <?php echo ($answer['ip_40'] == "Rendah") ? "checked='true'" : ""; ?> style="vertical-align:text-bottom;"> Rendah</td>
                                            <td style="border:0; width:33%;"><input type="checkbox" <?php echo ($answer['ip_40'] == "Sedang") ? "checked='true'" : ""; ?> style="vertical-align:text-bottom;"> Sedang</td>
                                            <td style="border:0; width:33%;"><input type="checkbox" <?php echo ($answer['ip_40'] == "Tinggi") ? "checked='true'" : ""; ?> style="vertical-align:text-bottom;"> Tinggi</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width:50%">Disetujui Oleh :</td>
                            </tr>
                            <tr>
                                <td style="width:50%; padding:0">
                                    <p style="padding-left: 12px;">*Catatan:</p>
                                    <ol>
                                        <li>Penilaian risiko mengacau kepada lampiran matrix risiko terlampir</li>
                                        <li>Jika tingkat risiko tinggi wajib di setujui oleh NPH dan RH</li>
                                    </ol>
                                </td>
                                <td style="width:50%;">
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; width:50%;">
                                                <?php
                                                    if(isset($approval['approval_7_1'])){
                                                        if($approval['approval_7_1'] == 1){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }else if($approval['approval_7_1'] == 2){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center; width:50%;">
                                                <?php
                                                    if(isset($approval['approval_8_1'])){
                                                        if($approval['approval_8_1'] == 1){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }else if($approval['approval_8_1'] == 2){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">National Production Head</td>
                                            <td style="text-align: center; font-size:9pt">Regional Head</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">(<?php echo (isset($approval['nama_7_1'])) ? $approval['nama_7_1'] : "............................." ?>)</td>
                                            <td style="text-align: center; font-size:9pt">(<?php echo (isset($approval['nama_8_1'])) ? $approval['nama_8_1'] : "............................." ?>)</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table class="form-table" style="width: 100%;">
                <tr>
                    <td class="biru" style="font-weight:bold;">C. Informasi Khusus</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">I. Peralatan /Perlengkapan keselamatan / proteksi kebakaran yang harus ada (Beri tanda yang sesuai dengan jenis Pekerjaan dan telah Dilengkapi)</td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            <tr>
                                <td width="60%">
                                    <table class="table-hidden">
                                        <?php 
                                            $jawaban_52 = explode(",",$answer['ip_52']);

                                            for($i = 1; $i <= 20; $i++){
                                                $ik[$i] = false;

                                                foreach($jawaban_52 as $jwb){
                                                    if($i === (int)$jwb){
                                                        $ik[$i] = true;
                                                    }
                                                }
                                            }
                                        ?>

                                        <tr>
                                            <td style="width:2%;"><input type="checkbox" <?php echo ($ik[1]) ? "checked='true'" : ""; ?>></td>
                                            <td style="width:2%;">a.</td>
                                            <td>Alat Pemadam Api Ringan (APAR)</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[2]) ? "checked='true'" : ""; ?>></td>
                                            <td>b.</td>
                                            <td>Karung Basah</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[3]) ? "checked='true'" : ""; ?>></td>
                                            <td>c.</td>
                                            <td>Tanda - tanda peringatan (termasuk Lock Out Tag Out)</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[4]) ? "checked='true'" : ""; ?>></td>
                                            <td>d.</td>
                                            <td>Topeng Las</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[5]) ? "checked='true'" : ""; ?>></td>
                                            <td>e.</td>
                                            <td>Sarung Tangan Las</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[6]) ? "checked='true'" : ""; ?>></td>
                                            <td>f.</td>
                                            <td>Sarung Tangan Karet</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[7]) ? "checked='true'" : ""; ?>></td>
                                            <td>g.</td>
                                            <td>Safety belt/hamess</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[8]) ? "checked='true'" : ""; ?>></td>
                                            <td>h.</td>
                                            <td>Kacamata Keselamatan(safety google)</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[9]) ? "checked='true'" : ""; ?>></td>
                                            <td>i.</td>
                                            <td>Ear Plug</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[10]) ? "checked='true'" : ""; ?>></td>
                                            <td>j.</td>
                                            <td>Sepatu Safety</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[11]) ? "checked='true'" : ""; ?>></td>
                                            <td>k.</td>
                                            <td>Tangga/Scalfolding</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[12]) ? "checked='true'" : ""; ?>></td>
                                            <td>l.</td>
                                            <td>Pita pengaman lokasi</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[13]) ? "checked='true'" : ""; ?>></td>
                                            <td>m.</td>
                                            <td>Helm Keselamatan</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[14]) ? "checked='true'" : ""; ?>></td>
                                            <td>n.</td>
                                            <td>Alat Komunikasi (HT)</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[15]) ? "checked='true'" : ""; ?>></td>
                                            <td>o.</td>
                                            <td>Masker</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[16]) ? "checked='true'" : ""; ?>></td>
                                            <td>p.</td>
                                            <td>Breathing Apparatus</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[17]) ? "checked='true'" : ""; ?>></td>
                                            <td>q.</td>
                                            <td>Sepatu Karet</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[18]) ? "checked='true'" : ""; ?>></td>
                                            <td>r.</td>
                                            <td>Lampu Penerangan</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[19]) ? "checked='true'" : ""; ?>></td>
                                            <td>s.</td>
                                            <td>Tirai Las</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" <?php echo ($ik[20]) ? "checked='true'" : ""; ?>></td>
                                            <td>t.</td>
                                            <td>Lain - lain (sebutkan) <?php echo ($answer['ip_sub_52']) ? "[". $answer['ip_sub_52'] ."]" : "" ?></td>
                                        </tr>
                                    </table> 
                                </td>
                                <td width="40%">
                                    <table class="table-red">
                                        <td>
                                            <p><b class="div-red">DILARANG KERAS!!!</b> Mengelas Bin
                                            Produksi Dalam Kondisi Terisi. BIN yang akan Di-
                                            las Hanya Boleh dilakukan pada saat produksi
                                            stop dan Semua BIN Disampingnya Harus Dalam
                                            Keadaan Kosong.
                                            Mengelas Bin Yang terisi produk Akan
                                            Mengakibatkan <b class="div-red1">LEDAKAN!!!</b></p>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>          
                    </td>
                </tr>
            </table>
            <div class="header-row" style="page-break-before: always;">
                <div class="info">
                    <table class="form-table-header">
                        <tr>
                            <td rowspan="4" style="text-align:center; width:15%;">
                                <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                            </td>
                            <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                            <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                            <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?= $no_dokumen ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $berlaku ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                            <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $edisi ?> / <?= $revisi ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr> 
                            <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class="">3 dari 5</a> &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <table class="form-table" style="width: 100%;">
                <tr>
                    <td>
                        <b>II. Tindakan Pencegahan</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            <tr>
                                <td style="width:4%">a.</td>
                                <td style="width:85%">Peralatan dan perlengkapan kerja dan keselamatan dalam kondisi baik</td>
                                <td style="width:11%"><span class="<?php echo ($answer['ip_53'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_53'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>b.</td>
                                <td>Bahaya mekanis / listrik / gas telah dilindungi dengan baik / telah dimatikan</td>
                                <td><span class="<?php echo ($answer['ip_54'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_54'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td>Lantai Bersih dari bahan/debu mudah terbakar</td>
                                <td><span class="<?php echo ($answer['ip_55'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_55'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>d.</td>
                                <td>Penggunaan bahan berbahaya telah diketahui oleh manager dan safety officer</td>
                                <td><span class="<?php echo ($answer['ip_56'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_56'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>e.</td>
                                <td>Bahan / debu mudah terbakar dibersihkan dari sisi bersebrangan dengan lokasi, jika bekerja didinding</td>
                                <td><span class="<?php echo ($answer['ip_57'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_57'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>f.</td>
                                <td>Kontainer bersih dari debu/uap/gas yang mudah terbakar</td>
                                <td><span class="<?php echo ($answer['ip_58'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_58'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>g.</td>
                                <td>Ventilasi cukup saat dilakukan Pekerjaan pemotongan/pengelasan diruang tertutup</td>
                                <td><span class="<?php echo ($answer['ip_59'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_59'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>h.</td>
                                <td>Sumber api dalam pengontrolan</td>
                                <td><span class="<?php echo ($answer['ip_60'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_60'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>i.</td>
                                <td>Area kerja dibersihkan dari sumber - sumber bahaya</td>
                                <td><span class="<?php echo ($answer['ip_61'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_61'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>j.</td>
                                <td>Kondisi hydrant/nozle terdekat baik</td>
                                <td><span class="<?php echo ($answer['ip_62'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_62'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>k.</td>
                                <td>Terpasang tanda LOTO sebelum pengerjaan</td>
                                <td><span class="<?php echo ($answer['ip_63'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_63'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>l.</td>
                                <td>Pencahyaan cukup untuk pekerjaan di ruang tertutup</td>
                                <td><span class="<?php echo ($answer['ip_64'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_64'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>m.</td>
                                <td>Ada pembatasan area terhadap aktivitas lain/orang lalu lalang</td>
                                <td><span class="<?php echo ($answer['ip_65'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_65'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>n.</td>
                                <td>Kondisi tanah tidak ada genangan air yang memungkinngkan longsor tanah(Khusus untuk penggalian)</td>
                                <td><span class="<?php echo ($answer['ip_66'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_66'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>o.</td>
                                <td>Ada persiapan tanggap darurat untuk kecelakaan (First Aider, Ambulance, Tas P3K, Khusus pada saat pekerjaan penggalian/excavasi)</td>
                                <td><span class="<?php echo ($answer['ip_67'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_67'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>p.</td>
                                <td>Membasahi benda padat yang menyatu dengan benda yang akan dilas (khusus untuk pengelasan)</td>
                                <td><span class="<?php echo ($answer['ip_68'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_68'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>q.</td>
                                <td>Khusus pengelasan bin : bin yang dilas dan bin bersebelahan dalam kondisi kosong</td>
                                <td><span class="<?php echo ($answer['ip_69'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_69'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                        </table>
                    </td>
                    
                </tr>
                <tr>
                    <td>
                        <b>III. Tindakan Khusus (Jika Diperlukan)</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            Dilakukan Pengujian kualitas udara dan gas-gas berbahaya didalam ruang tertutup :
                            <tr>
                                <td style="width:4%">a.</td>
                                <td style="width:75%;">% Oksigen (batas 19.5% - 23.5%)</td>
                                <td style="width:7%; text-align:right; padding-right:15px;"><a class="jawaban-text"><?= $answer['ip_70'] ?>%</a></td>
                            </tr>
                            <tr>
                                <td>b.</td>
                                <td>% Carbon Monoksida (< 25 ppm)</td>
                                <td style="text-align:right; padding-right:15px;"><a class="jawaban-text"><?= $answer['ip_71'] ?>%</a></td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td>% Hydrogen Sulfida (< 10 ppm)</td>
                                <td style="text-align:right; padding-right:15px;"><a class="jawaban-text"><?= $answer['ip_72'] ?>%</a></td>
                            </tr>
                            <tr>
                                <td>e.</td>
                                <td>Gas lain (sebutkan)</td>
                                <td style="text-align:right; padding-right:15px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <div class="header-row" style="page-break-before: always;">
                    <div class="info">
                        <table class="form-table-header">
                            <tr>
                                <td rowspan="4" style="text-align:center; width:15%;">
                                    <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                                </td>
                                <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                                <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                                <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?= $no_dokumen ?></a> &nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                                <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $berlaku ?></a> &nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                                <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                                <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $edisi ?> / <?= $revisi ?></a> &nbsp;&nbsp;</td>
                            </tr>
                            <tr> 
                                <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                                <td class="font-header clear-side">&nbsp;&nbsp;<a class="">4 dari 5</a> &nbsp;&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <tr>
                    <td class="abu-abu"><b>D. Peminjaman Alat</b></td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            <?php 
                                $jawaban_73 = explode(",",$answer['ip_73']);

                                for($i = 1; $i <= 5; $i++){
                                    $ik[$i] = false;

                                    foreach($jawaban_73 as $jwb){
                                        if($i === (int)$jwb){
                                            $ik[$i] = true;
                                        }
                                    }
                                }
                            ?>
                            <tr>
                                <td><input type="checkbox" <?php echo ($ik[1]) ? "checked='true'" : ""; ?>></td>
                                <td>a.</td>
                                <td style="width:100%;"> Alat Pemadam Api Ringan (APAR)</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" <?php echo ($ik[2]) ? "checked='true'" : ""; ?>></td>
                                <td>b.</td>
                                <td>Safety hamess</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" <?php echo ($ik[3]) ? "checked='true'" : ""; ?>></td>
                                <td>c.</td>
                                <td>Safety sign</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" <?php echo ($ik[4]) ? "checked='true'" : ""; ?>></td>
                                <td>d.</td>
                                <td>Karung Basah</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" <?php echo ($ik[5]) ? "checked='true'" : ""; ?>></td>
                                <td>e.</td>
                                <td>dll <?php echo ($answer['ip_sub_73']) ? "[". $answer['ip_sub_73'] ."]" : "" ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="abu-abu"><b>E. Persetujuan Pra Pekerjaan</b></td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <td>
                        <table class="table-hidden" style="font-size:9pt;">
                            <tr>
                                <td colspan="6">Telah dilakukan pemeriksaan terhadap persiapan pekerjaan yang akan dilakukan secara bersama, dan pekerjaan</td>
                            </tr>
                            <tr>
                                <td style="width: 17%;">Boleh dilakukan</td>
                                <td><input type="checkbox" <?php echo ($answer['ip_74'] == "Boleh Dilakukan") ? "checked='true'" : ""; ?>></td>
                                <td style="width: 23%;">tidak Boleh dilakukan</td>
                                <td><input type="checkbox" <?php echo ($answer['ip_74'] == "Tidak Boleh Dilakukan") ? "checked='true'" : ""; ?>></td>
                                <td style="width: 19%;">ditunda dilakukan</td>
                                <td><input type="checkbox" <?php echo ($answer['ip_74'] == "Ditunda Dilakukan") ? "checked='true'" : ""; ?>></td>
                            </tr>
                        </table>
                        <table class="table-hidden" style="font-size:9pt;">
                            <tr><td colspan="5">Telah dilakukan pemeriksaan ulangi (jika ijin belum diberikan pada ijin pertama) Waktu :</td></tr>
                            <tr>
                                <td style="width:20%;text-align: center; font-size:9pt">Pekerja</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Pengawas</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Supervisor Pemberi Kerja</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Supervisor Area</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Petugas Safety</td>                        
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%;">
                                    <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                    <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                    <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                        if($approval['approval_1_1'] == 1){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }else if($approval['approval_1_1'] == 2){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <?php
                                        if($approval['approval_2_1'] == 1){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }else if($approval['approval_2_1'] == 2){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <?php
                                        if($approval['approval_3_1'] == 1){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }else if($approval['approval_3_1'] == 2){
                                            ?>
                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                            <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?= $pemohon->caption ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?= $pemohon->caption ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_1_1'])) ? $approval['nama_1_1'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_2_1'])) ? $approval['nama_2_1'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_3_1'])) ? $approval['nama_3_1'] : "............................." ?>)
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_1_1'])) ? $approval['catatan_1_1'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_2_1'])) ? $approval['catatan_2_1'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_3_1'])) ? $approval['catatan_3_1'] : "............................." ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                   
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <?php
                                        $write = "*Foto ";
                                        
                                        for($i = 81; $i <= 84; $i++){
                                            if($answer['ip_' . $i]){
                                                if($i == 81){
                                                    $write .= "Alat Kerja";
                                                }else if($i == 82){
                                                    $write .= "Personil";
                                                }else if($i == 83){
                                                    $write .= "Lokasi Kerja";
                                                }else if($i == 84){
                                                    $write .= "Jalur";
                                                }
                                            }

                                            if($i < 84){
                                                $write .= ", ";
                                            }
                                        }

                                        $write .= " Terlampir Dibawah."
                                    ?>
                                    <p style="font-style:italic"><?php echo ($answer['ip_81'] || $answer['ip_82'] || $answer['ip_83'] || $answer['ip_84']) ? $write : "" ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="font-size:9pt">
                                    *Wajib Mencantumkan Nama
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table-hidden">
                            <p style="text-align: center;">Diketahui oleh :</p>
                            <tr>
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                Security Head
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if($approval['approval_4_1'] == 1){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }else if($approval['approval_4_1'] == 2){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                PGA Head
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if($approval['approval_5_1'] == 1){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }else if($approval['approval_5_1'] == 2){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                Plant Manager
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if($approval['approval_6_1'] == 1){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }else if($approval['approval_6_1'] == 2){
                                                        ?>
                                                        <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                        <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_4_1'])) ? $approval['nama_4_1'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_5_1'])) ? $approval['nama_5_1'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_6_1'])) ? $approval['nama_6_1'] : "............................." ?>)
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_4_1'])) ? $approval['catatan_4_1'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_5_1'])) ? $approval['catatan_5_1'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_6_1'])) ? $approval['catatan_6_1'] : "............................." ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-size:9pt">
                                    *Catatan:Persetujuan ini khusus untuk pekerjaan ijin kerja panas
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="header-row" style="page-break-before: always;">
                <div class="info">
                    <table class="form-table-header">
                        <tr>
                            <td rowspan="4" style="text-align:center; width:15%;">
                                <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                            </td>
                            <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                            <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                            <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?= $no_dokumen ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $berlaku ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                            <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?= $edisi ?> / <?= $revisi ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr> 
                            <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class="">5 dari 5</a> &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <table class="form-table">
                <tr>
                    <td class="abu-abu">
                        <b>F. Pemeriksaan Saat Pekerjaan Berlangsung</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pemeriksaan saat pekerjaan Berlangsung
                        <table class="form-table">
                            <tr>
                                <td style="width:86%">a. Apakah Pekerja konsisten menggunakan APD dengan taat?</td>
                                <td style="width:14%"><span class="<?php echo ($answer['ip_75'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_75'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>b. Apakah Pekerja konsisten bekerja dengan cara yang aman?</td>
                                <td><span class="<?php echo ($answer['ip_76'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_76'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>c. Apakah lingkungan kerja konsisten dalam kondisi aman?</td>
                                <td><span class="<?php echo ($answer['ip_77'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_77'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                        </table>
                        <br>
                        *Catatan : Jika ditemukan ketidaksesuaian maka pekerjaan akan dihentikan hingga dilakukan perbaikan
                    </td>
                </tr>
                <tr>
                    <td  class="abu-abu">
                        <b>G. Pemeriksaan Pasca Pekerjaan</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pemeriksaan setelah pekerjaan selesai :
                        <table class="form-table">
                            <tr>
                                <td style="width:86%">a. Apakah sisa pekerjaan sudah disingkirkan?</td>
                                <td style="width:14%"><span class="<?php echo ($answer['ip_78'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_78'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>b. Apakah mesin/jalur yang diperbaiki sudah dapat berjalan dengan normal dan aman?                                </td>
                                <td><span class="<?php echo ($answer['ip_79'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_79'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                            <tr>
                                <td>c. Apakah pekerjaan Sudah selesai?</td>
                                <td><span class="<?php echo ($answer['ip_80'] == "Ya") ? "garis" : ""; ?>">Ya</span>/<span class="<?php echo ($answer['ip_80'] == "Tidak") ? "garis" : ""; ?>">Tidak</span></td>
                            </tr>
                        </table>
                        <table class="table-hidden">
                            <tr>
                                <td colspan="5">Telah dilakukan pemeriksaan setelah selesai melakukan pekerjaan, dan dinyatakan area kerja aman dan tidak ada sisa-sisa sumber api/bahaya yang dapat timbul akibat dari pekerjaan.</td>
                            </tr>
                            <tr>
                                <td style="width:20%;text-align: center; font-size:9pt">Pekerja</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Pengawas</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Supervisor Pemberi Kerja</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Supervisor Area</td>
                                <td style="width:20%;text-align: center; font-size:9pt">Petugas Safety</td>                        
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%;">
                                    <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                    <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                    <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <?php
                                        if(isset($approval['approval_1_2'])){
                                            if($approval['approval_1_2'] == 1){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }else if($approval['approval_1_2'] == 2){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <?php
                                        if(isset($approval['approval_2_2'])){
                                            if($approval['approval_2_2'] == 1){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }else if($approval['approval_2_2'] == 2){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center; width:20%;">
                                    <?php
                                        if(isset($approval['approval_3_2'])){
                                            if($approval['approval_3_2'] == 1){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }else if($approval['approval_3_2'] == 2){
                                                ?>
                                                <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                <?php
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?= $pemohon->caption ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?= $pemohon->caption ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_1_2'])) ? $approval['nama_1_2'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_2_2'])) ? $approval['nama_2_2'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    (<?php echo (isset($approval['nama_3_2'])) ? $approval['nama_3_2'] : "............................." ?>)
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_1_2'])) ? $approval['catatan_1_2'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_2_2'])) ? $approval['catatan_2_2'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <p>Catatan : <?php echo (isset($approval['catatan_3_2'])) ? $approval['catatan_3_2'] : "............................." ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                   
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    
                                </td>
                                <td style="text-align: center; width:20%; font-size:9pt;">
                                    <?php
                                        $write = "*Foto ";
                                        
                                        for($i = 81; $i <= 84; $i++){
                                            if($answer['ip_' . $i]){
                                                if($i == 81){
                                                    $write .= "Alat Kerja";
                                                }else if($i == 82){
                                                    $write .= "Personil";
                                                }else if($i == 83){
                                                    $write .= "Lokasi Kerja";
                                                }else if($i == 84){
                                                    $write .= "Jalur";
                                                }
                                            }

                                            if($i < 84){
                                                $write .= ", ";
                                            }
                                        }

                                        $write = "*Foto Laporan Terlampir Dibawah."
                                    ?>
                                    <p style="font-style:italic"><?php echo ($answer['ip_81'] || $answer['ip_82'] || $answer['ip_83'] || $answer['ip_84']) ? $write : "" ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="padding:0;">
                    <td>
                        <table class="table-hidden">
                            <tr style="padding:0;">
                                <td>
                                    <p style="text-align: center;">Diketahui oleh :</p>
                                </td>
                            </tr>
                            <tr style="padding:0;">
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                Security Head
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if(isset($approval['approval_4_2'])){
                                                        if($approval['approval_4_2'] == 1){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }else if($approval['approval_4_2'] == 2){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                PGA Head
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if(isset($approval['approval_5_2'])){
                                                        if($approval['approval_5_2'] == 1){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }else if($approval['approval_5_2'] == 2){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-hidden">
                                        <tr>
                                            <td style="text-align: center; font-size:9pt">
                                                Plant Manager
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php
                                                    if(isset($approval['approval_6_2'])){
                                                        if($approval['approval_6_2'] == 1){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: green; margin:0;">DISETUJUI</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }else if($approval['approval_6_2'] == 2){
                                                            ?>
                                                            <p style="font-size: 11pt; font-weight:bold; color: red; margin:0;">DITOLAK</p>
                                                            <p style="font-size: 5pt; margin:0;">Ditandatangani secara digital oleh</p>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_4_2'])) ? $approval['nama_4_2'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_5_2'])) ? $approval['nama_5_2'] : "............................." ?>)
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    (<?php echo (isset($approval['nama_6_2'])) ? $approval['nama_6_2'] : "............................." ?>)
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_4_2'])) ? $approval['catatan_4_2'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_5_2'])) ? $approval['catatan_5_2'] : "............................." ?></p>
                                </td>
                                <td style="text-align: center; font-size:9pt">
                                    <p>Catatan : <?php echo (isset($approval['catatan_6_2'])) ? $approval['catatan_6_2'] : "............................." ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>   
        </div>
        <table class="form-table1" style="width:100%;">
            <tr>
                <td>
                    <table class="table-hidden">
                        <tr>
                            <td>
                                <b class="div-red">DILARANG KERAS!!!</b> Mengelas Bin Produksi Dalam Kondisi
                                Terisi. BIN yang akan Di-las Hanya Boleh
                                dilakukan pada saat produksi stop dan
                                Semua BIN Disampingnya Harus Dalam
                                Keadaan Kosong.
                                Mengelas Bin Yang terisi produk Akan
                            Mengakibatkan <b class="div-red1">LEDAKAN!!!</b>       
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
            if($answer['ip_81'] || $answer['ip_82'] || $answer['ip_83'] || $answer['ip_84']){
                ?>
                <br>
                <table class="form-table">
                    <tr>
                        <td><p>Lampiran Foto dari Tim Petugas Safety - Form Izin Kerja</p></td>
                    </tr>
                    <tr>
                        <td><p>Foto Alat Kerja</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_81']) ? "<img src='" . base_url() . $answer['ip_81'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Personil</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_82']) ? "<img src='" . base_url() . $answer['ip_82'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Lokasi Kerja</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_83']) ? "<img src='" . base_url() . $answer['ip_83'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Jalur</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_84']) ? "<img src='" . base_url() . $answer['ip_84'] . "' width='250'>" : "" ?></td>
                    </tr>
                </table>
                <?php
            }
        ?>
        <?php
            if($answer['ip_85'] || $answer['ip_86'] || $answer['ip_87'] || $answer['ip_88']){
                ?>
                <br>
                <table class="form-table">
                    <tr>
                        <td><p>Lampiran Foto dari Tim Petugas Safety - Pasca Pekerjaan</p></td>
                    </tr>
                    <tr>
                        <td><p>Foto Lokasi 1</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_85']) ? "<img src='" . base_url() . $answer['ip_85'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Lokasi 1</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_86']) ? "<img src='" . base_url() . $answer['ip_86'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Pekerjaan 1</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_87']) ? "<img src='" . base_url() . $answer['ip_87'] . "' width='250'>" : "" ?></td>
                    </tr>
                    <tr>
                        <td><p>Foto Pekerjaan 2</p></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><?php echo ($answer['ip_88']) ? "<img src='" . base_url() . $answer['ip_88'] . "' width='250'>" : "" ?></td>
                    </tr>
                </table>
                <?php
            }
        ?>
    </div>
</body>
</html>
