<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("admin_content") ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Categories</h1>
            <p class="text-muted mb-0">Manage your blog categories</p>
        </div>
        <a href="<?php echo base_url('admin/categories/create') ?>" class="btn btn-success">
            <i class="bi bi-plus-lg me-2"></i>New Category
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Category Table
        </div>
        <div class="card-body p-3">
            <table class="table table-hover mb-0" id="datatablesSimple">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Category Name</th>
                        <th class="text-end px-4" style="width: 200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $key => $category): ?>

                        <tr>
                            <td class="px-4"><?php echo $category['name'] ?></td>
                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="<?php echo base_url('admin/categories/edit/' . $category['id']) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <a href="<?php echo base_url('admin/categories/delete/' . $category['id']) ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
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
        <?php echo $pager->links('category', 'bootstrap_pagination') ?>
    </div>
</div>
<?= $this->endSection() ?>