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

                <?php if (auth()->loggedIn()): ?>


                    <li class="nav-item">
                        <a class="nav-link <?= $title === 'Admin Dashboard' ? 'active' : '' ?>"
                            href="<?php echo base_url("admin") ?>">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $title === 'Manage Posts' ? 'active' : '' ?>"
                            href="<?php echo base_url("admin/posts") ?>">Manage Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $title === 'Manage Categories' ? 'active' : '' ?>"
                            href="<?php echo base_url("admin/categories") ?>">Manage Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url("logout") ?>">Logout</a>
                    </li>

                <?php endif ?>






        </div>
    </div>
</nav>