<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("adminContent") ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Blog Posts</h1>
            <p class="text-muted mb-0">Manage your blog posts</p>
        </div>
        <a href="<?php echo base_url('admin/posts/create') ?>" class="btn btn-success">
            <i class="bi bi-plus-lg me-2"></i>New Post
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Post Table
        </div>
        <div class="card-body p-3">
            <table class="table table-hover mb-0" id="datatablesSimple">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Title</th>
                        <th class="px-4">Status </th>
                        <th class="px-4">Category </th>
                        <th class="text-end px-4" style="width: 200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $key => $post): ?>

                        <tr>
                            <td class="px-4"><?php echo $post['title'] ?></td>
                            <td class="px-4"><?php echo $post['status'] ?></td>
                            <td class="px-4"><?php echo $post['category_id'] ?></td>
                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="<?php echo base_url('admin/posts/edit/' . $post['id']) ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <a href="<?php echo base_url('admin/posts/delete/' . $post['id']) ?>"
                                        class="btn btn-sm btn-danger"
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
</div>

<?= $this->endSection() ?>