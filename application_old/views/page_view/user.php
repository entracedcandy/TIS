<body>
    <div id="layoutSidenav_content">
        <div class="container-fluid">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row my-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">Master User</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content px-3">
                <div class="card card-secondary">
                    <div class="card-header px-4">
                        <h3 class="text-bold">Input Data User</h3>
                        <form>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" id="in_nama">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" id="in_dept">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="input-group">
                                            <select class="form-control" id="in_status" onchange="changeVendor(this.value)">
                                                <option value="" selected></option>
                                                <option value="p">PERMANENT</option>
                                                <option value="n">NON PERMANENT</option>
                                            </select>
                                            <select class="form-control" id="in_vendor" disabled>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label id="nomor">NIK</label>
                                        <input type="text" class="form-control" id="in_noID">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" id="in_username">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Cost Center</label>
                                        <select class="form-control" id="in_cc">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>No Telepon (Whatsapp)</label>
                                        <input type="text" class="form-control" id="in_noHP">
                                    </div>
                                </div>
                            </div>
                            <button type="text" class="btn btn-primary float-right" onclick="simpanUser()">Simpan</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table_data">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">No Reg</th>
                                    <th class="text-center">Menu</th>
                                </tr>
                            </thead>
                            <tbody id="data_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
    
    <script src="<?= base_url('assets/AdminLTE/');?>jquery/jquery.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>jquery-ui/jquery-ui.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>moment/moment.min.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>daterangepicker/daterangepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/');?>js/scripts.js"></script>
    <script src="<?= base_url('assets/AdminLTE/');?>select2/js/select2.full.js"></script>

    <script>
        $(document).ready(function() { 
            renderData();
        });

        function renderData(){
            document.getElementById("data_table").innerHTML = "";

            $.ajax({
                url: '../cpar/User/getData',
                type: 'POST',
                dataType: 'json',
                success: function(data){
                    let print = "";

                    for(let i = 0; i < data.length; i++){
                        print += "<tr>"
                        print += "<td class='text-center align-middle'>"
                        print += (i+1);
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].caption;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].department;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].status;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].vendor;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].nik;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += data[i].no_reg;
                        print += "</td>"
                        print += "<td class='text-center align-middle'>"
                        print += "<button class='btn btn-warning'><i class='fas fa-pencil-alt'></i></button>";
                        print += "</td>"
                        print += "</tr>"
                    }

                    document.getElementById("data_table").innerHTML = print;
                },
                error: function() {
                    console.error("error getData");
                }
            });
        }

        function changeVendor(param){
            if(param){
                document.getElementById("in_vendor").disabled = false;

                if(param === 'p'){
                    document.getElementById("in_vendor").innerHTML = "";
                    document.getElementById("in_vendor").innerHTML = "<option value='CPI'>CPI</option>";
                    
                    document.getElementById("nomor").innerHTML = "";
                    document.getElementById("nomor").innerHTML = "NIK";
                }else if(param === 'n'){
                    document.getElementById("in_vendor").innerHTML = "";

                    let print = '';

                    print += "<option value=''></option>";
                    print += "<option value='MDP'>MDP</option>";
                    print += "<option value='MJA'>MJA</option>";
                    print += "<option value='CCC'>CCC</option>";
                    print += "<option value='LAR'>LAR</option>";
                    print += "<option value='SWORD'>SWORD</option>";

                    document.getElementById("in_vendor").innerHTML = print;

                    document.getElementById("nomor").innerHTML = "";
                    document.getElementById("nomor").innerHTML = "NoReg";
                }
            }else{
                document.getElementById("in_vendor").disabled = true;
                document.getElementById("in_vendor").innerHTML = "";
                document.getElementById("in_vendor").innerHTML = "<option value=''></option>";

                document.getElementById("nomor").innerHTML = "";
                document.getElementById("nomor").innerHTML = "NIK";
            }
        }
    </script>

</body>
</html>