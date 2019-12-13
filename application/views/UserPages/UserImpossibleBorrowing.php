<!DOCTYPE html>
<html>
<head>
    <title>Emprunt impossible</title>
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

echo form_open('User/BorrowingUserController/userImpossibleBorrowing/'.$product->id);
?>

<div class="container-fluid">
    <table>
        <thead>

        </thead>
        <tbody>
        <tr>
            <td>
                Sujet : Impossible d'emprunter
            </td>
        </tr>
        <tr>
            <td>
                Numéro étudiant : <?php echo $user['id']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Matériel : <?php echo $product->name; ?>
            </td>
        </tr>
        <tr>
            <td>
                Commentaire : <br/>
                <?php
                echo form_textarea("Commentaries", null, "required");
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
                    <h5 class="modal-title" id="exampleModalLongTitle">Mail envoyé</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Un mail avec le commentaire que vous avez écrit a été envoyé au service Informatique.
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
