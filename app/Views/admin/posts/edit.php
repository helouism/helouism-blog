<?= $this->section("pageStyles") ?>
<style>
    .max-w-4xl {
        max-width: 56rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    [data-bs-theme="dark"] .form-control {
        background-color: #2b3035;
        border-color: #373b3e;
        color: #e9ecef;
    }

    [data-bs-theme="dark"] .form-text {
        color: #9ca3af;
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
        border: 1px solid #dee2e6;
    }

    [data-bs-theme="dark"] .btn-light {
        background-color: #2b3035;
        border-color: #373b3e;
        color: #e9ecef;
    }

    [data-bs-theme="dark"] .btn-light:hover {
        background-color: #373b3e;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #9ca3af !important;
    }

    [data-bs-theme="dark"] .text-gray-800 {
        color: #e9ecef !important;
    }

    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding: 1rem !important;

        border-radius: 0.5rem 0.5rem 0 0;
    }

    .ql-container.ql-snow {
        border: none !important;
        border-radius: 0 0 0.5rem 0.5rem;
    }
</style>
<?= $this->endSection() ?>
<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("adminContent") ?>


<div class="container py-4 max-w-4xl mx-auto py-4">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Post</h1>
        <p class="text-muted small">Update your blog post details</p>
    </div>

    <?php echo form_open_multipart('admin/posts/update/' . $post['id'], ['id' => 'editPostForm']); ?>
    <div class="mb-4">
        <label for="title" class="form-label text-sm fw-medium">Post Title</label>
        <input type="text" id="title" value="<?= esc($post['title']) ?>"
            class="form-control form-control-lg border-0 shadow-sm <?= (validation_show_error('title')) ? 'is-invalid' : ''; ?>"
            name="title" placeholder="Enter a descriptive title" required>
        <div class="invalid-feedback">
            <?= validation_show_error('title'); ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="slug" class="form-label text-sm fw-medium">Post Slug</label>
        <input type="text" id="slug" value="<?= esc($post['slug']) ?>"
            class="form-control form-control-lg border-0 shadow-sm  <?= (validation_show_error('slug')) ? 'is-invalid' : ''; ?>"
            name="slug" placeholder="Enter a descriptive title" required>
        <div class="invalid-feedback">
            <?= validation_show_error('slug') ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="meta_description" class="form-label text-sm fw-medium">Meta Description</label>
        <input name="meta_description" id="meta_description" value="<?= esc($post['meta_description']) ?>"
            maxlength="150"
            class="form-control form-control-lg border-0 shadow-sm <?= (validation_show_error('meta_description')) ? 'is-invalid' : ''; ?>"
            placeholder="Enter the meta description">
        <div class="invalid-feedback">
            <?= validation_show_error('meta_description'); ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="thumbnail_path" class="form-label text-sm fw-medium">Featured Image</label>
            <?php if ($post['thumbnail_path']): ?>
                <div class="mb-2">
                    <img src="<?= base_url('uploads/thumbnails/' . $post['thumbnail_path']) ?>" class="img-thumbnail"
                        alt="Current thumbnail" style="max-height: 100px;">
                </div>
            <?php endif; ?>
            <?php
            $data = [
                'name' => 'thumbnail_path',
                'id' => 'thumbnail_path',

                'class' => 'form-control border-0 shadow-sm filepond', // add filepond class
            ];
            echo form_upload($data); ?>

            <!-- Hidden input for temp file ID -->
            <input type="hidden" name="temp_file_id" id="temp_file_id" value="">
            <div class="form-text">Recommended size: 1200x630px</div>
        </div>

        <div class="mb-4">
            <label for="thumbnail_caption" class="form-label text-sm fw-medium">Image Caption</label>

            <input name="thumbnail_caption" id="thumbnail_caption" maxlength="200"
                placeholder="Describe your featured image"
                class="form-control border-0 shadow-sm <?= (validation_show_error('thumbnail_caption')) ? 'is-invalid' : ''; ?>"
                value="<?= esc($post['thumbnail_caption']) ?>" required>
            <div class="invalid-feedback">
                <?= validation_show_error('thumbnail_caption'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <label for="category_name" class="form-label text-sm fw-medium">Category</label>
            <select name="category_name" id="category_name"
                class="form-control border-0 shadow-sm <?= (validation_show_error('category_name')) ? 'is-invalid' : ''; ?>">
                <?php foreach ($categories as $category) ?>
                <option value="">Select a category</option>
                <option value="<?= esc($category['name']) ?>"><?= esc($category['name']) ?></option>
            </select>

            <div class="invalid-feedback">
                <?= validation_show_error('category_name'); ?>
            </div>
        </div>
    </div>

    <input type="hidden" name="content" value="<?= set_value($post['content'], $post['content'], true) ?>">

    <div class="mb-4">
        <label class="form-label text-sm fw-medium">Content</label>
        <div id="editor" class="shadow-sm rounded" style="min-height: 300px;">
            <?= set_value($post['content'], $post['content'], false) ?>
        </div>
    </div>

    <div class="mb-4">
        <select name="status" id="status"
            class="form-control border-0 shadow-sm <?= (validation_show_error('status')) ? 'is-invalid' : ''; ?>">
            <option value="">Select Post Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>

        <div class="invalid-feedback">
            <?= validation_show_error('status'); ?>
        </div>

    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Update Post</button>
        <a href="<?= base_url('admin/posts') ?>" class="btn btn-light px-4">Cancel</a>
    </div>
    </form>
</div>



<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {

            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                ['blockquote', 'code-block'],
                ['link', 'image', 'video', 'formula'],

                [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
                [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
                [{ 'direction': 'rtl' }],                         // text direction

                [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                [{ 'font': [] }],
                [{ 'align': [] }],

                ['clean']
            ]
        },
    });

    quill.on('text-change', function (delta, oldDelta, source) {
        document.querySelector("input[name='content']").value = quill.root.innerHTML;
    });

    // FilePond initialization with server configuration for edit
    const pond = FilePond.create(document.querySelector('input[name="thumbnail_path"]'), {
        allowImagePreview: true,
        imagePreviewMaxHeight: 100,
        labelIdle: 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
        acceptedFileTypes: ['image/*'],
        maxFiles: 1,
        maxFileSize: '5MB',


        // Keep only basic transform for preview
        imageTransformOutputMimeType: 'image/jpeg',
        imageTransformOutputQuality: 0.9, // Higher quality for preview

        server: {
            url: '<?= base_url('admin/upload') ?>',
            process: '/process',
            revert: '/revert',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        },
        onprocessfile: (error, file) => {
            if (error) {
                console.error('Upload error:', error);
                return;
            }
            console.log('File uploaded successfully:', file.serverId);
            document.getElementById('temp_file_id').value = file.serverId;
        },
        onremovefile: (error, file) => {
            document.getElementById('temp_file_id').value = '';
        },
        onprocessfilestart: (file) => {
            console.log('Upload started for:', file.filename);
        },
        onprocessfileprogress: (file, progress) => {
            console.log('Upload progress:', Math.round(progress * 100) + '%');
        }
    });

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function (e) {
        // Get FilePond files
        const pondFiles = pond.getFiles();

        if (pondFiles.length > 0 && pondFiles[0].serverId) {
            // Get the server file ID (only for newly uploaded files)
            const serverFileId = pondFiles[0].serverId;

            // Create a hidden input with the server file ID
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'temp_file_id';
            hiddenInput.value = serverFileId;

            this.appendChild(hiddenInput);
        }
    });
</script>
<?= $this->endSection() ?>