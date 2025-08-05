<?= $this->section("pageStyles") ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
    integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
<style>
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 300px;
        overflow-y: auto;
    }

    .max-w-4xl {
        max-width: 56rem;
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

    .btn-light {
        border: 1px solid #dee2e6;
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

    <form action="<?= base_url('admin/posts/store') ?>" autocomplete="off" id="addPostForm" method="post"
        enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data" />
        <div class="mb-4">
            <?php $attributes = [
                'class' => 'form-label text-sm fw-medium',

            ];
            echo form_label('Post Title', 'title', $attributes); ?>
            <input type="text" id="title" maxlength="255" class="form-control form-control-lg border-0 shadow-sm"
                name="title" placeholder="Enter a descriptive title" required>
            <span class="text-danger error-text title_error"></span>
        </div>

        <div class="mb-4">
            <label for="slug" class="form-label text-sm fw-medium">Post Slug</label>
            <input type="text" id="slug" class="form-control form-control-lg border-0 shadow-sm" name="slug"
                placeholder="Enter a descriptive title" required>
            <span class="text-danger error-text slug_error"></span>
        </div>

        <div class="mb-4">
           <div class="form-floating">
            <textarea name="meta_description" id="meta_description" maxlength="255" style="height: 100px"
                class="form-control border-0 shadow-sm" placeholder="Enter the meta description"></textarea>
                 <label for="meta_description">Meta Description</label>
                  <span class="text-danger error-text meta_description_error"></span>
                 </div>
           
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <?php $attributes = [
                    'class' => 'form-label text-sm fw-medium',
                ];
                echo form_label('Featured Image', 'thumbnail_path', $attributes); ?>
                <input type="file" name="thumbnail_path" id="thumbnail_path" class="form-control-file form-control"
                    height="auto">
                <span class="text-danger error-text thumbnail_path_error"></span>
                <div class="d-block mb-3" style="max-width: 250px">
                    <img src="" class="img-thumbnail" alt="Thumbnail Preview" id="image-previewer"
                        style="width: 100%; height: auto; display: none;">
                </div>
            </div>

            <div class="col-md-6">
                <label for="category_id" class="form-label text-sm fw-medium">Category</label>
                <select name="category_id" id="category_id" class="form-control border-0 shadow-sm">
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= esc($category->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger error-text category_id_error"></span>
            </div>
        </div>

        <div class="mb-4">
            <label for="thumbnail_caption" class="form-label text-sm fw-medium">Image Caption</label>
            <input name="thumbnail_caption" id="thumbnail_caption" maxlength="255"
                placeholder="Describe your featured image" class="form-control form-control-lg border-0 shadow-sm"
                required>
            <span class="text-danger error-text thumbnail_caption_error"></span>
        </div>


        <div class="mb-4">
            <label for="content" class="form-label text-sm fw-medium">Content</label>


            <textarea id="content" name="content"></textarea>
            <span class="text-danger error-text content_error"></span>
        </div>

        <div class="mb-4">
            <?php
            $options = [
                '' => 'Select Post Status',
                'published' => 'Published',
                'draft' => 'Draft'
            ];
            $class = 'form-control border-0 shadow-sm';
            $extra = [
                'id' => 'status',
                'class' => $class
            ];
            echo form_dropdown('status', $options, 'draft', $extra);
            ?>
            <span class="text-danger error-text status_error"></span>

        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Publish Post</button>
            <a href="<?= base_url('admin/posts') ?>" class="btn btn-light px-4">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"
    integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.umd.js"></script>

</body>

<script>
    $(document).ready(function () {
        // Initialize CKEditor

        const {
            ClassicEditor,
            Autoformat,
            Alignment,
            Font,
            Bold,
            Italic,
            Underline,
            BlockQuote,
            Base64UploadAdapter,
            Code,
            CodeBlock,
            Essentials,
            Heading,
            Image,
            ImageCaption,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            PictureEditing,
            Indent,
            IndentBlock,
            Link,
            List,
            MediaEmbed,
            Mention,
            Paragraph,
            PasteFromOffice,
            RemoveFormat,
            Table, TableProperties, TableCellProperties,
            TableColumnResize,
            TableToolbar,
            TableCaption,
            TextTransformation
        } = CKEDITOR;
        // Create a free account and get <YOUR_LICENSE_KEY>
        // https://portal.ckeditor.com/checkout?plan=free
        ClassicEditor
            .create(document.querySelector('#content'), {
                licenseKey: '<?= $ckEditorLicenseKey ?>',
                plugins: [
                    Alignment,
                    Autoformat,
                    BlockQuote,
                    Bold,
                    Code,

                    CodeBlock,
                    Essentials,
                    Font,
                    Heading,
                    Image,
                    ImageCaption,
                    ImageResize,
                    ImageStyle,
                    ImageToolbar,
                    ImageUpload,
                    Base64UploadAdapter,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    List,
                    MediaEmbed,
                    Mention,
                    Paragraph,
                    PasteFromOffice,
                    PictureEditing,
                    RemoveFormat,
                    Table,
                    TableCellProperties, TableProperties,
                    TableColumnResize,
                    TableToolbar,
                    TableCaption,
                    TextTransformation,
                    Underline],

                toolbar: [

                    'undo',
                    'redo',
                    '|',
                    'heading', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'link',
                    'uploadImage',
                    'insertTable',
                    'blockQuote',
                    'mediaEmbed',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    'code',
                    'codeblock',
                    'removeFormat',
                    'alignment'
                ],
                heading: {
                    options: [
                        {
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        }
                    ]
                },
                image: {
                    resizeOptions: [
                        {
                            name: 'resizeImage:original',
                            label: 'Default image width',
                            value: null
                        },
                        {
                            name: 'resizeImage:50',
                            label: '50% page width',
                            value: '50'
                        },
                        {
                            name: 'resizeImage:75',
                            label: '75% page width',
                            value: '75'
                        }
                    ],
                    toolbar: [
                        'imageTextAlternative',
                        'toggleImageCaption',
                        '|',
                        'imageStyle:inline',
                        'imageStyle:wrapText',
                        'imageStyle:breakText',
                        '|',
                        'resizeImage'
                    ]
                },
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://'
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'toggleTableCaption', 'tableProperties', 'tableCellProperties']
                    ,
                    tableProperties: {
                        // The default styles for tables in the editor.
                        // They should be synchronized with the content styles.
                        defaultProperties: {
                            borderStyle: 'dashed',
                            borderColor: 'hsl(90, 75%, 60%)',
                            borderWidth: '3px',
                            alignment: 'left',
                            width: '550px',
                            height: '450px'
                        },
                    },
                    // The default styles for table cells in the editor.
                    // They should be synchronized with the content styles.
                    tableCellProperties: {
                        defaultProperties: {
                            horizontalAlignment: 'center',
                            verticalAlignment: 'bottom',
                            padding: '10px'
                        }
                    }
                },
                alignment: {
                    options: ['left', 'center', 'right', 'justify']
                },
                fontFamily: {
                    options: [

                    ],
                    supportAllValues: true
                },

                codeBlock: {
                    languages: [
                        // Do not render the CSS class for the plain text code blocks.
                        { language: 'plaintext', label: 'Plain text' }, // The default language.
                        { language: 'bash', label: 'Bash' },
                        { language: 'c', label: 'C' },
                        { language: 'cs', label: 'C#' },
                        { language: 'cpp', label: 'C++' },
                        { language: 'css', label: 'CSS' },
                        { language: 'diff', label: 'Diff' },
                        { language: 'go', label: 'Go' },
                        { language: 'html', label: 'HTML' },
                        { language: 'java', label: 'Java' },
                        { language: 'javascript', label: 'JavaScript' },
                        { language: 'json', label: 'Json' },
                        { language: 'markdown', label: 'Markdown' },

                        { language: 'php', label: 'PHP' },
                        { language: 'python', label: 'Python' },
                        { language: 'ruby', label: 'Ruby' },
                        { language: 'typescript', label: 'TypeScript' },
                        { language: 'xml', label: 'XML' }
                    ]
                }

            }
            )
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error.stack);
            });





        $("#thumbnail_path").on("change", function () {
            var file = this.files[0];
            if (file) {
                // Validate file type
                if (!file.type.match("image.*")) {
                    toastr.error("Please select an image file");
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#image-previewer")
                        .attr("src", e.target.result)
                        .css("display", "block");
                };
                reader.readAsDataURL(file);
            } else {
                $("#image-previewer").attr("src", "").css("display", "none");
            }
        });

        $("#addPostForm").on("submit", function (e) {
            e.preventDefault();
            var csrfName = $(".ci_csrf_data").attr("name");
            var csrfHash = $(".ci_csrf_data").val();
            // Get content from Toast UI Editor
            var content = editor.getData();
            var form = this;
            var formData = new FormData(form);

            formData.append(csrfName, csrfHash);
            formData.append("content", content);

            $.ajax({
                url: $(form).attr("action"),
                method: $(form).attr("method"),
                data: formData,

                contentType: false,
                processData: false,
                dataType: "json",

                beforeSend: function () {
                    toastr.remove();
                    $(form).find("span.error-text").text("");
                },
                success: function (response) {
                    // Update CSRF HASH
                    $(".ci_csrf_data").val(response.token);

                    if ($.isEmptyObject(response.error)) {
                        if (response.status == 1) {
                            editor.setData('');
                            $(form)[0].reset();
                            $("img#image-previewer").attr("src", "");
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg);
                        }
                    } else {
                        // Display validation errors
                        $.each(response.error, function (prefix, val) {
                            $(form)
                                .find("span." + prefix + "_error")
                                .text(val);
                        });
                    }
                },
            });
        });
    });

</script>



</script>
<?= $this->endSection() ?>