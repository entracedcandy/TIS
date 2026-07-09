<?php 
$image_path = base_url('assets/image/dashboard_bg.jpg'); 
$logo_path = base_url('assets/image/logo_CP2.png');
$logo_path2 = base_url('assets/image/logo_TIS.jpg'); 
?>

<style>
    .dashboard-container {
        background-image: url('<?= $image_path ?>'); 
        background-size: cover; 
        background-repeat: no-repeat; 
        background-position: center center; 
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .header-title {
        line-height: 1.2; 
        color: #1e5aa8; 
        text-transform: uppercase; 
        letter-spacing: 1px;
        font-size: 2.5rem; 
    }

    @media (max-width: 768px) {
        .dashboard-container {
            background-position: 70% center; 
            background-size: 350%; 
        }

        .header-title {
            font-size: 1.2rem; 
        }

        .logo-secondary {
            max-width: 60px !important; 
        }
    }
</style>

<div class="container-fluid dashboard-container">

    <div class="mb-5">
        <div class="text-center mb-3">
            <img src="<?= $logo_path ?>" alt="Logo" style="max-width: 100px; height: auto;">
        </div>
        
        <div class="d-flex align-items-start">
            <img src="<?= $logo_path2 ?>" alt="Logo Secondary" class="logo-secondary" style="max-width: 80px; height: auto; margin-right: 15px;">
            <div>
                <h1 class="font-weight-bold mb-0 header-title">
                    Technical Information System
                </h1>
                <h1 class="mb-0 header-title">
                    Online Database
                </h1>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column align-items-center" style="max-width: 400px; width: 100%;">

        <?php if (isset($user['group_user']) && $user['group_user'] != 'sales') : ?>
            <a href="<?= site_url('Dashboard_new/visual_data_kunjungan') ?>" 
               class="btn btn-lg mb-3 w-75" 
               style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                      color: white; 
                      border: 2px solid #1e5aa8; 
                      border-radius: 25px; 
                      font-weight: bold;
                      box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                Data Kunjungan
            </a>
        <?php endif; ?>

        <a href="<?= site_url('Dashboard_new/visual_kasus_penyakit') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            Kasus Penyakit
        </a>

        <a href="<?= site_url('Dashboard_new/visual_kandang_kosong') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            Kandang Kosong
        </a>

        <a href="<?= site_url('Dashboard_new/visual_kondisi_lingkungan') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            Kondisi Lingkungan
        </a>

        <a href="<?= site_url('Dashboard_new/visual_harga/telur') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            Harga Produk
        </a>

        <a href="<?= site_url('Dashboard_new/visual_harga_compare') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            HPP
        </a>

        <a href="<?= site_url('Dashboard_new/visual_vip_farms') ?>" 
           class="btn btn-lg mb-3 w-75" 
           style="background: linear-gradient(135deg, #4a9fd8 0%, #2b7ab8 100%); 
                  color: white; 
                  border: 2px solid #1e5aa8; 
                  border-radius: 25px; 
                  font-weight: bold;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            Farm VIP Grower
        </a>

    </div>

</div>