<div class="place-hero">
    <h1><?= e($place['name']) ?></h1>
    <div class="meta">
        <span class="badge" style="background:rgba(255,255,255,0.2);color:white;"><?= e(ucfirst($place['category'])) ?></span>
        <?php if ($place['address']): ?>
            · 📍 <?= e($place['address']) ?>
        <?php endif; ?>
    </div>
    <div style="margin-top:1rem; display:flex; align-items:center; gap:0.75rem;">
        <?= starRating((float)$place['avg_rating']) ?>
        <strong style="font-size:1.25rem;"><?= number_format($place['avg_rating'], 1) ?></strong>
        <span style="opacity:0.85;">dari <?= $place['total_reviews'] ?> ulasan</span>
    </div>
</div>

<?php if ($place['description']): ?>
<div class="card">
    <p><?= nl2br(e($place['description'])) ?></p>
</div>
<?php endif; ?>

<!-- Form Ulasan -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">✍️ Berikan Ulasan Anda</h3>
    </div>
    
    <?php if ($flash = $flash ?? null): ?>
        <div class="alert alert-<?= e($flash['type']) ?>">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= e(url('p/' . $place['barcode_code'] . '/review')) ?>" method="POST">
        <div class="form-group">
            <label class="form-label">Rating Anda *</label>
            <div class="rating-input">
                <input type="radio" name="rating" value="5" id="star5" required>
                <label for="star5">★</label>
                <input type="radio" name="rating" value="4" id="star4">
                <label for="star4">★</label>
                <input type="radio" name="rating" value="3" id="star3">
                <label for="star3">★</label>
                <input type="radio" name="rating" value="2" id="star2">
                <label for="star2">★</label>
                <input type="radio" name="rating" value="1" id="star1">
                <label for="star1">★</label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="reviewer_name">Nama Anda</label>
            <input type="text" id="reviewer_name" name="reviewer_name" class="form-control" 
                   placeholder="Kosongkan untuk Anonim" maxlength="100">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="comment">Komentar / Kritik & Saran *</label>
            <textarea id="comment" name="comment" class="form-control" 
                      placeholder="Bagikan pendapat, kritik, atau saran Anda tentang tempat ini..." required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Kirim Ulasan</button>
        <p style="text-align:center;margin-top:0.75rem;font-size:0.85rem;color:var(--text-muted);">
            Ulasan akan ditampilkan setelah disetujui oleh admin.
        </p>
    </form>
</div>

<!-- Daftar Ulasan -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">💬 Ulasan Pengunjung (<?= count($reviews) ?>)</h3>
    </div>
    
    <?php if (empty($reviews)): ?>
        <div class="empty-state">
            <div class="icon">📝</div>
            <p>Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author"><?= e($review['reviewer_name']) ?></span>
                    <span class="review-date"><?= timeAgo($review['created_at']) ?></span>
                </div>
                <div><?= starRating((float)$review['rating']) ?></div>
                <div class="review-comment"><?= nl2br(e($review['comment'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<p style="text-align:center;margin-top:1rem;">
    <a href="/" class="btn btn-outline">← Kembali ke Beranda</a>
</p>
