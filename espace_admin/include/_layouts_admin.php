<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="/espace_admin/index_admin.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    <div class="sb-sidenav-menu-heading">Interface</div>
                    <a class="nav-link" href="/espace_admin/annonce/show_annonce.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                        Annonces
                    </a>
                    <a class="nav-link" href="/espace_admin/categorie/show_categorie.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-tag"></i></div>
                        Catégories
                    </a>
                    <a class="nav-link" href="/espace_admin/membre/show_user.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Membres
                    </a>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
                    <div class="sb-sidenav-menu-heading">Communauté</div>
                    <a class="nav-link" href="/espace_admin/commentaire/show_commentaire.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-comments"></i></div>
                        Commentaires
                    </a>
                    <a class="nav-link" href="/espace_admin/membre/show_user.php">
                        <div class="sb-nav-link-icon"><i class="fa-regular fa-star-half-stroke"></i></div>
                        Notes
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Connecté comme :</div>
                <span class="text-warning"><?= $_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom'] ?></span>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
