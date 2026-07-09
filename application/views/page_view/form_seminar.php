<?php if (
    $this->session->flashdata('success')
): ?>
    <div style="color: green; margin-bottom: 10px;">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('FormSeminarController/save') ?>" method="post">
    <input type="text" name="entry_log_id" placeholder="Entry Log ID" required><br><br>
    <input type="text" name="lokasi_seminar" placeholder="Lokasi Seminar" required><br><br>
    <input type="text" name="pemilik_tempat_seminar" placeholder="Pemilik Tempat" required><br><br>
    <input type="text" name="materi_seminar" placeholder="Materi Seminar" required><br><br>
    <select name="kategori_tempat_seminar" required>
        <option value="">Pilih Kategori Tempat</option>
        <option value="Poultry Shop">Poultry Shop</option>
        <option value="Farm">Farm</option>
    </select><br><br>
    <select name="persentase_pakan_cp_seminar" required>
        <option value="">Pilih Persentase Pakan</option>
        <option value="Pengguna pakan CP > 70%">Pengguna pakan CP > 70%</option>
        <option value="Pengguna pakan CP < 60%">Pengguna pakan CP </option>
    </select><br><br>
    <button type="submit">Simpan</button>
</form>