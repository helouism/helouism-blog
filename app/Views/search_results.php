<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>
<h1 class="mb-4">Search Results for "<?= esc($query) ?>"</h1>
<?php if (empty($results)): ?>
    <div class="alert alert-info">No results found.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($results as $post): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (!empty($post['thumbnail_path'])): ?>
                        <a href="<?= base_url('post/' . $post['slug']) ?>" target="_blank">
                            <img src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>" class="card-img-top"
                                alt="<?= esc($post['title']) ?> thumbnail" style="object-fit:cover; width:100%; height:200px;"
                                loading="lazy" decoding="async">
                        </a>
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center"
                            style="width:100%; height:200px; color:#fff;">No Image</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-title h5 mb-1">
                            <a href="<?= base_url('post/' . $post['slug']) ?>" target="_blank"><?= esc($post['title']) ?></a>
                        </h2>
                        <div class="mb-2 text-muted small">
                            By <?= esc($post['username'] ?? 'Unknown') ?> &bull;
                            <?= isset($post['created_at']) ? date('M d, Y', strtotime($post['created_at'])) : '' ?>
                        </div>
                        <p class="card-text mb-0"><?= esc(mb_substr(strip_tags($post['content']), 0, 150)) ?>...</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>