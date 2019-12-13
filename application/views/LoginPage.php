<?php

$login = "Numéro d'étudiant";
$Connection = "Connexion";
$StudentNumber = "Numéro étudiant";
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    include "UserPages/BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/Login.css'); ?>>
    <title>Page de connexion</title>
</head>
<body>
<h1>Accueil</h1>

<?php echo validation_errors(); ?>
<?php
if(isset($error_message) && !empty($error_message)) {
    ?>
    <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
    <?php
}
?>

<?php echo form_open(); ?>
<div class="row">
    <div class="col-lg-4">
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <?php
            echo form_label($login, 'login');
            echo form_input('login', null, 'class="form-control" required');
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
    </div>
    <div class="col-lg-4">
        <?php
        echo form_submit('Connection', $Connection, 'class = "btn btn-info"');
        echo form_close();
        ?>
    </div>
</div>
</body>

</html>
