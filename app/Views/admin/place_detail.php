<div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.25rem;">
    
    <!-- Info Tempat -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= e($place['name']) ?></h3>
                <div>
                    <a href="<?= e(url('admin/places/' . $place['id'] . '/edit')) ?>" class="btn btn-sm btn-outline">Edit</a>
                    <a href="<?= e(url('p/' . $place['barcode_code'])) ?>" target="_blank" class="btn btn-sm btn-primary">Lihat Halaman</a>
                </div>
            </div>
            
            <table style="width:100%;">
                <tr>
                    <td style="width:140px;color:var(--text-muted);padding:0.4rem 0;">Kategori</td>
                    <td><span class="badge badge-secondary"><?= e($place['category']) ?></span></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:0.4rem 0;">Alamat</td>
                    <td><?= e($place['address'] ?: '-') ?></td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:0.4rem 0;">Status</td>
                    <td>
                        <?php if ($place['status'] === 'active'): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:0.4rem 0;">Rating</td>
                    <td>
                        <?= starRating((float)$place['avg_rating']) ?>
                        <strong><?= number_format($place['avg_rating'], 1) ?></strong>
                        (<?= $place['total_reviews'] ?> ulasan)
                    </td>
                </tr>
                <tr>
                    <td style="color:var(--text-muted);padding:0.4rem 0;">Barcode Code</td>
                    <td><code><?= e($place['barcode_code']) ?></code></td>
                </tr>
            </table>
            
            <?php if ($place['description']): ?>
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                    <strong>Deskripsi:</strong>
                    <p style="margin-top:0.4rem;"><?= nl2br(e($place['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Reviews -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Semua Ulasan (<?= count($reviews) ?>)</h3>
            </div>
            
            <?php if (empty($reviews)): ?>
                <p style="color:var(--text-muted);">Belum ada ulasan untuk tempat ini.</p>
            <?php else: ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-author"><?= e($r['reviewer_name']) ?></span>
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <?php if (!$r['is_approved']): ?>
                                    <span class="badge badge-warning">Pending</span>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/approve')) ?>" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/reject')) ?>" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Tolak dan hapus ulasan ini?')">
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge badge-success">Approved</span>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/delete')) ?>" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Hapus ulasan ini?')">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div><?= starRating((float)$r['rating']) ?> · <span class="review-date"><?= timeAgo($r['created_at']) ?></span></div>
                        <div class="review-comment"><?= nl2br(e($r['comment'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- QR Code -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📱 QR Code / Barcode</h3>
            </div>
            <div class="qr-box">
                <!-- Menggunakan API QR Server gratis -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($qrUrl) ?>" 
                     alt="QR Code">
                <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem;">
                    Scan QR ini untuk menuju halaman ulasan
                </p>
                <div class="qr-url"><?= e($qrUrl) ?></div>
                <p style="margin-top:1rem;">
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?= urlencode($qrUrl) ?>" 
                       target="_blank" class="btn btn-sm btn-outline" download>
                        Download QR Besar
                    </a>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                        Cetak QR Code
                    </button>
                </p>
            </div>
            <div style="margin-top:1rem;padding:0.75rem;background:#f1f5f9;border-radius:8px;font-size:0.85rem;">
                <strong>Cara pakai:</strong>
                <ol style="margin:0.5rem 0 0 1.2rem;padding:0;">
                    <li>Download / print QR Code di atas</li>
                    <li>Tempel di lokasi tempat</li>
                    <li>Pengunjung scan → langsung ke halaman ulasan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<style media="print">
    .sidebar, .admin-header, .btn, .footer, .card:not(:has(.qr-box)), .qr-box ~ * { display: none !important; }
    .admin-layout, .admin-content, .admin-body { display: block !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: 0 !important; }
    .qr-box { text-align: center; }
    .qr-box img { width: 320px !important; height: 320px !important; }
</style>
