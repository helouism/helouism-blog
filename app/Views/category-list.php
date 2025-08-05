<?= $this->extend("templates/layout") ?>
<?= $this->section("pageStyles") ?>
<style>
 
    .category-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        text-align: center;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        
    }

    .category-image-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.05);
    }

    .category-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg,
                rgba(0, 0, 0, 0.2) 0%,
                rgba(0, 0, 0, 0.6) 100%);
    }

    .category-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-body {
        border-radius: 0 0 0.375rem 0.375rem;
    }

    .stats-item {
        padding: 1rem 2rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }

        .stats-item {
            padding: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section("content") ?>
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 mb-3">Browse Categories</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-decoration-none">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>

    </div>
</div>
<!-- Categories Grid -->
<div class="row g-4">
    <?php if (empty($categories)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4 class="alert-heading">No Categories Found</h4>
                <p class="mb-0">Check back later for new categories!</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <?php
            $details = $categoryDetails[$category['name']];
            $postCount = $details['postCount'];
            $latestPost = $details['latestPost'];
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-primary h-100 category-card hover-card">
                    <div class="category-image-wrapper">
                        <?php if ($latestPost && $latestPost['thumbnail_path']): ?>
                            <img loading="lazy" decoding="async"
                                src="<?= base_url('uploads/thumbnails/' . $latestPost['thumbnail_path']) ?>"
                                class="card-img-top category-image" alt="<?= esc($category['name']) ?>">
                        <?php else: ?>
                            <div class="category-image-placeholder">
                                <i class="bi bi-folder2 display-4"></i>
                            </div>
                        <?php endif; ?>
                        <div class="category-overlay"></div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h4"><?= esc($category['name']) ?></h3>
                        <p class="card-text mb-4">
                            <?= $postCount ?>         <?= $postCount === 1 ? 'Post' : 'Posts' ?>
                        </p>
                        <div class="mt-auto">
                            <a href="<?= base_url('category/' . $category['slug']) ?>" class="btn stretched-link btn-dark">
                                View Posts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>