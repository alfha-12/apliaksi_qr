<?php $isEdit = !empty($place); ?>

<div class="card" style="max-width:700px;">
    <form method="POST" 
          action="<?= e(url($isEdit ? 'admin/places/' . $place['id'] . '/edit' : 'admin/places/create')) ?>"
          enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="form-label" for="name">Nama Tempat *</label>
            <input type="text" id="name" name="name" class="form-control" required
                   value="<?= e($place['name'] ?? '') ?>" placeholder="Contoh: Warung Makan Sederhana">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="category">Kategori</label>
            <select id="category" name="category" class="form-control">
                <?php foreach ($categories as $key => $label): ?>
                    <option value="<?= e($key) ?>" 
                        <?= ($place['category'] ?? '') === $key ? 'selected' : '' ?>>
                        <?= e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="address">Alamat</label>
            <input type="text" id="address" name="address" class="form-control"
                   value="<?= e($place['address'] ?? '') ?>" placeholder="Jl. Contoh No. 123">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control"
                      placeholder="Deskripsi singkat tentang tempat ini..."><?= e($place['description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="image">Gambar (opsional, max 2MB)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <?php if (!empty($place['image'])): ?>
                <p style="margin-top:0.5rem;font-size:0.85rem;color:var(--text-muted);">
                    Gambar saat ini: <?= e($place['image']) ?>
                </p>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="active" <?= ($place['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="inactive" <?= ($place['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
        
        <?php if ($isEdit): ?>
            <div class="form-group">
                <label class="form-label">Kode Barcode</label>
                <input type="text" class="form-control" value="<?= e($place['barcode_code']) ?>" disabled>
                <small style="color:var(--text-muted);">Kode barcode tidak dapat diubah.</small>
            </div>
        <?php endif; ?>
        
        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Tempat' ?>
            </button>
            <a href="<?= e(url('admin/places')) ?>" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
