<?= $this->extend("admin/templates/layout") ?>
<?= $this->section("admin_content") ?>

<div class="container py-4 max-w-4xl mx-auto">
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

        <?php
        $data = [
            'name' => 'title',
            'id' => 'title',
            'placeholder' => 'Enter a descriptive title',
            'maxlength' => '150',
            'class' => 'form-control form-control-lg border-0 shadow-sm',
            'value' => set_value('title'),
            'required' => true,
        ];
        echo form_input($data); ?>
    </div>

    <div class="mb-4">
        <?php $attributes = [
            'class' => 'form-label text-sm fw-medium',

        ];

        echo form_label('Meta Description', 'meta_description', $attributes); ?>

        <?php $data = [
            'name' => 'meta_description',
            'id' => 'meta_description',
            'value' => set_value('meta_description'),
            'maxlength' => '150',
            'class' => 'form-control form-control-lg border-0 shadow-sm',
            'placeholder' => 'Enter the meta description'
        ];
        echo form_input($data); ?>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <?php $attributes = [
                'class' => 'form-label text-sm fw-medium',
            ];
            echo form_label('Featured Image', 'thumbnail_path', $attributes); ?>
            <input type="file" name="thumbnail_path" id="thumbnail_path"
                class="form-control border-0 shadow-sm filepond">

            <div class="form-text">Recommended size: 1200x630px</div>

            <!-- Hidden input for temp file ID -->
            <input type="hidden" name="temp_file_id" id="temp_file_id" value="">

        </div>

        <div class="col-md-6">
            <label for="category_name" class="form-label text-sm fw-medium">Category</label>
            <?php
            $options = ['' => 'Select a category'];
            foreach ($categories as $category) {
                $options[$category['name']] = $category['name'];
            }
            echo form_dropdown('category_name', $options, set_value('category_name'), 'class="form-control border-0 shadow-sm"');
            ?>
        </div>
    </div>

    <div class="mb-4">
        <label for="thumbnail_caption" class="form-label text-sm fw-medium">Image Caption</label>
        <?php
        $data = [
            'name' => 'thumbnail_caption',
            'id' => 'thumbnail_caption',
            'placeholder' => 'Describe your featured image',
            'maxlength' => '200',
            'class' => 'form-control border-0 shadow-sm',
            'value' => set_value('thumbnail_caption'),
            'required' => true,
        ];
        echo form_input($data); ?>
    </div>

    <div class="mb-4">
        <label for="content" class="form-label text-sm fw-medium">Content</label>
        <input type="hidden" id="content" name="content" value="<?= set_value('content') ?>">
        <div id="editor" class="shadow-sm rounded" style="min-height: 300px;"><?= set_value('content') ?></div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">Publish Post</button>
        <a href="<?= base_url('admin/posts') ?>" class="btn btn-light px-4">Cancel</a>
    </div>
    </form>
</div>

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

    [data-bs-theme="dark"] .ql-toolbar.ql-snow {
        background-color: #2b3035;
        border-bottom-color: #373b3e !important;
    }

    [data-bs-theme="dark"] .ql-snow .ql-stroke {
        stroke: #e9ecef;
    }

    [data-bs-theme="dark"] .ql-snow .ql-fill {
        fill: #e9ecef;
    }

    [data-bs-theme="dark"] .ql-container.ql-snow {
        background-color: #2b3035;
        color: #e9ecef;
    }

    [data-bs-theme="dark"] .ql-editor.ql-blank::before {
        color: #6c757d;
    }
</style>

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

    // FilePond initialization with server configuration
    const pond = FilePond.create(document.querySelector('input[name="thumbnail_path"]'), {
        allowImagePreview: true,
        imagePreviewMaxHeight: 100,
        labelIdle: 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
        acceptedFileTypes: ['image/*'],
        maxFiles: 1,
        maxFileSize: '5MB',
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
            // Set the temp file ID in the hidden input
            document.getElementById('temp_file_id').value = file.serverId;
        },
        onremovefile: (error, file) => {
            // Clear the temp file ID when file is removed
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