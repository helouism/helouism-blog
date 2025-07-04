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
            <?php
            $attributes = ['class' => 'd-flex', 'id' => 'myform', 'role' => 'search', 'method' => 'get'];
            echo form_open('search', $attributes); ?>


            <input class="form-control me-2" type="search" name="search_query" placeholder="Search"
                aria-label="Search" />
            <button class="btn btn-outline-success" type="submit">Search</button>
            <?php echo form_close(); ?>

        </div>
    </div>
</nav>