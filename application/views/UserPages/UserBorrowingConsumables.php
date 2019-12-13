<!DOCTYPE html>
<html>
<head>
    <title>Emprunt d'un produit</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserBorrowingConsumables.css'); ?>>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url('assets/js/dateValidation.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dateformat.js'); ?>"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</head>
<body>
<?php
include "UserMenu.php";
?>

<?php
if (isset($isSent) && $isSent == TRUE) {
    ?>
    <script>
        $(document).ready(function () {
            $('#sendMessage').modal();
        });
    </script>
    <?php
}
?>

<div class="container-fluid">
    <?php
    echo validation_errors();
    ?>
    <?php
    if (isset($error_message) && !empty($error_message)) {
        ?>
        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php
    }
    echo form_open('User/BorrowingUserController/userBorrowingConsumable');
    ?>
    <table>
        <thead>

        </thead>
        <tbody>
        <tr>
            <td>
                Sujet : Demande de comsommable
            </td>
        </tr>
        <tr>
            <td>
                Numéro étudiant : <?php echo $user['id']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Nom du consommable : <br/>
                <?php
                echo form_input("consumableName", set_value("consumableName"), "required");
                ?>
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
                <!--                <div class="bootstrap-iso form-group">-->
                <div class="input-group input-daterange">
                    <div class="input-group-addon">de</div>
                    <input type="text" name="startDate" class="form-control" value="<?php set_value("startDate") ?>"
                           placeholder="YYYY-MM-DD">
                    <div class="input-group-addon">à</div>
                    <input type="text" name="endDate" class="form-control" value="<?php set_value("endDate") ?>"
                           placeholder="YYYY-MM-DD">
                </div>
                <!--                </div>-->
            </td>
        </tr>
        <tr>
            <td>
                Nombre de jour d'emprunt : <span id="remainingDays"></span>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                echo form_submit("", "Envoyer", 'class = "btn btn-info"');
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?php
    echo form_close();
    ?>

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
                    Votre demande de consommable a été ajouté à votre panier.
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

    <script>
        $(document).ready(function () {
            $('.input-daterange').each(function () {
                $(this).datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                    language: 'fr',
                    startDate: new Date(),
                });
            });
        });
    </script>
    <?php
    include "UserFooter.php";
    ?>
</body>
</html>