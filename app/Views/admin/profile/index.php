<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("adminContent") ?>
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Edit Profile
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Username and Email -->
                    <form action="<?= base_url('admin/profile/updateProfile') ?>" method="POST" id="profileForm">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person"></i> Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= esc($username) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= esc($email) ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Profile
                            </button>
                           
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- Change Password -->
                    <h6 class="mb-3">
                        <i class="bi bi-shield-lock"></i> Change Password
                    </h6>
                    <form action="<?= base_url('admin/profile/changePassword') ?>" method="POST" id="passwordForm">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">
                                Current Password
                            </label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label">
                                New Password
                            </label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" 
                                   required>
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">
                                Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" 
                                   required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-counterclockwise"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-lg">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i> Profile Information
                    </h6>
                </div>
                <div class="card-body">
                   
                    <p><strong>Username:</strong></p>
                    <p class="text-muted"><?= esc($username) ?></p>
                    
                    <p><strong>Email:</strong></p>
                    <p class="text-muted"><?= esc($email) ?></p>
                    
                    <p><strong>Role:</strong></p>
                    <p class="text-muted"><?= esc(implode(", ", $role)) ?></p>

                    <hr>
                    <p class="small text-muted">
                        Keep your profile information up to date and use a strong password to protect your account.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>