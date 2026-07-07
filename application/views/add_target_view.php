<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?? 'Tambah Target Baru' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><?= $page_title ?? 'Tambah Target Baru' ?></h3>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('Admin_Controller/create_target_action') ?>" method="post">
                <div class="mb-3">
                    <label for="id_user" class="form-label">Pilih User</label>
                    <select name="id_user" id="id_user" class="form-select" required>
                        <option value="">-- Pilih User --</option>
                        <?php foreach ($users_without_target as $user): ?>
                            <option value="<?= $user['id_user'] ?>">
                                <?= htmlspecialchars($user['username']) ?> - (<?= htmlspecialchars($user['caption']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($users_without_target)): ?>
                        <div class="form-text text-warning mt-2">
                            Semua user sudah memiliki target.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="target" class="form-label">Nilai Target</label>
                    <input type="number" name="target" id="target" class="form-control" placeholder="Masukkan angka target" required>
                </div>
                
                <div class="mb-3">
                    <label for="vip_target" class="form-label">Nilai VIP Target</label>
                    <input type="number" name="vip_target" id="vip_target" class="form-control" placeholder="Masukkan angka VIP target" required>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai Efektif</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>" required>
                    <div class="form-text">Tanggal ini akan menjadi awal dari riwayat target.</div>
                </div>

                <button type="submit" class="btn btn-primary" <?= empty($users_without_target) ? 'disabled' : '' ?>>
                    Simpan Target
                </button>
                <a href="<?= site_url('Admin_Controller/list_data/target') ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>