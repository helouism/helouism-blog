<?= $this->extend("templates/layout") ?>
<?= $this->section("content") ?>
<div class="py-5">
    <!-- About Me Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-4">About Me</h2>
            <p class="lead">Fresh graduate in Informatics Engineering from Universitas Pamulang, passionate about web
                development and software engineering.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <i class="bi bi-person-circle display-1 text-primary"></i>
        </div>
    </div>

    <!-- Education Section -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4"><i class="bi bi-mortarboard-fill me-2"></i>Education</h3>
        <div class="timeline">
            <div class="mb-4">
                <h4 class="fw-bold">Universitas Pamulang</h4>
                <p class="text-muted">Bachelor's Degree in Informatics Engineering | 2020 - 2024</p>
            </div>
            <div>
                <h4 class="fw-bold">Prima Unggul Vocational School</h4>
                <p class="text-muted">Computer Network Engineering | 2016 - 2019</p>
            </div>
        </div>
    </div>

    <!-- Experience Section -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4"><i class="bi bi-briefcase-fill me-2"></i>Experience</h3>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h4 class="fw-bold">Web Development Intern</h4>
                <p class="text-muted mb-2">Tech Company Name | Jan 2023 - Jun 2023</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i>Developed and maintained responsive
                        websites</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i>Collaborated with senior developers on
                        various projects</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i>Implemented new features and fixed bugs
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Skills Section -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4"><i class="bi bi-tools me-2"></i>Skills</h3>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <div class="p-3 border border-secondary  rounded text-center">
                    <i class="bi bi-code-slash mb-2 text-primary h4"></i>
                    <p class="mb-0">Full Stack Web Development</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-3 border border-secondary rounded text-center">
                    <i class="bi bi-router mb-2 text-primary h4"></i>
                    <p class="mb-0">Computer Networking</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-3 border border-secondary rounded text-center">
                    <i class="bi bi-pc-display mb-2 text-primary h4"></i>
                    <p class="mb-0">IT Support</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Projects Section -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4"><i class="bi bi-folder-fill me-2"></i>Projects</h3>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold">E-Commerce Website</h4>
                        <p class="text-muted mb-3">A full-stack e-commerce platform built with PHP and MySQL</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary">PHP</span>
                            <span class="badge bg-primary">MySQL</span>
                            <span class="badge bg-primary">Bootstrap</span>
                        </div>
                        <div class="mt-2">
                            <a class="btn btn-secondary" href="#">Live Demo</a>
                            <a class="btn btn-secondary" href="#">Github</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold">Task Management App</h4>
                        <p class="text-muted mb-3">A mobile-first task management application</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary">CodeIgniter</span>
                            <span class="badge bg-primary">JavaScript</span>
                            <span class="badge bg-primary">REST API</span>
                        </div>
                        <div class="mt-2">
                            <a class="btn btn-secondary" href="#">Live Demo</a>
                            <a class="btn btn-secondary" href="#">Github</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages Section -->
    <div>
        <h3 class="fw-bold mb-4"><i class="bi bi-translate me-2"></i>Languages</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 me-3">Indonesian</h5>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 me-3">English</h5>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>