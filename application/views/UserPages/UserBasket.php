<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Panier</title>
    <?php
    include "BaseHeader.php";
    ?>

    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserBasket.css'); ?>>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script src="<?php echo base_url('assets/js/dateformat.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dateValidation.js'); ?>"></script>
</head>
<body>
<?php
include "UserMenu.php";
?>
<h2 class="display-5 text-center mb-4">Votre panier :</h2>
<hr>
<table id="borrows">
    <tbody>
    <?php
    if (empty($basket)) {
        ?>
        <tr>
            <td align="center">Aucun élément</td>
        </tr>
        <?php
    }
    foreach ($basket as $key => $basketLine):
        $isConsumable = !isset($basketLine->idProduct);
        $designation = $isConsumable ? $basketLine->designation : $basketLine->product->name;
        ?>
        <tr id="line<?php echo $key; ?>">
            <td align="center">
                <img class="img-fluid img-thumbnail h-25 w-25" src=<?php echo base_url('assets/images/test.jpg'); ?>>
            </td>
            <td align="center">
                <?php echo $designation; ?><br/>
                <?php
                if (!$isConsumable) {
                    ?>
                    <p data-toggle="tooltip" data-placement="left"
                       title="<?php echo $basketLine->product->description; ?>">
                        <?php
                        echo $basketLine->product->description;
                        ?>
                    </p>
                    <?php
                }
                else{
                    ?>
                    <p data-toggle="tooltip" data-placement="left"
                       title="<?php echo $basketLine->userComment; ?>">
                        <?php
                        echo $basketLine->userComment;
                        ?>
                    </p>
                    <?php
                }
                ?>
            </td>
            <td align="center">
                Date d'emprunt : <span
                        id="startDateLine<?php echo $basketLine->id; ?>"><?php echo $basketLine->startDate; ?></span><br/>
                Date de retour : <span
                        id="endDateLine<?php echo $basketLine->id; ?>"><?php echo $basketLine->endDate; ?></span><br/>
                Nombre de jours : <span
                        id="remainingDaysLine<?php echo $basketLine->id; ?>"><?php echo date_diff(new DateTime($basketLine->startDate), new DateTime($basketLine->endDate))->days; ?></span>
            </td>
            <td>
                <button type="button" class="btn btn-info"
                        onclick="updateBasket( <?php echo $basketLine->id; ?>, <?php echo $basketLine->idProduct; ?>)">
                    Modifier
                </button>

                <button type="button" class="btn btn-info"
                        onclick="deleteBasketLine(<?php echo $basketLine->id; ?>, <?php echo $key; ?>)">
                    Supprimer
                </button>
            </td>
        </tr>
    <?php
    endforeach;
    ?>

    </tbody>
</table>
<br/>

