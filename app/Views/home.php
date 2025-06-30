<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>
<div class="row">
    <!-- Main Content Area -->
    <div class="col-lg-8">
        <h1 class="display-4 mb-4">Latest Posts</h1>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <h4 class="alert-heading">No Posts Yet</h4>
                <p class="mb-0">Stay tuned! Posts will appear here soon.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-4 post-card">
                    <div class="row g-0">
                        <?php if ($post['thumbnail_path']): ?>
                            <div class="col-md-4">
                                <img loading="lazy" src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                                    class="img-fluid rounded-start h-100" alt="<?= esc($post['title']) ?>"
                                    style="object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        <div class="<?= $post['thumbnail_path'] ? 'col-md-8' : 'col-12' ?>">
                            <div class="card-body">
                                <h2 class="card-title h4">
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($post['title']) ?>
                                    </a>
                                </h2>
                                <div class="card-text mb-2">
                                    <?php
                                    $preview = strip_tags($post['content']);
                                    echo esc(mb_substr($preview, 0, 200)) . '...';
                                    ?>
                                </div>
                                <div class="card-text d-flex justify-content-between align-items-center">
                                    <div class="post-meta small">
                                        <span><i class="bi bi-calendar3"></i>
                                            <?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                                        <span class="ms-3"><i class="bi bi-person"></i> <?= esc($post['username']) ?></span>
                                    </div>
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="btn btn-sm btn-outline-primary">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links('home_posts', 'bootstrap_pagination') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="sidebar-widget categories-widget">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Categories</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled categories-list mb-0">
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="<?= base_url('category/' . $category['slug']) ?>"
                                    class="text-decoration-none d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-folder me-2"></i><?= esc($category['name']) ?></span>
                                    <span class="badge bg-primary rounded-pill">
                                        <?= $categoryPostCounts[$category['name']] ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>