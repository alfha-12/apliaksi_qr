<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
    <p style="color:var(--text-muted);"><?= count($places) ?> tempat terdaftar</p>
    <a href="<?= e(url('admin/places/create')) ?>" class="btn btn-primary">+ Tambah Tempat</a>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Barcode</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($places)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">
                            Belum ada tempat. <a href="<?= e(url('admin/places/create')) ?>">Tambah sekarang</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($places as $p): ?>
                        <tr>
                            <td>
                                <a href="<?= e(url('admin/places/' . $p['id'])) ?>" style="font-weight:600;">
                                    <?= e($p['name']) ?>
                                </a>
                            </td>
                            <td><span class="badge badge-secondary"><?= e($p['category']) ?></span></td>
                            <td><code><?= e($p['barcode_code']) ?></code></td>
                            <td>
                                <?= starRating((float)$p['avg_rating']) ?>
                                <small><?= number_format($p['avg_rating'], 1) ?></small>
                            </td>
                            <td><?= $p['total_reviews'] ?></td>
                            <td>
                                <?php if ($p['status'] === 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="<?= e(url('admin/places/' . $p['id'])) ?>" class="btn btn-sm btn-outline">Detail</a>
                                <a href="<?= e(url('admin/places/' . $p['id'] . '/edit')) ?>" class="btn btn-sm btn-outline">Edit</a>
                                <form action="<?= e(url('admin/places/' . $p['id'] . '/delete')) ?>" method="POST" 
                                      style="display:inline;" 
                                      onsubmit="return confirm('Yakin hapus tempat ini beserta semua ulasannya?')">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