<hr>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <br/>
        </div>

        <div class="col-md-8">
            <div class="form-check">
                <input type="checkbox" name="basketCondition" id="basketCondition" value="accept"
                       class="form-check-input" <?php echo(empty($basket) ? "disabled" : ""); ?>/>
                <label class="form-check-label" for="basketCondition">
                    Je m'engage à avoir lu les
                    <a data-target="#conditionModal" data-toggle="modal" class="MainNavText" id="MainNavHelp"
                                                  href="#conditionModal">conditions d'emprunt</a>
                    de matériels informatiques et à le restituer dans les conditions inscrites.
                </label>

                <div class="invalid-feedback">
                    Vous devez accepter les conditions d'emprunt.
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-info"
                    onclick="checkBasketCondition()" <?php echo(empty($basket) ? "disabled" : ""); ?>>
                Valider mon panier
            </button>

            <!-- Modal valider le panier-->
            <div class="modal fade" id="validateModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Valider le panier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Cette action validera votre panier. Des produits vous seront donc affectés pour les dates
                            demandées. Voulez-vous continuer ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-info" onclick="validateBasket()">Valider</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal supprimer element du panier-->
            <div class="modal fade" id="deleteLineModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Supprimer la ligne</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Cette action effacera cette ligne de votre panier. Voulez-vous continuer ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-info" id="validateDelete">Valider</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal modifier element du panier -->
            <div class="modal fade" id="updateModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modifier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bootstrap-iso">
                                <div class="alert alert-warning" role="alert" id="alertDaysError"
                                     style="display: none;">
                                    Les dates sélectionnées ne sont pas valides !
                                </div>
                                <div class="alert alert-success" id="actionStatusOk" role="alert"
                                     style="display: none;">
                                    Modifications éffectuées !
                                </div>
                                <div class="alert alert-danger" id="actionStatusError" role="alert"
                                     style="display: none;">
                                    Une erreur s'est produite. Veuillez réessayer ultérieurement.
                                </div>
                                <div class="bootstrap-iso form-group">
                                    <div class="input-group input-daterange">
                                        <div class="input-group-addon">de</div>
                                        <input type="text" id="startDate" name="startDate" class="form-control"
                                               placeholder="YYYY-MM-DD">
                                        <div class="input-group-addon">à</div>
                                        <input type="text" id="endDate" name="endDate" class="form-control" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <div>
                                    Nombre de jours d'emprunt : <span id="remainingDays"></span>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-info" onclick="validateUpdate()">Valider
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal conditions d'emprunt-->
            <div class="modal fade" id="conditionModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Consitions d'emprunt</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Vous vous engagez à prendre soin du matériel emprunté et à le rendre dans l'état dans le quel vous l'avez reçu.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    let nextUpdateId = 0;
    const allImpossibleDays = JSON.parse('<?php echo json_encode($usedCalendar); ?>');
    let impossibleDays = [];

    const axiosInstance = axios.create({
        baseURL: '<?php echo base_url(); ?>'
    });

    function validateBasket() {
        console.log("validate");
        window.location.href = "<?php echo base_url('User/BasketUserController/validateUserBasket'); ?>";
    }

    function checkBasketCondition() {
        console.log("check");

        if ($('#basketCondition').is(':checked')) {
            $('#validateModal').modal();
        } else {
            $('#basketCondition').addClass("is-invalid");
        }
    }

    function deleteBasketLine(basketId) {
        $("#validateDelete").click(function (event) {

            window.location.href = "<?php echo base_url('User/BasketUserController/deleteBasketLine/'); ?>" + basketId;
        });
        $("#deleteLineModal").modal()
    }

    function updateBasket(basketId, productId) {
        console.log("update");

        nextUpdateId = basketId;

        impossibleDays = allImpossibleDays["" + productId];
        if (impossibleDays === undefined) {
            impossibleDays = [];
        }

        //set the date diff
        $('input[name="startDate"]').datepicker('setDate', $('#startDateLine' + basketId).html());
        $('input[name="endDate"]').datepicker('setDate', $('#endDateLine' + basketId).html());

        updateDays();

        $('#updateModal').modal();
        $('#updateModal').on('hidden.bs.modal', resetModals);
    }

    function validateUpdate() {

        const startDate = $('input[name="startDate"]').val();
        const endDate = $('input[name="endDate"]').val();

        axiosInstance.put("/User/BasketRestUserController/updateLine/" + nextUpdateId,
            {
                "startDate": startDate,
                "endDate": endDate
            })
            .then(function (response) {
                $("#actionStatusOk").show();
                $('#startDateLine' + nextUpdateId).html(startDate);
                $('#endDateLine' + nextUpdateId).html(endDate);
                updateDays();
                $('#remainingDaysLine' + nextUpdateId).html($('#remainingDays').html());
            })
            .catch(function (error) {
                console.log(error);
                $("#actionStatusError").show();
            });
    }

    function resetModals() {
        $("#actionStatusOk").hide();
        $("#actionStatusError").hide();
    }


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
                console.log(impossibleDays[0]);
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
<?php
include "UserFooter.php";
?>
</body>
</html>
