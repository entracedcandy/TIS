<div class="row">
    <?php
        $max_col = (int)$max_col;

        for($i=0; $i < $max_col; $i++){
            ?>
                <div class="col-sm-4">
                    <?php
                        $elm_count = count($data_elm_di[$i]);
                        for($j = 0; $j < $elm_count; $j++){
                            ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php include 'create-element.php'; ?>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            <?php
        }
    ?>
</div>