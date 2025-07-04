<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>
<h1 class="mb-4">Search Results for "<?= esc($query) ?>"</h1>
<?php if (empty($results)): ?>
    <div class="alert alert-info">No results found.</div>
<?php else: ?>
    <?php foreach ($results as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="card-title h5">
                    <a href="<?= base_url('post/' . $post['slug']) ?>"><?= esc($post['title']) ?></a>
                </h2>
                <p class="card-text"><?= esc(mb_substr(strip_tags($post['content']), 0, 150)) ?>...</p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>