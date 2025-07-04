<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 mb-3">Browse Categories</h1>

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
                <div class="card h-100 category-card hover-card">
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
                            <a href="<?= base_url('category/' . $category['slug']) ?>" class="btn stretched-link"
                                target="_blank">
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