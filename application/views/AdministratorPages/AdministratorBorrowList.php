<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 20/03/2019
 * Time: 11:03
 */

?>


<!DOCTYPE html>
<html>
<head>
	<title>BORROWING LIST PAGE ADMINISTRATOR</title>

	<?php
	include "BaseHeader.php";
	?>

	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="<?php echo base_url('assets/js/triTable.js');?>"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

	<script>
		function printHardwares() {
			const axiosInstance = axios.create({
				baseURL: '<?php echo base_url(); ?>'
			});

			axiosInstance.get(
				"Admin/BorrowRestAdminController/AdminGetCurrentHardwareBorrowingList"
			)
				.then(function (response) {
					console.log(response.data);
					console.log(response.data.length);
					//var table = document.createElement('TABLE');
					//var tableHead = document.createElement('THEAD');
					var tableBody = document.createElement('TBODY');

					for (var nbLine = 0; nbLine < response.data.length; nbLine++) {
						try {

							var tableLine = document.createElement("TR");
							tableLine.id = response.data[nbLine].barCode;

							var tableColumn = document.createElement("TD");
							//tableColumn.width = '400';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].idHardware));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '400';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].categoryName));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '400';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].productName));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode("administrateur : "));
							if (response.data[nbLine].adminComment == null) {
								tableColumn.appendChild(document.createTextNode("RAS"));
							} else {
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].adminComment));
							}
							tableColumn.appendChild(document.createElement("br"));
							tableColumn.appendChild(document.createTextNode("utilisateur : "));
							if (response.data[nbLine].userComment == null) {
								tableColumn.appendChild(document.createTextNode("RAS"));
							} else {
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].userComment));
							}
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].userNumber));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].endDate));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '400';
							if (response.data[nbLine].remainingTime.invert == 1) {
								tableColumn.appendChild(document.createTextNode("En retard de : "));
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].remainingTime.days));
								tableColumn.appendChild(document.createTextNode(" jours"));
							} else {
								tableColumn.appendChild(document.createTextNode("Reste : "));
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].remainingTime.days));
								tableColumn.appendChild(document.createTextNode(" jours"));
							}
							tableLine.appendChild(tableColumn);
							tableBody.appendChild(tableLine);
						} catch (error) {
							console.error(error);
						}

					}
					//table.appendChild(tableBody);
					document.getElementById("tableHardware").appendChild(tableBody);
					$('#tableHardware').DataTable();
				})
				.catch(function (error) {
					console.log("bad");
					console.log(error);
				});
		}
	</script>

	<script>
		function printConsumables() {
			const axiosInstance = axios.create({
				baseURL: '<?php echo base_url(); ?>'
			});

			axiosInstance.get(
				"Admin/BorrowRestAdminController/AdminGetCurrentConsumableBorrowingList"
			)
				.then(function (response) {
					console.log(response.data);
					console.log(response.data.length);

					//var table = document.createElement('TABLE');
					//var tableHead = document.createElement('THEAD');
					var tableBody = document.createElement('TBODY');

					for (var nbLine = 0; nbLine < response.data.length; nbLine++) {

							var tableLine = document.createElement("TR");
							tableLine.designation = response.data[nbLine].designation;

							var tableColumn = document.createElement("TD");
							//tableColumn.width = '400';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].designation));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode("administrateur : "));
							if (response.data[nbLine].adminComment == null || response.data[nbLine].adminComment == "") {
								tableColumn.appendChild(document.createTextNode("RAS"));
							} else {
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].adminComment));
							}
							tableColumn.appendChild(document.createElement("br"));
							tableColumn.appendChild(document.createTextNode("utilisateur : "));
							if (response.data[nbLine].userComment == null || response.data[nbLine].userComment == "") {
								tableColumn.appendChild(document.createTextNode("RAS"));
							} else {
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].userComment));
							}
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].userNumber));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '500';
							tableColumn.appendChild(document.createTextNode(response.data[nbLine].endDate));
							tableLine.appendChild(tableColumn);

							tableColumn = document.createElement("TD");
							//tableColumn.width = '400';

							if (response.data[nbLine].remainingTime.invert == 1) {
								tableColumn.appendChild(document.createTextNode("En retard de : "));
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].remainingTime.days));
								tableColumn.appendChild(document.createTextNode(" jours"));
							} else {
								tableColumn.appendChild(document.createTextNode("Reste : "));
								tableColumn.appendChild(document.createTextNode(response.data[nbLine].remainingTime.days));
								tableColumn.appendChild(document.createTextNode(" jours"));
							}
							tableLine.appendChild(tableColumn);


							tableBody.appendChild(tableLine);


					}
					//table.appendChild(tableBody);
					document.getElementById("tableConsumable").appendChild(tableBody);
					$('#tableConsumable').DataTable();

				})
				.catch(function (error) {
					console.log("bad");
					console.log(error);
				});
		}
	</script>
</head>
<body>


<?php
$studentNumber = "Numéro étudiant";
$lastName = "Nom";
$firstName = "Prénom";
$category = "Catégorie produit";
$hardwareBorrowed = "Matériel emprunté";
$comment = "Commentaire";
$comebackDate = "Date de retour";
$remainingDays = "Jours restants";
$barcode = "Code barre";
$designation = "Désignation";
?>

<h2 class="display-5 text-center mb-4">Liste des emprunts :</h2>

<nav class="nav nav-tabs mb-5 justify-content-center">
	<a class="nav-item nav-link active" href="#p1" data-toggle="tab">Matériels</a>
	<a class="nav-item nav-link" href="#p2" data-toggle="tab">Consommables</a>
</nav>

<div class="tab-content">
	<div class="tab-pane active" id="p1">
		<div class="container-fluid col-lg-11">
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-bordered" id="tableHardware">
						<thead>
						<tr>
							<th>
								<?php echo $barcode; ?>
							</th>
							<th>
								<?php echo $category; ?>
							<th>
								<?php echo $hardwareBorrowed; ?>
							</th>
							<th>
								<?php echo $comment; ?>
							</th>
							<th>
								<?php echo $studentNumber; ?>
							</th>
							<th>
								<?php echo $comebackDate; ?>
							</th>
							<th>
								<?php echo $remainingDays; ?>
							</th>
						</tr>
						</thead>
						<script>printHardwares()</script>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane" id="p2">

		<div class="container-fluid col-lg-11">
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-bordered" id="tableConsumable">
						<thead>
						<tr>
							<th>
								<?php echo $designation; ?>
							</th>
							<th>
								<?php echo $comment; ?>
							</th>
							<th>
								<?php echo $studentNumber; ?>
							</th>
							<th>
								<?php echo $comebackDate; ?>
							</th>
							<th>
								<?php echo $remainingDays; ?>
							</th>
						</tr>
						</thead>
						<script>printConsumables()</script>
					</table>
				</div>
			</div>

		</div>

	</div>

</body>

</html>


