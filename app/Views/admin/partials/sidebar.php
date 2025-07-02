<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="<?php echo base_url("admin") ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Admin Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">Manage</div>
                <a class="nav-link" href="<?php echo base_url("admin/posts") ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Manage Posts
                </a>
                <a class="nav-link" href="<?php echo base_url("admin/categories") ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Manage Categories
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= auth()->user()->username ?>
        </div>
    </nav>
</div>