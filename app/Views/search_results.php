<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>
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
        <?php if (!empty($results)): ?>
            <div class="search-results">
                <?php foreach ($results as $post): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?= base_url('post/' . $post['slug']) ?>" class="text-decoration-none">
                                    <?= highlightSearchTerms(esc($post['title']), $query) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                <?= highlightSearchTerms(esc(truncateText($post['content'], 200)), $query) ?>
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i>
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                <?php if (!empty($post['category_name'])): ?>
                                    | <i class="fas fa-folder"></i> <?= esc($post['category_name']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pager->getPageCount() > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>

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
                    </ul>
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