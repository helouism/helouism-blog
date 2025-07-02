<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("admin_content") ?>


<div class="container py-4 max-w-2xl mx-auto py-4">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Category</h1>
        <p class="text-muted small">Add a new category to organize your posts</p>
    </div>

    <?php echo form_open('admin/categories/store', ['class' => 'needs-validation']); ?>
    <div class="mb-4">
        <label for="name" class="form-label text-sm fw-medium">Category Name</label>
        <input type="text" class="form-control form-control-lg border-0 shadow-sm" name="name" id="name"
            placeholder="Enter category name" required>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Create Category</button>
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-light px-4">Cancel</a>
    </div>
    </form>
</div>

<style>
    .max-w-2xl {
        max-width: 42rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }


    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }
</style>

<?= $this->endSection() ?>