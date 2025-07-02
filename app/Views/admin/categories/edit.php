<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("admin_content") ?>

<div class="container py-4 max-w-2xl mx-auto">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Category</h1>
        <p class="text-muted small">Update category details</p>
    </div>

    <?= form_open('admin/categories/update/' . $category['id'], ['class' => 'needs-validation']) ?>
    <div class="mb-4">
        <label for="name" class="form-label text-sm fw-medium">Category Name</label>
        <input type="text" class="form-control form-control-lg border-0 shadow-sm" name="name" id="name"
            value="<?= esc($category['name']) ?>" placeholder="Enter category name" required>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Update Category</button>
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
        background-color: #f8f9fa;
    }

    .form-control:focus {
        background-color: #fff;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
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

    .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    [data-bs-theme="dark"] .form-control {
        background-color: #2b3035;
        border-color: #373b3e;
        color: #e9ecef;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #9ca3af !important;
    }

    [data-bs-theme="dark"] .text-gray-800 {
        color: #e9ecef !important;
    }
</style>
<?= $this->endSection() ?>