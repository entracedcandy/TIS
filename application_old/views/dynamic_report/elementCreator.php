<div class="mx-2">
    <?php
        // var_dump($posisiNow);
        if($posisiNow == "body"){
            ?>
                <div class="row">
                        <?php
                            if($cmpNow == "data"){
                                for($j = 1; $j < 4;$j++){
                                    ?>
                                        <div class="col-lg-4">
                                            <?php
                                                foreach($element as $e){
                                                    if($e->bagian !== "info"){
                                                        if($e->col == $j){
                                                            ?>
                                                                <div class="row mb-2">
                                                                    <?php include 'creatingElement.php'; ?>
                                                                </div>
                                                            <?php
                                                        }
                                                    }  
                                                }
                                            ?>     
                                        </div>
                                    <?php
                                }
                            }else{
                                include 'creatingElement.php';
                            }
                        ?>
                </div>
            <?php
        }elseif($posisiNow == "info"){
            foreach($element as $e){
                if($e->bagian == "info"){
                    ?>
                        <div class="row mb-2">
                            <?php include 'creatingElement.php'; ?>
                        </div>
                    <?php
                }
            }
        }else{
            include 'creatingElement.php';
        }
    ?>
</div>