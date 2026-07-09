 <!--Side bar Menu-->
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                             <?php 
                                $hak_akses = $this->session->userdata('group_user');
                                // $id_user = $this->db->get_where('log_user',['token' => $this->session->userdata('token')])->result();
                                // $user = $this->db->get_where('z_master_user',['id_user' => $id_user[0]->id_user])->result();
                                // $hak_akses = $user[0]->group_user;
                                //query menu()
                                $query_menu= "select CONCAT(UCASE(MID(x.type,1,1)),MID(x.type,2)) AS tipe, x.type as type FROM 
                                        (select DISTINCT(menu_type)as type FROM master_menu) x";
                                if($hak_akses =='administrator' or $hak_akses =='koor_security')
                                {
                                    
                                }
                                else
                                {
                                    $query_menu = $query_menu . " where type <> 'dashboard' and type <> 'head'";
                                }
                                
                             
                                $result_menu = $this->db->query($query_menu)->result_array();

                                //query sub menu
                                // $hak_akses = $this->session->userdata('group_user');

                                $query_submenu="select
                                                master_user_d_access_right.group_user as group_user
                                                , master_user_d_access_right.id_menu as id_menu
                                                , master_menu.caption as caption
                                                , master_menu.menu_type as menu_type
                                                , master_menu.src as src
                                                , master_menu.submenu as submenu   
                                                , master_menu.has_sub as has_sub
                                                from
                                                master_menu, master_user_d_access_right
                                                where
                                                master_menu.id_menu = master_user_d_access_right.id_menu
                                                and master_user_d_access_right.group_user = '$hak_akses'
                                                and master_menu.src not like '%under_construction.htm%'
                                                order by master_menu.menu_type, master_menu.urutan";

                                $result_submenu = $this->db->query($query_submenu)->result_array();

                                // var_dump($result_submenu);
                            
                                //var_dump($result_menu);
                                //die;

                            ?>

                            <div class="accordion" id="sidebar_acordion">
                                <div class="">
                                    <!--Looping foreach menu-->
                                    <?php foreach ($result_menu as $m): ?>
                                        <div class="" id="heading_<?= $m['tipe'];?>">
                                            <h2 class="mb-0">
                                                <a class="sb-sidenav-menu-heading" type="button" data-toggle="collapse" data-target="#<?= $m['tipe'];?>" aria-expanded="false" aria-controls="<?= $m['tipe'];?>">
                                                    <?= $m['tipe'];?>
                                                </a>
                                            </h2>
                                        </div>

                                        <div id="<?= $m['tipe'];?>" class="collapse" aria-labelledby="heading_<?= $m['tipe'];?>" data-parent="#sidebar_acordion">
                                             <!--Looping foreach sub menu-->
                                            <?php foreach ($result_submenu as $sm): ?>
                                            <div class="">
                                                
                                                <?php if($sm['menu_type'] == $m['type']):?>

                                                    <?php if($sm['submenu'] == 0):?>
                                                        <?php $id_colapse = $sm['id_menu'];?>

                                                        <?php if($sm['has_sub'] == 1): ?>
                                                             <a class="nav-link small collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts_<?= $id_colapse;?>" aria-expanded="false" aria-controls="collapseLayouts">
                                                                    <?= $sm['caption']; 
                                                                    if($this->uri->segment(3)== $m['tipe']){echo 'class="active"';}
                                                                    ?>
                                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                                            </a>

                                                        <?php else: ?>

                                                            <div class="">
                                                                <a class="nav-link small" href="<?= base_url($sm['src']);?>" aria-expanded="false" aria-controls="">
                                                                    <?= $sm['caption'];
                                                                     if($this->uri->segment(3)== $m['tipe']){echo 'class="active"';}
                                                                     ?>
                                                                </a>
                                                            </div>

                                                        <?php endif; ?>
                                                        
                                                    <?php else: ?>
                                                         <div class="collapse" id="collapseLayouts_<?= $id_colapse;?>" aria-labelledby="heading_<?= $m['tipe'];?>" data-parent="#sidenavAccordion">
                                                            <nav class="sb-sidenav-menu-nested nav">
                                                                <a class="nav-link small" href="<?= base_url($sm['src']);?>"> 
                                                                    <?= $sm['caption'];
                                                                         if($this->uri->segment(3)== $m['tipe']){echo 'class="active"';}
                                                                    ?>
                                                                    
                                                                </a>
                                                            </nav>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif;?>
                                            </div>
                                             <!--Looping end foreach sub menu-->
                                            <?php endforeach;?>
                                        </div>
                                     <!--Looping end foreach menu-->
                                    <?php endforeach;?>
                                    </div>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="sb-sidenav-footer"> -->
                        <!-- <div class="small">Logged in as:</div> -->
                        <!-- <?php // $group_user['alias']; ?> -->
                    <!-- </div> -->
                </nav>
            </div>
