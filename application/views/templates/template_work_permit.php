<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Work Permit</title>

        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
            }

            .container{
                margin: 40px 30px;
            }

            .table-content{
                border: 1px solid;
                border-collapse: collapse;
            }

            /* table, td, th{
                border: 1px solid;
                border-collapse: collapse;
            } */

            td, th{
                height: 20px;
                margin: 0;
                padding: 0;
            }

            .table-center {
                margin-left: auto;
                margin-right: auto;
                font-size: 14px;;
            }

            .table-full {
                width: 100%;
            }

            .font-small{
                font-size: 11px;
            }

            .font-content{
                font-size: 13px;
            }

            .font-bold{
                font-weight: bold;
            }

            .center-v-table{
                text-align: center;
                /* vertical-align: center; */
            }

            .center-h-table{
                vertical-align: center;
            }

            .h1{
                font-size: 22px;
            }

            .h2{
                font-size: 14px;
            }

            .bar-title{
                width: 100%;
                border: 1px solid black;
                background-color: #1864c9;
                margin: 15px 0px 0px;
                padding: 0;
                text-align: center;
                font-weight: bold;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <section class="header">
                <table class="table-content table-full">
                    <tr>
                        <td class="center-v-table table-content" rowspan="4" style="width: 17%;"><img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="65" height=auto></td>
                        <td class="font-bold center-v-table h1 table-content" rowspan="2" style="width: 40%; line-height: 0; padding-top: 8px;"><p>FORM</p></td>
                        <td class="font-small center-v-table table-content" style="width: 20%;">No. Dokumen :</td>
                        <td class="font-small center-v-table table-content" style="width: 23%;">X xx</td>
                    </tr>
                    <tr>
                        <td class="font-small center-v-table table-content">Berlaku efektif :</td>
                        <td class="font-small center-v-table table-content">X xx</td>
                    </tr>
                    <tr>
                        <td class="font-bold center-v-table h2 table-content" rowspan="2"><p>IJIN KERJA</p></td>
                        <td class="font-small center-v-table table-content">Edisi / Revisi :</td>
                        <td class="font-small center-v-table table-content">X xx</td>
                    </tr>
                    <tr>
                        <td class="font-small center-v-table table-content">Halaman :</td>
                        <td class="font-small center-v-table table-content">X xx</td>
                    </tr>
                </table>
            </section>
            <section class="header-bar" style="margin:0;">
                <div class="bar-title">IJIN KERJA</div>
            </section>
            <section class="sub-1 font-content" style="margin:0;">
                <p class="font-bold">A. Jenis Izin Kerja</p>
                <table width="97%" class="table-center">
                    <tr>
                        <td width="20%"><input type="checkbox">Ijin Kerja Panas</td>
                        <td width="20%"><input type="checkbox">Ijin Kerja di Ruang Tertutup</td>
                        <td width="20%"><input type="checkbox">Ijin Kerja di Ketinggian</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox">Ijin Kerja Listrik</td>
                        <td><input type="checkbox">Penggalian (Excavasi)</td>
                        <td><input type="checkbox">Lain - lain (Jelaskan)</td>
                    </tr>
                </table>
            </section>
        </div>
    </body>
</html>
