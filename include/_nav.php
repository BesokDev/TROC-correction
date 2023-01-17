<div class="row bg-light">
    <div class="col-11 mx-auto">

        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand text-warning fw-bolder" href="/index.php">TROC</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-item nav-link" href="#">Qui sommes-nous</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-item nav-link" href="#">Contact</a>
                        </li>
                    </ul>

                    <form class="col-6">
                        <input class="form-control me-2" type="search" placeholder="Rechercher une annonce" aria-label="Search" title='Tapez "Entrée" pour rechercher'>
                    </form>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Espace membre
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php if ( ! isConnect()) : ?>
                                <li><a class="dropdown-item" href="../inscription.php">Inscription</a></li>
                                <li><a class="dropdown-item" href="../connexion.php">Connexion</a></li>
                                <?php endif ?>
                                <?php if(isAdminConnect()): ?>
                                <li><a class="dropdown-item" href="../espace_admin/index_admin.php">Espace admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif ?>
                                <?php if(isConnect()) : ?>
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="../connexion.php?action=deconnexion">Déconnexion</a></li>
                                <?php endif ?>
                            </ul>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

    </div>
</div>