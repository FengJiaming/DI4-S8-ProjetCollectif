<div id="title" class="jumbotron jumbotron-fluid mb-0">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img class="img-fluid w-50" src=<?php echo base_url('assets/images/1280-Polytech.png'); ?>>
            </div>
            <div class="col-6">
                <h1 class="text-right">Emprunt de matériels informatiques</h1>
                <p class="text-right font-weight-bold">
                    Bienvenue <?php echo $user["firstname"]; ?> <?php echo $user["lastname"]; ?></p>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>User/ProfilUserController/userPage'>Accueil</a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>User/BorrowingUserController/userBorrowing'>Liste de matériel</a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>User/BasketUserController/userBasket'>Panier</a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>User/ProfilUserController/userHistory'>Historique</a>
            </li>
        </ul>
        <?php if($user["type"] === "ADMIN") {?>
        <a class="btn btn-light my-sm-0 mr-5"
           href='<?php echo base_url(); ?>Admin/BorrowAdminController/AdministratorBorrowingList'>Vue Administrateur</a>
        <?php } ?>

        <a class="btn btn-danger my-sm-0"
           href='<?php echo base_url(); ?>ConnectionController/logout'>Déconnexion</a>
    </div>
</nav>