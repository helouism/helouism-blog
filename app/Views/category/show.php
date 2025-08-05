<?= $this->extend('templates/layout') ?>
<?= $this->section('pageStyles') ?>
<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
    }
</style><?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Category Header -->
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold"><?= esc($category['name']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-decoration-none">Home</a>
                </li>
                 <li class="breadcrumb-item"><a href="<?= base_url('category-list') ?>" class="text-decoration-none">Categories</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($category['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- Posts Grid -->
<div class="row g-4">
    <?php if (empty($posts)): ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <h4 class="alert-heading">No Posts Found</h4>
                <p class="mb-0">There are currently no posts in this category.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm hover-card text-white bg-primary">
                    <?php if ($post['thumbnail_path']): ?>
                        <img loading="lazy" decoding="async" src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                            class="card-img-top" alt="<?= esc($post['title']) ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?= base_url('post/' . $post['slug']) ?>" class="text-decoration-none stretched-link text-white">
                                <?= esc($post['title']) ?>
                            </a>
                        </h5>
                        <div class="card-text mb-3">
                            <?= esc($post['meta_description']) ?>
                        </div>
                        <div class="card-text">
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i>
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div> <!-- Pagination -->
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-center">
        <?= $pager->links('category_posts', 'bootstrap_pagination') ?>
    </div>
</div>
<?= $this->endSection() ?>