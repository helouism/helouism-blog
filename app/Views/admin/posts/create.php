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
        <h1 class="h3 mb-0 text-gray-800">Create New Post</h1>
        <p class="text-muted small">Fill in the details below to create a new blog post</p>
    </div>

    <?php echo form_open_multipart('admin/posts/store', ['class' => 'needs-validation', 'id' => 'postForm']); ?>
    <div class="mb-4">
        <?php $attributes = [
            'class' => 'form-label text-sm fw-medium',

        ];
        echo form_label('Post Title', 'title', $attributes); ?>
        <input type="text" id="title" maxlength="150"
            class="form-control form-control-lg border-0 shadow-sm <?= (validation_show_error('title')) ? 'is-invalid' : ''; ?>"
            name="title" value="<?= set_value('title') ?>" placeholder="Enter a descriptive title" required>
        <div class="invalid-feedback">
            <?= validation_show_error('title'); ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="slug" class="form-label text-sm fw-medium">Post Slug</label>
        <input type="text" id="slug" value="<?= set_value('slug') ?>"
            class="form-control form-control-lg border-0 shadow-sm  <?= (validation_show_error('slug')) ? 'is-invalid' : ''; ?>"
            name="slug" placeholder="Enter a descriptive title" required>
        <div class="invalid-feedback">
            <?= validation_show_error('slug') ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="meta_description" class="form-label text-sm fw-medium">Meta Description</label>
        <input name="meta_description" id="meta_description" value="<?= set_value('meta_description') ?>"
            maxlength="150"
            class="form-control form-control-lg border-0 shadow-sm <?= (validation_show_error('meta_description')) ? 'is-invalid' : ''; ?>"
            placeholder="Enter the meta description">
        <div class="invalid-feedback">
            <?= validation_show_error('meta_description'); ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <?php $attributes = [
                'class' => 'form-label text-sm fw-medium',
            ];
            echo form_label('Featured Image', 'thumbnail_path', $attributes); ?>
            <input type="file" name="thumbnail_path" id="thumbnail_path"
                class="form-control border-0 shadow-sm filepond <?= (validation_show_error('thumbnail_path')) ? 'is-invalid' : ''; ?>">
            <div class="invalid-feedback">
                <?= validation_show_error('thumbnail_path'); ?>
            </div>
            <div class="form-text">Recommended size: 1200x630px</div>


            <!-- Hidden input for temp file ID -->
            <input type="hidden" name="temp_file_id" id="temp_file_id" value="">

        </div>


        <div class="col-md-6">
            <label for="category_name" class="form-label text-sm fw-medium">Category</label>
            <select name="category_name" id="category_name"
                class="form-control border-0 shadow-sm <?= (validation_show_error('category_name')) ? 'is-invalid' : ''; ?>">
                <?php foreach ($categories as $category) ?>
                <option selected value="">Select a category</option>
                <option value="<?= esc($category['name']) ?>"><?= esc($category['name']) ?></option>
            </select>

            <div class="invalid-feedback">
                <?= validation_show_error('category_name'); ?>
            </div>
        </div>
    </div>


    <div class="mb-4">
        <label for="thumbnail_caption" class="form-label text-sm fw-medium">Image Caption</label>

        <input name="thumbnail_caption" id="thumbnail_caption" maxlength="200"
            placeholder="Describe your featured image"
            class="form-control border-0 shadow-sm <?= (validation_show_error('thumbnail_caption')) ? 'is-invalid' : ''; ?>"
            value="<?= set_value('thumbnail_caption') ?>" required>
        <div class="invalid-feedback">
            <?= validation_show_error('thumbnail_caption'); ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="content" class="form-label text-sm fw-medium">Content</label>
        <input type="hidden" id="content" name="content" value="<?= old('content') ?>">

        <div id="editor" class="shadow-sm rounded" style="min-height: 300px;"></div>
    </div>

    <div class="mb-4">
        <select name="status" id="status"
            class="form-control border-0 shadow-sm <?= (validation_show_error('status')) ? 'is-invalid' : ''; ?>">
            <option selected value="">Select Post Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>

        <div class="invalid-feedback">
            <?= validation_show_error('status'); ?>
        </div>

    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Publish Post</button>
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

    // Set old content if it exists (after validation errors)
    const oldContent = document.querySelector("input[name='content']").value;
    if (oldContent) {
        quill.root.innerHTML = oldContent;
    }

    quill.on('text-change', function (delta, oldDelta, source) {
        document.querySelector("input[name='content']").value = quill.root.innerHTML;
    });


    // FilePond initialization with image resize and compression
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

    // Form validation before submission
    document.getElementById('postForm').addEventListener('submit', function (e) {
        const tempFileId = document.getElementById('temp_file_id').value;

        if (!tempFileId) {
            e.preventDefault();
            alert('Please select a thumbnail image before submitting.');
            return false;
        }

        console.log('Submitting with temp_file_id:', tempFileId);
    });
</script>
<?= $this->endSection() ?>