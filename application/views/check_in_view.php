<div class="container-fluid">
    <h2 class="page-title mb-4">Check-In Lokasi</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body text-center">
            <p class="card-text">Tekan tombol di bawah untuk merekam lokasi dan waktu kehadiran Anda saat ini.</p>
            
            <form id="checkInForm" action="<?= site_url('Check_In_Controller/submit_check_in') ?>" method="post">
                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
                <input type="hidden" name="location_address" id="location_address">

                <button type="submit" id="checkInButton" class="btn btn-primary btn-lg" disabled>
                    <span id="buttonSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    <span id="buttonText">📍 Dapatkan Lokasi...</span>
                </button>
            </form>
            <div id="locationStatus" class="mt-3 text-muted"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInButton = document.getElementById('checkInButton');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const addressInput = document.getElementById('location_address');
    const locationStatus = document.getElementById('locationStatus');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');

    function getLocation() {
        if (navigator.geolocation) {
            buttonSpinner.style.display = 'inline-block';
            locationStatus.textContent = 'Sedang mengambil koordinat...';
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            locationStatus.textContent = "Geolocation tidak didukung oleh browser ini.";
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        latitudeInput.value = lat;
        longitudeInput.value = lon;
        
        locationStatus.textContent = `Lokasi didapat: Lat: ${lat.toFixed(5)}, Lon: ${lon.toFixed(5)}`;
        
        // Aktifkan tombol dan ubah teksnya
        buttonText.textContent = 'Kirim Check-In Sekarang';
        checkInButton.disabled = false;
        buttonSpinner.style.display = 'none';
        
        // (Opsional) Dapatkan alamat dari koordinat
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                    locationStatus.textContent += ` | Alamat: ${data.display_name}`;
                }
            })
            .catch(err => console.warn('Gagal mendapatkan alamat:', err));
    }

    function showError(error) {
        buttonSpinner.style.display = 'none';
        buttonText.textContent = 'Gagal Mendapatkan Lokasi';
        checkInButton.disabled = true;
        let errorMessage = "Terjadi kesalahan yang tidak diketahui.";
        switch(error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = "Anda menolak permintaan untuk Geolocation. Harap izinkan akses lokasi.";
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = "Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                errorMessage = "Permintaan untuk mendapatkan lokasi pengguna timed out.";
                break;
        }
        locationStatus.textContent = errorMessage;
        alert(errorMessage);
    }

    // Panggil fungsi untuk mendapatkan lokasi saat halaman dimuat
    getLocation();
});
</script>