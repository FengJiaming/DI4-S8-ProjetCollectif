<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 27/03/2019
 * Time: 11:50
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>AdministratorPage</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href= <?php echo base_url('assets/css/Administrator/AdministratorMenu.css'); ?>>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">


</head>

<body>



<?php

// Pour liste d'emprunt : Deux onglets : un hardware, un consommable
// Plus filtre historique et en cours

// Un onglet hardware, un consommable

$borrowingList = "Listes des emprunts";
$hardwareRequest = "Demandes de matériels";
$hardwareManagement = "Gestion du matériel";
$borrowingComeback = "Retour d'emprunt";
$hardwareBorrow = "Générer un emprunt";
$csvImport = "Import CSV";
$Logout = "Déconnexion";
$userView = "Vue Utilisateur";
?>

<div id="title" class="jumbotron jumbotron-fluid mb-0">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img class="img-fluid w-50" src=<?php echo base_url('assets/images/1280-Polytech.png'); ?>>
            </div>
            <div class="col-6">
                <h1 class="text-right">Emprunt de matériels informatiques</h1>
                <p class="text-right text-monospace font-weight-bold">
                    Vue Administrateur</p>
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
                   href='<?php echo base_url(); ?>Admin/BorrowAdminController/AdministratorBorrowingList'><?php echo $borrowingList ?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>Admin/HardwareAdminController/AdministratorHardwareRequest'><?php echo $hardwareRequest ?>
                    <?php if(isset($nbUnreadRequests)&&($nbUnreadRequests!=0)){ ?>
                    <span class="badge badge-danger ml-1">
                        <?php  echo $nbUnreadRequests; ?>
                    </span>
                    <?php } ?>
                </a>

            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>Admin/HardwareAdminController/AdministratorHardwareManagement'><?php echo $hardwareManagement ?></a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                   href='<?php echo base_url(); ?>Admin/BorrowAdminController/AdministratorBorrowingComeback'><?php echo $borrowingComeback ?></a>
            </li>

        </ul>
        <a class="btn btn-light my-sm-0 mr-5"
           href='<?php echo base_url(); ?>User/ProfilUserController/userPage'><?php echo $userView ?> </a>
        <a class="btn btn-light my-sm-0 mr-5"
           href='<?php echo base_url(); ?>Admin/CSVImportAdminController/importCSV'><?php echo $csvImport ?> </a>
        <a class="btn btn-danger my-sm-0"
           href='<?php echo base_url(); ?>ConnectionController/logout'><?php echo $Logout ?> </a>
    </div>

</nav>

</body>
</html>
