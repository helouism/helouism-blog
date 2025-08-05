<?= $this->extend('templates/layout') ?><?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="search-header mb-4">
            <h2>Search Results</h2>

            <?php if (!empty($query)): ?>
                <div class="alert alert-info">
                    <strong>Search query:</strong> "<?= esc($query) ?>"
                    <br>
                    <strong>Results found:</strong> <?= $total_results ?>
                    <?= $total_results === 1 ? 'post' : 'posts' ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search Results -->
        <?php if (!empty($search_results)): ?>
            <div class="search-results">
                <?php foreach ($search_results as $post): ?>
                    <div class="card mb-4 shadow-sm text-white bg-dark">
                        <div class="row g-0">
                            <!-- Thumbnail Column -->
                            <div class="col-md-3">
                                <?php if (!empty($post['thumbnail_path'])): ?>
                                    <img src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>"
                                        class="img-fluid rounded-start h-100 object-fit-contain" alt="<?= esc($post['title']) ?>"
                                        style="min-height: 200px;">
                                <?php else: ?>
                                    <div class="bg-light rounded-start h-100 d-flex align-items-center justify-content-center"
                                        style="min-height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content Column -->
                            <div class="col-md-9">
                                <div class="card-body bg-primary h-100 d-flex flex-column">
                                    <h5 class="card-title mb-3">
                                        <a href="<?= base_url('post/' . $post['slug']) ?>"
                                            class="text-decoration-none text-light">
                                            <?= highlightSearchTerms(esc(data: $post['title']), $query) ?>
                                        </a>
                                    </h5>

                                    <p class="card-text flex-grow-1">
                                        <?= highlightSearchTerms(strip_tags(truncateText($post['content'], 200)), $query) ?>
                                    </p>

                                    <div class="mt-auto">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                            <?php if (!empty($post['category_name'])): ?>
                                                | <i class="fas fa-folder me-1"></i>
                                                <span class="badge bg-secondary"><?= esc($post['category_name']) ?></span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="alert alert-warning text-center">
                <h4>No results found</h4>
                <?php if (!empty($query)): ?>
                    <p>Sorry, no posts match your search for <strong>"<?= esc($query) ?>"</strong>.</p>
                    <p>Try:</p>
                    <ul class="list-unstyled">
                        <li>• Using different keywords</li>
                        <li>• Checking your spelling</li>
                        <li>• Using more general terms</li>
                    </ul>results
                <?php else: ?>
                    <p>Please enter a search term to find posts.</p>
                <?php endif; ?>

                <a href="<?= base_url('/') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go to Homepage
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>