<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserPage.css'); ?>>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

    <script src="<?php echo base_url('assets/js/triTable.js');?>"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
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
<h2 class="display-5 text-center mb-4">Vos emprunts en cours :</h2>

<div class="container-fluid col-lg-11">

    <table id="borrows" class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>N° de matériel</th>
            <th>Nom du matériel</th>
            <th>État</th>
            <th>Date d'emprunt</th>
            <th>Date de retour</th>
            <th>Jour(s) restant(s)</th>
            <th>Commentaires</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (count($borrows) == 0) { ?>
            <tr>
                <td colspan="6" style="text-align: center">Aucun emprunts</td>
            </tr>
            <?php
        }
        // begin borrow rows
        foreach ($borrows as $borrow):
            $isConsumable = $borrow->isConsumable;
            $designation = $isConsumable ? $borrow->designation : $borrow->product->name;
            $condition = $isConsumable ? "N/A" : $borrow->hardware->comment;
            $barCode = $isConsumable ? "N/A" : $borrow->idHardware;

            $rawColor = $borrow->remainingTime->days > 5 ? '' : ($borrow->remainingTime->days > 2 ? 'class="table-warning"' : '');
            $rawColor = $borrow->remainingTime->invert == 1 ? 'class="table-danger"' : $rawColor;

            ?>
            <tr <?php echo $rawColor; ?>>
                <td>
                    <!-- The designation -->
                    <?php echo $barCode; ?>
                </td>
                <td>
                    <!-- The designation -->
                    <?php echo $designation; ?>
                </td>
                <td>
                    <!-- The condition of the hardware -->
                    <?php echo $condition; ?>
                </td>
                <td>
                    <!-- The borrow start date -->
                    <?php echo $borrow->startDate; ?>
                </td>
                <td>
                    <!-- The borrow end date -->
                    <?php echo $borrow->endDate; ?>
                </td>
                <td>
                    <!-- The borrow remaining time before the return time -->
                    <?php echo $borrow->remainingTime->format("%R%a jours %h heure(s)"); ?>
                </td>
                <td>
                    <!-- The borrow comment -->
                    <p data-toggle="tooltip" data-placement="left" title="<?php echo $borrow->userComment; ?>">
                        <?php
                            echo $borrow->userComment;
                            ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $('#borrows').DataTable();
</script>

<?php
include "UserFooter.php";
?>
</body>
</html>
