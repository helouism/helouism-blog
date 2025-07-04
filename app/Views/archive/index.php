<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>

<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold">Archive</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('/') ?>" class="text-decoration-none">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Archive</li>
            </ol>
        </nav>

    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <?php if (empty($archive)): ?>
            <div class="text-center">
                <div class="alert alert-info">
                    <h4 class="alert-heading">No Archive Data</h4>
                    <p class="mb-0">There are currently no posts in the archive.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($archive as $year => $months): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">
                            <i class="bi bi-calendar-event me-2"></i>
                            <?= esc($year) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($months as $monthNum => $count):
                                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="d-grid">
                                        <a href="<?= base_url('archive/' . $year . '/' . $monthNum) ?>"
                                            class="btn btn-outline-primary btn-lg d-flex justify-content-between align-items-center"
                                            target="_blank">
                                            <span>
                                                <i class="bi bi-folder me-2"></i>
                                                <?= esc($monthName) ?>
                                            </span>
                                            <span class="badge bg-primary rounded-pill"><?= esc($count) ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Year Summary -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Year: <?= esc($year) ?>
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-file-text me-1"></i>
                                        Total Posts: <?= array_sum($months) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



<!-- Pagination (if needed) -->
<?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            <?= $pager->links('archive', 'bootstrap_pagination') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>