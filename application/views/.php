<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">DOC Tracking</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-flui m-0">
            <div class="card card-dark mb-2">
                <div class="card-header">
                    <label>Pilih Nama Perusahaan</label>
                    <div class="form-floating">
                        <select class="form-select col-sm-4" id="allPT" onchange="PTChange(this.value)">
                            <option value="" selected></option>
                            <?php
                                foreach($allPT as $ap){
                                    ?>
                                        <option value="<?= $ap->id ?>"><?= $ap->nama_pt ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Perusahaan</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table_content">
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                                <tr>
                                    <th class="align-middle" scope="row">1</th>
                                    <td class="align-middle">PT. Sumber Kelapa</td>
                                    <td class="align-middle"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>