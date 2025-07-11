<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo base_url("/") ?>">helouism</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $title === 'Home' ? 'active' : '' ?>"
                        href="<?php echo base_url("/") ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $title === 'All Categories' ? 'active' : '' ?>"
                        href="<?php echo base_url("category-list") ?>">Categories</a>
                </li>
            </ul>
            <!-- Search Form -->
            <form method="get" action="<?= base_url('search') ?>" class="d-flex" id="searchForm" role="search">
                <div class="input-group">
                    <input type="search" maxlength="150" name="q" placeholder="Search posts..." aria-label="Search"
                        class="form-control" value="<?= old('q') ?: ($query ?? '') ?>" autocomplete="off">

                    <button class="btn btn-outline-success" type="submit" aria-label="Search">
                        <i class="fas fa-search" aria-hidden="true"></i> Search
                    </button>
                </div>
            </form>

            <!-- Error Toast -->
            <?php $errors = session()->getFlashdata('search_errors'); ?>
            <?php if (!empty($errors['q'])): ?>
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
                    <div id="searchErrorToast" class="toast align-items-center text-bg-danger border-0 show" role="alert"
                        aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                        <div class="d-flex">
                            <div class="toast-body">
                                <?= esc($errors['q']) ?>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Optional: Auto-hide toast after 5 seconds
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