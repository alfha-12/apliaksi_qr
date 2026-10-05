<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $stats['total_places'] ?></div>
        <div class="stat-label">Total Tempat</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $stats['total_reviews'] ?></div>
        <div class="stat-label">Total Ulasan</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:var(--warning);"><?= $stats['pending'] ?></div>
        <div class="stat-label">Menunggu Approval</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= number_format($stats['review_stats']['avg_rating'] ?? 0, 1) ?></div>
        <div class="stat-label">Rating Rata-rata</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
    <!-- Recent Reviews -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ulasan Terbaru</h3>
            <a href="<?= e(url('admin/reviews')) ?>" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        
        <?php if (empty($recentReviews)): ?>
            <p style="color:var(--text-muted);">Belum ada ulasan.</p>
        <?php else: ?>
            <?php foreach (array_slice($recentReviews, 0, 5) as $r): ?>
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author"><?= e($r['reviewer_name']) ?></span>
                        <?php if (!$r['is_approved']): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php else: ?>
                            <span class="badge badge-success">Approved</span>
                        <?php endif; ?>
                    </div>
                    <div style="font-size:0.85rem;color:var(--text-muted);">
                        <?= e($r['place_name']) ?> · <?= starRating((float)$r['rating']) ?>
                    </div>
                    <div class="review-comment" style="font-size:0.9rem;">
                        <?= e(mb_strimwidth($r['comment'], 0, 80, '...')) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Places Overview -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tempat Terdaftar</h3>
            <a href="<?= e(url('admin/places')) ?>" class="btn btn-sm btn-outline">Kelola</a>
        </div>
        
        <?php if (empty($places)): ?>
            <p style="color:var(--text-muted);">Belum ada tempat.</p>
        <?php else: ?>
            <?php foreach (array_slice($places, 0, 5) as $p): ?>
                <div class="review-item">
                    <div class="review-header">
                        <a href="<?= e(url('admin/places/' . $p['id'])) ?>" class="review-author"><?= e($p['name']) ?></a>
                        <span class="badge badge-secondary"><?= e($p['category']) ?></span>
                    </div>
                    <div style="font-size:0.85rem;">
                        <?= starRating((float)$p['avg_rating']) ?>
                        <span style="color:var(--text-muted);"><?= $p['total_reviews'] ?> ulasan</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-top:1.25rem;">
    <div class="card" style="padding:0; overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.25rem 0;">
            <h3 class="card-title">Rekap Komentar per Bulan</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Komentar</th>
                        <th>Pending</th>
                        <th>Disetujui</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($monthlyRecap)): ?>
                        <tr><td colspan="5" style="text-align:center;padding:1.5rem;color:var(--text-muted);">Belum ada komentar.</td></tr>
                    <?php else: ?>
                        <?php foreach ($monthlyRecap as $recap): ?>
                            <tr>
                                <td><?= e($recap['period_label']) ?></td>
                                <td><?= $recap['total_comments'] ?></td>
                                <td><span class="badge badge-warning"><?= $recap['pending'] ?></span></td>
                                <td><span class="badge badge-success"><?= $recap['approved'] ?></span></td>
                                <td><?= number_format((float) $recap['average_rating'], 1) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.25rem 0;">
            <h3 class="card-title">Rekap Komentar per Tahun</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Komentar</th>
                        <th>Pending</th>
                        <th>Disetujui</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($yearlyRecap)): ?>
                        <tr><td colspan="5" style="text-align:center;padding:1.5rem;color:var(--text-muted);">Belum ada komentar.</td></tr>
                    <?php else: ?>
                        <?php foreach ($yearlyRecap as $recap): ?>
                            <tr>
                                <td><?= e((string) $recap['year']) ?></td>
                                <td><?= $recap['total_comments'] ?></td>
                                <td><span class="badge badge-warning"><?= $recap['pending'] ?></span></td>
                                <td><span class="badge badge-success"><?= $recap['approved'] ?></span></td>
                                <td><?= number_format((float) $recap['average_rating'], 1) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
