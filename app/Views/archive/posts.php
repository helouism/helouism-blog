<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>

<div class="row mb-5">
    <div class="col-12">
        <h1 class="display-4 fw-bold">Archive: <?= esc($monthName) ?> <?= esc($year) ?></h1>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('/') ?>" class="text-decoration-none">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('archive') ?>" class="text-decoration-none">Archive</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= esc($monthName) ?> <?= esc($year) ?>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Main Content Area -->
    <div class="col-lg-8">
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <h4 class="alert-heading">No Posts Found</h4>
                <p class="mb-0">There are no posts for <?= esc($monthName) ?>     <?= esc($year) ?>.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-4 text-white bg-primary">
                    <div class="row g-0">
                        <?php if ($post['thumbnail_path']): ?>
                            <div class="col-md-4">
                                <a href="<?= base_url('post/' . $post['slug']) ?>">
                                    <img loading="lazy" decoding="async"
                                        src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                                        class="img-fluid rounded-start h-100" alt="<?= esc($post['title']) ?>"
                                        style="object-fit: contain;">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="<?= $post['thumbnail_path'] ? 'col-md-8' : 'col-12' ?>">
                            <div class="card-body">
                                <h2 class="card-title h4">
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="text-decoration-none text-white"
                                        target="_blank">
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
                <?= $pager->links('archive_posts', 'bootstrap_pagination') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
       
        <!-- Archive Widget -->
        <div class="sidebar-widget mt-4">
            <div class="card text-white bg-dark">
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
                                                        class="text-decoration-none text-white d-flex justify-content-between align-items-center">
                                                        <span><?= $monthName ?></span>
                                                        <span class="badge bg-info rounded-pill ms-2"><?= $count ?></span>
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