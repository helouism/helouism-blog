<!DOCTYPE html>
<html lang="en">

<?= $this->include("partials/head") ?>

<body>
    <?= $this->include("partials/navbar") ?>
    <main class="container-fluid py-5">
        <?= $this->renderSection("content") ?>
    </main>

    <?= $this->include("partials/footer") ?>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const toast = document.getElementById('searchErrorToast');
            if (toast) {
                setTimeout(() => {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000);
            }
        });
    </script>

   


     

        
   
    <?= $this->renderSection("scripts") ?>
</body>

</html>