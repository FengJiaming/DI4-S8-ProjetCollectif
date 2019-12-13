<!DOCTYPE html>
<html>
<head>
    <title>Emprunt d'un produit</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserProducts.css'); ?>>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url('assets/js/dateValidation.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dateformat.js'); ?>"></script>
</head>
<body>
<?php
include "UserMenu.php";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <table class="description">
                <thead>

                </thead>
                <tbody>
                <tr>
                    <td align="center">
                        <img class="img-fluid img-thumbnail h-25 w-25" src=<?php echo base_url('assets/images/test.jpg'); ?>>
                    </td>
                </tr>
                <tr>
                    <td class="descriptif">
                        Descriptif du produit : <br/>
                    </td>
                </tr>
                <tr>
                    <td class="description">
                        <?php
                        echo $product->description;
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <?php
            echo form_open('User/BorrowingUserController/userProducts/' . $product->id);
            ?>

            <div class="alert alert-warning" role="alert" id="alertDaysError" style="display: none;">
                Les dates sélectionnées ne sont pas valides !
            </div>

            <table class="reservation">
                <thead>
                <tr>
                    <th>
                        <?php
                        echo $product->name;
                        ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Réservation
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bootstrap-iso form-group">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon">de</div>
                                <input type="text" id="startDate" name="startDate" class="form-control" placeholder="YYYY-MM-DD">
                                <div class="input-group-addon">à</div>
                                <input type="text" id="endDate" name="endDate" class="form-control" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
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
                        echo form_submit("AddToBasket", "Ajouter au panier", 'class="btn btn-info"');
                        ?>
                        <br/>
                        <a href="<?php echo base_url('User/BorrowingUserController/userImpossibleBorrowing/'. $product->id); ?>">Produit indisponible? Cliquez ici</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
            echo form_close();

            if (isset($validation) && $validation) {
                ?>
                <!-- Modal affichee quand le produit a été ajouté au panier -->
                <div class="modal" tabindex="-1" role="dialog" id="modalSuccess">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajout réussit</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Votre produit a bien été ajouté à votre panier. </p>
                            </div>
                            <div class="modal-footer">
                                <a href="<?php echo base_url('User/BorrowingUserController/userBorrowing'); ?>"
                                   class="btn btn-info">Continuer mes emprunts</a>
                                <a href="<?php echo base_url('User/BasketUserController/userBasket'); ?>"
                                   class="btn btn-info">Voir mon panier</a>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">

                    $('#modalSuccess').modal('show');

                </script>
                <?php
            }

            ?>

            <script>

                const impossibleDays = [].concat(<?php echo json_encode($usedCalendar); ?>);
                //const impossibleDays = ["2019-05-28"];

                $(document).ready(function () {
                    $('.input-daterange').each(function () {

                        $(this).datepicker({
                            format: 'yyyy-mm-dd',
                            todayHighlight: true,
                            autoclose: true,
                            language: 'fr',
                            startDate: new Date(),
                            beforeShowDay: function (day) {
                                const dayStr = $.format.date(day, "yyyy-MM-dd");

                                if (!impossibleDays.includes(dayStr)) {
                                    return true;
                                }

                                return {
                                    enabled: false,
                                    classes: "", //additionnal classes
                                    tooltip: "Aucun produit n'est disponible pour ce jour"
                                }
                            }
                        });
                        $("#startDate").datepicker().on("changeDate", function(ev){
                            $('#endDate').datepicker("setEndDate", null);
                            //alert("Working");
                            const afterStartDate = impossibleDays.map(x=> new Date(x)).filter(d=>new Date(d) > new Date(ev.date));
                            console.log("afterDate")
                            console.log(afterStartDate);
                            if(afterStartDate.length == 0){
                                return;
                            }
                            const theDate = afterStartDate.sort()[0];
                            const dayStr = $.format.date(theDate, "yyyy-MM-dd");
                            console.log("the date");
                            console.log(dayStr);
                            $('#endDate').datepicker("setEndDate", dayStr);
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
<?php
include "UserFooter.php";
?>
</body>
</html>

