<div class="login-page">
    <div class="login-card">
        <h1>🔐 Login Admin</h1>
        <p class="subtitle">Barcode Review System</p>
        
        <?php if ($flash = $flash ?? null): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= e(url('admin/login')) ?>">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Masukkan username" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>
        
        <p style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:var(--text-muted);">
            Default: <code>admin</code> / <code>admin123</code>
        </p>
    </div>
</div>
