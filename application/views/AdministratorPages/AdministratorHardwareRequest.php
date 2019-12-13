<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 20/03/2019
 * Time: 11:03
 */

?>

<?php
$borrowingList="Liste d'emprunt";
$hardwareRequest="Demande de matériel";
$hardwareManagement="Gestion du matériel";
$borrowingComeback="Retour d'emprunt";
$hardwareBorrow="Emrpunt Matériel";
?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include "BaseHeader.php";
    ?>

    <title>HOME PAGE ADMINISTRATOR</title>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> !-->

    <script src="<?php echo base_url('assets/js/triTable.js');?>"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">


	<style>
		table { table-layout: fixed; }
        table td { overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
        }
	</style>

</head>
<body>


<?php
$seeRequest = "Détails";
$studentNumber = "Numéro étudiant";
$lastName = "Nom";
$firstName = "Prénom";
$typeProduct = "Type de produit";
$hardwardRequest = "Matériel";
$Date = "Date";
$requestTexte = "Descriptif"
?>

<h2 class="display-5 text-center mb-4">Demandes de matériels :</h2>

<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Demande :</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Descriptif : </br></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#detailsModal').modal('hide');
    })

    $('#detailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var product = button.data('product');
        var message = button.data('message');

        console.log(product);
        console.log(message);

        $("#modalTitle").html("Demande : " + product);
        $("#modalMessage").html("Descriptif : " + message);
    });
</script>

<div class="container-fluid col-lg-11">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover" id="tableRequest">
                <thead class="sticky-top">
                <tr>
                    <th>
                        <?php echo $seeRequest ?>
                    </th>
                    <th>
                        <?php echo $typeProduct ?>
                    </th>
                    <th>
                        <?php echo $studentNumber ?>
                    </th>
                    <th>
                        <?php echo $lastName ?>
                    </th>
                    <th>
                        <?php echo $firstName?>
                    </th>
                    <th>
                        <?php echo $Date ?>
                    </th>
                    <th >
                        <?php echo $requestTexte ?>
                    </th>
					<th style="width: 8%">

					</th>
                </tr>
                </thead>
                <tbody>

                <?php
				foreach($unreadRequests as $unreadRequest):
				?>
                    <tr>
                    <td><button type="button" class='btn btn-primary btn-open-my-modal' data-toggle="modal" data-target="#detailsModal"
                                data-product="<?= $unreadRequest->productType; ?>" data-message="<?= $unreadRequest->message; ?>">Voir</button></td>
                    <td><?php echo $unreadRequest->productType; ?></td>
                    <td><?php echo $unreadRequest->userNumber; ?></td>
                    <td><?php echo $unreadRequest->firstname; ?></td>
                    <td><?php echo $unreadRequest->lastname; ?></td>
                    <td><?php echo $unreadRequest->date; ?></td>
	                <td class='desc hover'><?php echo $unreadRequest->message; ?></td>
                    <td><a class='btn btn-warning' onclick="unreadRequest(<?php echo $unreadRequest->id; ?>)">Non lu</a></td>
                    </tr>
                <?php
                endforeach;
                ?>

                <?php
                foreach($readRequests as $readRequest):
                ?>
                    <tr>
                    <td><button type="button" class='btn btn-primary btn-open-my-modal' data-toggle="modal" data-target="#detailsModal"
                                data-product="<?= $readRequest->productType; ?>"
                                data-message="<?= $readRequest->message; ?>">Voir</button></td>
                <td><?php echo $readRequest->productType; ?></td>
                <td><?php echo $readRequest->userNumber; ?></td>
                <td><?php echo $readRequest->firstname; ?></td>
                <td><?php echo $readRequest->lastname; ?></td>
                <td><?php echo $readRequest->date; ?></td>
                <td class='desc hover'><?php echo $readRequest->message; ?></td>
                <td><a class='btn btn-light' onclick= "readRequest(<?php echo $readRequest->id; ?>)">Lu</a></td>

                </tr>
                <?php
                endforeach;
                ?>

                </tbody>
            </table>
            <script>$('#tableRequest').DataTable();</script>
        </div>
    </div>
</div>

<script type="text/javascript">

		$('.desc').click(function() {

			if($(this).css('white-space') == 'nowrap')
				$(this).css('white-space', 'normal');
			else
				$(this).css('white-space', 'nowrap');
		});

        function unreadRequest(requestId) {
            //$("#validateDelete").click(function (event) {
            window.location.href = "<?php echo base_url('Admin/HardwareAdminController/readAdminRequest/'); ?>" + requestId;

            //$("#deleteLineModal").modal()
        }

        function readRequest(requestId) {
            window.location.href = "<?php echo base_url('Admin/HardwareAdminController/unreadAdminRequest/'); ?>" + requestId;

        }

</script>

</body>

</html>


