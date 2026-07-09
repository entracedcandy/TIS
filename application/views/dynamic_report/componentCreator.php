<?php
    $posisiNow = "";
    $cmpNow = "";

    for($i = 0; $i < $totalCmp; $i++){
        ?>
            <div class="card card-gray card-outline mt-3">
                <div class="card-header">
                    <?php
                        $posisiNow = "header";

                        ?>
                            <h5 class="card-title">
                                <?php 
                                    if($i == 0){
                                        echo"Data";
                                        $cmpNow = "data";
                                    }elseif($i > 0){
                                        if($totalCmp == 2){
                                            echo "Table";
                                            $cmpNow = "table";
                                        }else{
                                            if($i == 1){
                                                echo "Chart";
                                                $cmpNow = "chart";
                                            }else{
                                                echo "Table";
                                                $cmpNow = "table";
                                            }
                                        }
                                    } 
                                ?></h5>
                        <?php
                        
                    ?>
                </div>
                <div class="card-body">
                    <?php
                        $posisiNow = "body";
                        include 'elementCreator.php';
                    ?>
                </div>
                <div class="card-footer">
                    <?php
                        $posisiNow = "footer";
                        include 'elementCreator.php';
                    ?>
                </div>
            </div>
        <?php
    }

    // if($hasInfo == true){
        ?>
        <!-- <div class="card card-gray card-outline mt-3">
            <div class="card-header">
                <h5 class="card-title">Informasi</h5>
            </div>
            <div class="card-body"> -->
                <?php
                    // $posisiNow = "info";
                    // $cmpNow = "info";
                    // include 'elementCreator.php';
                ?>
            <!-- </div>
        </div> -->
        <?php
    // }
?>