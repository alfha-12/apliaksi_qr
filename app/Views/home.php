<div style="text-align:center; margin-bottom:2rem;">
    <h1 style="font-size:1.75rem; margin-bottom:0.5rem;">📍 Daftar Tempat</h1>
    <p style="color:var(--text-muted);">Scan barcode di lokasi untuk memberikan ulasan, atau jelajahi daftar di bawah.</p>
</div>

<?php if (empty($places)): ?>
    <div class="empty-state">
        <div class="icon">📭</div>
        <p>Belum ada tempat yang terdaftar.</p>
    </div>
<?php else: ?>
    <div class="place-grid">
        <?php foreach ($places as $place): ?>
            <div class="place-card">
                <?php if (!empty($place['image'])): ?>
                    <img src="<?= e(asset('uploads/' . $place['image'])) ?>" alt="<?= e($place['name']) ?>" class="place-card-img" style="object-fit:cover;">
                <?php else: ?>
                    <div class="place-card-img">📍</div>
                <?php endif; ?>
                <div class="place-card-body">
                    <div class="place-card-title"><?= e($place['name']) ?></div>
                    <div class="place-card-meta">
                        <span class="badge badge-secondary"><?= e(ucfirst($place['category'])) ?></span>
                        <?php if ($place['address']): ?>
                            · <?= e(mb_strimwidth($place['address'], 0, 40, '...')) ?>
                        <?php endif; ?>
                    </div>
                    <div class="place-card-rating">
                        <?= starRating((float)$place['avg_rating']) ?>
                        <span class="rating-number"><?= number_format($place['avg_rating'], 1) ?></span>
                        <span style="color:var(--text-muted);font-size:0.85rem;">(<?= $place['total_reviews'] ?> ulasan)</span>
                    </div>
                    <a href="<?= e(url('p/' . $place['barcode_code'])) ?>" class="btn btn-primary btn-sm btn-block">
                        Lihat Ulasan & Nilai
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
