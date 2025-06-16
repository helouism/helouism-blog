<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Blog Posts</h1>
        <p class="text-muted mb-0">Manage your blog posts</p>
    </div>
    <a href="<?php echo base_url('admin/posts/create') ?>" class="btn btn-success">
        <i class="bi bi-plus-lg me-2"></i>New Post
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4">Title</th>
                    <th class="text-end px-4" style="width: 200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $key => $post): ?>

                    <tr>
                        <td class="px-4"><?php echo $post['title'] ?></td>
                        <td class="text-end px-4">
                            <div class="btn-group">
                                <a href="<?php echo base_url('admin/posts/edit/' . $post['id']) ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                <a href="<?php echo base_url('admin/posts/delete/' . $post['id']) ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    <?php echo $pager->links('post', 'bootstrap_pagination') ?>
</div>

<?= $this->endSection() ?>