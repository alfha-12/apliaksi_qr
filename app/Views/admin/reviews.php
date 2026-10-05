<div class="card" style="padding:0; overflow:hidden;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Pengulas</th>
                    <th>Tempat</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">
                            Belum ada ulasan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $r): ?>
                        <tr>
                            <td style="font-weight:600;"><?= e($r['reviewer_name']) ?></td>
                            <td>
                                <a href="<?= e(url('admin/places/' . $r['place_id'])) ?>">
                                    <?= e($r['place_name']) ?>
                                </a>
                            </td>
                            <td><?= starRating((float)$r['rating']) ?></td>
                            <td style="max-width:250px;">
                                <?= e(mb_strimwidth($r['comment'], 0, 80, '...')) ?>
                            </td>
                            <td>
                                <?php if ($r['is_approved']): ?>
                                    <span class="badge badge-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap;font-size:0.85rem;">
                                <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <?php if (!$r['is_approved']): ?>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/approve')) ?>" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-success">✓ Approve</button>
                                    </form>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/reject')) ?>" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Tolak dan hapus ulasan ini?')">
                                        <button type="submit" class="btn btn-sm btn-danger">✗ Tolak</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= e(url('admin/reviews/' . $r['id'] . '/delete')) ?>" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Hapus ulasan ini?')">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
