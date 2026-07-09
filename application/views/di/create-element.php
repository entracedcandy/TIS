<div class="row my-2 mx-1">
    <label class="col-sm-4"><?= $data_elm_di[$i][$j]->elm_caption ?></label>
    <?php
        if($data_elm_di[$i][$j]->elm_type == "datetime"){
            ?>
                <input type="datetime-local" class="form-control col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>">
            <?php
        }elseif($data_elm_di[$i][$j]->elm_type == "combobox"){
            ?>
                <select class="custom-select col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>">
                    <?php 
                        if(isset(${"value_".$data_elm_di[$i][$j]->elm_id."_query"})){
                            foreach(${"value_".$data_elm_di[$i][$j]->elm_id."_query"} as $v){
                                ?>
                                    <option value="<?= $v->VALUE ?>"><?= $v->VALUE ?></option>
                                <?php
                            }
                        }elseif(isset(${"value_".$data_elm_di[$i][$j]->elm_id."_model"})){
                            foreach(${"value_".$data_elm_di[$i][$j]->elm_id."_model"} as $v){
                                $keyValue = ${"value_".$data_elm_di[$i][$j]->elm_id."_key"};
                                ?>
                                    <option value="<?= $v->$keyValue ?>"><?= $v->$keyValue ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
            <?php
        }elseif($data_elm_di[$i][$j]->elm_type == "textarea"){
            ?>
                <textarea class="form-control col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>"></textarea>
            <?php
        }elseif($data_elm_di[$i][$j]->elm_type == "input"){
            ?>
                <input type="text" class="form-control col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>">
            <?php
        }elseif($data_elm_di[$i][$j]->elm_type == "date"){
            ?>
                <input type="date" class="form-control col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>">
            <?php
        }elseif($data_elm_di[$i][$j]->elm_type == "number"){
            ?>
                <input type="number" class="form-control col-sm-8" id="<?= $data_elm_di[$i][$j]->elm_id ?>">
            <?php
        }
    ?>
</div>