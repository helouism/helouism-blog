<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?= $this->include("partials/head") ?>

<body>
    <?= $this->include("partials/navbar") ?>
    <main class="container-fluid py-5">
        <?= $this->renderSection("content") ?>
    </main>

    <?= $this->include("partials/footer") ?>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <?php echo script_tag('assets/js/theme-toggle.js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('searchErrorToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>


    <?= $this->renderSection("pageScripts") ?>
</body>

</html>