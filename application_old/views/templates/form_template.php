<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
            }

            footer {
                position: fixed;
                bottom: -60px; 
                left: 0px; 
                right: 0px;
            }

            main {
                /* position: fixed; */
                /* top: 100px; */
            }

            body {
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: Arial, sans-serif;
                /* height: 100vh; */
                /* margin: 0; */
                font-size: 11pt;

                margin-top: 1.5cm;
                margin-bottom: 1.5cm;
            }

            .info {
                display: flex;
                flex-direction: column;
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

            .label-border {
                font-weight: bold;
            }

            .font-header{
                font-size: 12px;
            }

            .clear-side{
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <div class="header-row">
                <div class="info">
                    <table class="form-table-header">
                        <tr>
                            <td rowspan="4" style="text-align:center; width:15%;">
                                <img src="<?= base_url(); ?>asset/img/pokphand_logo.png" alt="CPI Logo" width="70" height=auto>
                            </td>
                            <td rowspan="2" class="label-border" style="text-align:center; width:40%; padding-top:10px;"><h3>FORM</h3></td>
                            <td class="font-header clear-side" style="width:13%;">&nbsp;&nbsp;No. Dokumen :</td>
                            <td class="font-header clear-side" style="width:27%;">&nbsp;&nbsp;<a class=""><?php echo (isset($no_dokumen)) ? $no_dokumen : "" ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="font-header clear-side">&nbsp;&nbsp;Berlaku efektif :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?php echo (isset($berlaku)) ? $berlaku : "" ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="label-border"><h5 style="text-align: center; vertical-align:bottom;">IJIN KERJA</h5></td>
                            <td class="font-header clear-side">&nbsp;&nbsp;Edisi / Revisi :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?php echo (isset($edisi)) ? $edisi : "" ?> / <?php echo (isset($revisi)) ? $revisi : "" ?></a> &nbsp;&nbsp;</td>
                        </tr>
                        <tr> 
                            <td class="font-header clear-side">&nbsp;&nbsp;Halaman :</td>
                            <td class="font-header clear-side">&nbsp;&nbsp;<a class=""><?php echo $PAGE_NUM; ?> dari <?php $PAGE_COUNT; ?></a> &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
        </header>

        <footer>
            Copyright &copy; <?php echo date("Y");?> 
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p style="page-break-after: always;">
                Content Page 1
            </p>
            <p style="page-break-after: never;">
                Content Page 2
            </p>
        </main>
    </body>
</html>