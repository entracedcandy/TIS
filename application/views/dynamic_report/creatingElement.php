<?php
    // var_dump($e->id_element);
    if($posisiNow == "footer"){
        if($cmpNow == "data"){
            ?>
                <button type="button" class="btn btn-outline-secondary float-right" id="btn_search" onclick="execute()">Search</button>
            <?php
        }
    }else{
        if($cmpNow == "data"){
            if($e->element == "combobox"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <select class="form-control" name="<?= $e->caption; ?>" id="<?= "elm_" . $e->id_element; ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                            <option value=""></option>
                            <?php
                                $namaElement = "elm_" . $e->id_element;
                                for($z = 0; $z < count(${$namaElement}[0]); $z++){
                                    $field = ${$namaElement}[1][$z];
                                    ?>
                                        <option value="<?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?>"><?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                <?php
            }elseif($e->element == "datetime"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="datetime-local" name="<?= $e->caption; ?>" class="form-control" id="elm_<?= $e->id_element ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                    </div>
                <?php
            }elseif($e->element == "textbox"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="<?= $e->caption; ?>" class="form-control" id="elm_<?= $e->id_element ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                    </div>
                <?php
            }elseif($e->element == "number"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" name="<?= $e->caption; ?>" class="form-control" id="elm_<?= $e->id_element ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                    </div>
                <?php
            }elseif($e->element == "date"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="date" name="<?= $e->caption; ?>" class="form-control" id="elm_<?= $e->id_element ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                    </div>
                <?php
            }elseif($e->element == "multiple"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <select class="form-control" name="<?= $e->caption; ?>" id="<?php if($e->id_element == "filter_chart"){echo $e->id_element;}else{echo "elm_" . $e->id_element;} ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?> multiple="multiple">
                            <option value=""></option>
                            <?php
                                $namaElement = "elm_" . $e->id_element;
                                for($z = 0; $z < count(${$namaElement}[0]); $z++){
                                    $field = ${$namaElement}[1][$z];
                                    ?>
                                        <option value="<?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?>"><?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                <?php
            }elseif($e->element == "combobox-search"){
                ?>
                    <div class="col-sm-4">
                        <label><?= $e->caption; ?></label>
                    </div>
                    <div class="col-sm-8">
                        <select class="form-control" name="<?= $e->caption; ?>" id="elm_<?= $e->id_element ?>" <?php if($e->enable == "n"){echo "disabled";} if($e->hidden == "y"){echo "hidden";} ?>>
                            <option value=""></option>
                            <?php
                                $namaElement = "elm_" . $e->id_element;
                                for($z = 0; $z < count(${$namaElement}[0]); $z++){
                                    $field = ${$namaElement}[1][$z];
                                    ?>
                                        <option value="<?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?>"><?php echo str_replace("_", " ", ${$namaElement}[0][$z]->$field); ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                <?php
            }
        }elseif($cmpNow == "chart"){
            ?>
                <div class="chart">
                    <div id="chart_data" style="min-height: 250px; max-height: 750px; max-width: 100%;"></div>
                </div>   
            <?php
        }elseif($cmpNow == "table"){
            ?>
                <div class="table-responsive">
                    <div class="form-group form-inline mt-3 float-left">
                        <label id="vpp_label" hidden>Rows/Page</label>
                        <select class="ml-2 form-control" id="table_vpp" style="width:70px;" onchange="changeView()" hidden>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="float-right mt-3 mr-2">
                        <button type="button" class="btn btn-outline-secondary float-right" id="table_excel" onclick="exportExcel()" hidden>Export Excel</button>
                    </div>

                    <table class="table table-striped mt-2" id="table_data">
                        <thead id="table_field">
                        </thead>
                        <tbody id="table_content">
                        </tbody>
                    </table>
                    
                    <label class="float-left" id="table_total"></label>
                    <ul class="pagination pagination-sm dark float-right" id="table_paging" style="cursor: pointer"></ul>
                    <input type="text" id="table_locPage" hidden>
                </div>
            <?php
        }elseif($cmpNow == "info"){
            if($e->element == "title"){
                ?>
                    <div class="col-sm-12">
                        <label><?= $e->caption; ?></label>
                    </div>
                <?php
            }elseif($e->element == "text"){
                ?>
                    <div class="col-sm-12">
                        <p><?= $e->caption; ?></p>
                    </div>
                <?php
            }
        }
    }
?>