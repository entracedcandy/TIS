<main id="main">

  <!-- ======= Breadcrumbs ======= -->
  <section class="breadcrumbs">
    <div class="container">

      <ol>
        <li><a href="<?= base_url('premix'); ?>">Home</a></li>
        <li><?= $portfolio->judul_portfolio; ?></li>
      </ol>
      <h2><?= $portfolio->judul_portfolio; ?></h2>

    </div>
  </section><!-- End Breadcrumbs -->

  <!-- ======= Portfolio Details Section ======= -->
  <section id="portfolio-details" class="portfolio-details">
    <div class="container">

      <div class="row gy-4">

        <div class="col-lg-7">
          <div class="portfolio-details-slider swiper-container">
            <div class="swiper-wrapper align-items-center">

              <div class="swiper-slide">
                <img src="<?= base_url('assets/img/portfolio/') . $portfolio->gambar_portfolio; ?>" alt="">
              </div>

              <div class="swiper-slide">
                <img src="<?= base_url('assets/img/portfolio/') . $portfolio->gambar_portfolio; ?>" alt="">
              </div>

              <div class="swiper-slide">
                <img src="<?= base_url('assets/img/portfolio/') . $portfolio->gambar_portfolio; ?>" alt="">
              </div>

            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="portfolio-info">
            <h3>Informasi Produk</h3>
            <ul>
              <li><strong>Produk</strong>: <?= $portfolio->judul_portfolio; ?></li>
              <li><strong>Kategori</strong>: <?= $portfolio->nama_layanan; ?></li>
              <li><strong>No Reg Kementan RI</strong>: <?= $portfolio->noreg_kementan; ?></li>
              <?php
              $source = $portfolio->date_project;
              $date = new DateTime($source);
              $date_project = $date->format('d F, Y');
              ?>
              <!-- <li><strong>Project date</strong>: <?= $date_project; ?></li> -->
              <li><strong>Deskripsi</strong>: <br><?= $portfolio->website_portfolio; ?></li>
              <li ><strong>Manfaat</strong>: <br><?= $portfolio->testimoni; ?></li>
              <li><strong>Perhatian: <br> OBAT HANYA UNTUK HEWAN</strong></li>
            </ul>
          </div>

          <div class="portfolio-info">
          <h3>Didistribusikan Oleh :</h3>
            <div class="row">
              <div class="col-3">
                <img style="height: 60px;" src="<?= base_url('assets/img/company/shs.png'); ?>" alt="">
              </div>
              <div class="col-9">
                <ul>
                  <li><strong>PT SHS INTERNATIONAL</strong></li>
                  <li><strong>Maspion Plaza Lt. 11</strong><br><strong>Jl. Gunung Sahari Raya Kav. 18 Jakarta 14420</strong><br><strong>Hotline : (021)-64701200</strong></li>  
                </ul>
              </div>
            </div>


          </div>
        </div>



        <div class="portfolio-description card card-info col-6">
          <h2>Informasi Detail</h2>
          <p>
            <?= $portfolio->isi_portfolio; ?>
          </p>
        </div>
        <div class="portfolio-description card card-info col-6">
          <h2>Komposisi</h2>
          <p>
            <?= $portfolio->komposisi; ?>
          </p>
        </div>

      </div>

    </div>
  </section><!-- End Portfolio Details Section -->

</main><!-- End #main -->