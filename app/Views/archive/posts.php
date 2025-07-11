<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>

<div class="row mb-5">
    <div class="col-12">
        <h1 class="display-4 fw-bold">Archive: <?= esc($monthName) ?> <?= esc($year) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
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
                <div class="card mb-4 post-card">
                    <div class="row g-0">
                        <?php if ($post['thumbnail_path']): ?>
                            <div class="col-md-4">
                                <a href="<?= base_url('post/' . $post['slug']) ?>">
                                    <img loading="lazy" decoding="async"
                                        src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                                        class="img-fluid rounded-start h-100" alt="<?= esc($post['title']) ?>"
                                        style="object-fit: cover;">
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
                                    <a href="<?= base_url('post/' . $post['slug']) ?>" class="btn btn-sm btn-outline-primary"
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
        <!-- Categories Widget -->
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
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Archive Widget -->
        <div class="sidebar-widget archive-widget mt-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="h5 mb-0">Archive</h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="archiveAccordion">
                        <?php foreach ($archive as $archiveYear => $months): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-archive-<?= $archiveYear ?>">
                                    <button class="accordion-button <?= $archiveYear != $year ? 'collapsed' : '' ?>"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-archive-<?= $archiveYear ?>"
                                        aria-expanded="<?= $archiveYear == $year ? 'true' : 'false' ?>"
                                        aria-controls="collapse-archive-<?= $archiveYear ?>">
                                        <?= $archiveYear ?>
                                    </button>
                                </h2>
                                <div id="collapse-archive-<?= $archiveYear ?>"
                                    class="accordion-collapse collapse <?= $archiveYear == $year ? 'show' : '' ?>"
                                    aria-labelledby="heading-archive-<?= $archiveYear ?>"
                                    data-bs-parent="#archiveAccordion">
                                    <div class="accordion-body p-2">
                                        <ul class="list-unstyled mb-0">
                                            <?php foreach ($months as $monthNum => $count):
                                                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                                                $isActive = ($archiveYear == $year && $monthNum == $month); ?>
                                                <li>
                                                    <a href="<?= base_url('archive/' . $archiveYear . '/' . $monthNum) ?>"
                                                        class="text-decoration-none d-flex justify-content-between align-items-center <?= $isActive ? 'fw-bold text-primary' : '' ?>"
                                                        target="_blank">
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
    </div>
</div>

<?= $this->endSection() ?>