<?php

use CodeIgniter\I18n\Time; ?>
<?= $this->extend("templates/layout") ?>
<?= $this->section("pageStyles") ?>
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;

    }

    .card:hover {
        transform: translateY(-2px);

    }

    a:hover {
        text-decoration: underline !important;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .categories-widget .card {
            position: static;
            margin-top: 2rem;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section("content") ?>
<div class="row">
    <!-- Main Content Area -->
    <div class="col-lg-8">
        <h1 class="display-4 mb-4 text-white">Latest Posts</h1>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <h4 class="alert-heading">No Posts Yet</h4>
                <p class="mb-0">Stay tuned! Posts will appear here soon.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card bg-primary mb-4">
                    <div class="row g-0">
                        <?php if ($post['thumbnail_path']): ?>
                            <div class="col-md-4">
                                <a href="<?= base_url('post/' . $post['slug']) ?>" target="_blank">
                                    <img loading="lazy" decoding="async" fetchpriority=high
                                        src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                                        class="img-fluid rounded-start h-100" alt="<?= esc($post['title']) ?>"
                                        style="object-fit: contain;">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="<?= $post['thumbnail_path'] ? 'col-md-8' : 'col-12' ?>">
                            <div class="card-body">
                                <h2 class="card-title h4">
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="text-decoration-none"
                                        target="_blank">
                                        <?= esc($post['title']) ?>
                                    </a>
                                </h2>
                                <div class="card-text mb-2">
                                    <?= esc($post['meta_description']) ?>

                                </div>
                                <div class="card-text d-flex justify-content-between align-items-center">
                                    <div class="small">
                                        <span class="badge bg-light"><i class="bi bi-calendar3"></i>
                                            <?= Time::parse($post['created_at'])->humanize() ?></span>
                                        <span class="badge bg-light ms-3"><i class="bi bi-person"></i> <?= esc($post['username']) ?></span>
                                    </div>
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="btn btn-sm btn-dark"
                                        target="_blank">
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
            <div class="card text-white bg-primary">
                <div class="card-header">
                    <h3 class="h5 mb-0">Categories</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled  mb-0">
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-3">
                                <a href="<?= base_url('category/' . $category['slug']) ?>"
                                    class=" d-flex justify-content-between align-items-center text-decoration-none">
                                    <span><i class="bi bi-folder me-2"></i><?= esc($category['name']) ?></span>
                                    <span class="badge bg-light rounded-pill">
                                        <?= $categoryPostCounts[$category['id']] ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="sidebar-widget mt-4">
            <div class="card text-white bg-primary">
                <div class="card-header">
                    <h3 class="h5 mb-0">About</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">Welcome to my helouism! Here you'll find posts about various topics that interest
                        me. Feel free to
                        explore and enjoy your stay!</p>
                </div>
            </div>
        </div>

        <!-- Archive Widget -->
        <div class="sidebar-widget mt-4">
            <div class="card text-white bg-primary">
                <div class="card-header">
                    <h3 class="h5 mb-0">Post Archive</h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="archiveAccordion">
                        <?php foreach ($archive as $year => $months): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-archive-<?= $year ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-archive-<?= $year ?>" aria-expanded="false"
                                        aria-controls="collapse-archive-<?= $year ?>">
                                        <?= $year ?>
                                    </button>
                                </h2>
                                <div id="collapse-archive-<?= $year ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading-archive-<?= $year ?>" data-bs-parent="#archiveAccordion">
                                    <div class="accordion-body p-2">
                                        <ul class="list-unstyled mb-0">
                                            <?php foreach ($months as $monthNum => $count):
                                                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); ?>
                                                <li>
                                                    <a href="<?= base_url('archive/' . $year . '/' . $monthNum) ?>"
                                                        class="text-decoration-none d-flex justify-content-between align-items-center">
                                                        <span><?= $monthName ?></span>
                                                        <span class="badge bg-light rounded-pill ms-2"><?= $count ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Archive Widget -->

    </div>
</div>
<?= $this->endSection() ?>