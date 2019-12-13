<!DOCTYPE html>
<html>
<head>
    <title>Demande d'un produit</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserImpossibleBorrowing.css'); ?>>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</head>
<body>
<?php
include "UserMenu.php";
if (isset($isSent) && $isSent == TRUE) {
    ?>
    <script>
        $(document).ready(function () {
            $('#sendMessage').modal();
        });
    </script>
    <?php
}

echo form_open('User/BorrowingUserController/userBorrowingNewProduct');
?>
<div class="container-fluid">
    <?php echo validation_errors(); ?>
    <?php
    if(isset($error_message) && !empty($error_message)) {
        ?>
        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php
    }
    ?>
    <table>
        <thead>

        </thead>
        <tbody>
        <tr>
            <td>
                Sujet : Demande de nouveau produit
            </td>
        </tr>
        <tr>
            <td>
                Numéro étudiant : <?php echo $user['id']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Matériel : <br/>
                <?php echo form_input('productName', set_value("productName"), "required") ?>
            </td>
        </tr>
        <tr>
            <td>
                Commentaire : <br/>
                <?php
                echo form_textarea("comment", set_value("comment"), "required");
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                echo form_submit("", "Envoyer", 'class = "btn btn-info"');
                echo form_close();
                ?>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="modal fade" id="sendMessage" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Demande envoyée</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Votre demande de nouveau matériel a été envoyée au service Informatique.
                </div>
                <div class="modal-footer">
                    <a href="<?php echo base_url('User/BorrowingUserController/userBorrowing'); ?>"
                       class="btn btn-info">Retour à la liste du matériel</a>
                    <a href="<?php echo base_url('User/BasketUserController/userBasket'); ?>"
                       class="btn btn-info">Voir mon panier</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    include "UserFooter.php";
    ?>
</body>
</html>