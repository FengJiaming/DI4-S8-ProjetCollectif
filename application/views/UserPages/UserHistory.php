
<!DOCTYPE html>
<html>
<head>
    <title>Historique des emprunts</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserHistory.css'); ?>>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

    <script src="<?php echo base_url('assets/js/triTable.js');?>"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
</head>
<body>
<?php
include "UserMenu.php";
?>
<h2 class="display-5 text-center mb-4">Historique des emprunts :</h2>

<div class="container-fluid col-lg-11">
    <table id="borrows" class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>Nom du materiel</th>
            <th>État</th>
            <th>Date d'emprunt</th>
            <th>Date de retour</th>
            <th>Jour(s) restant(s)</th>
            <th>Commentaires</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // begin borrow rows
        foreach ($borrows as $borrow):
            $isConsumable = $borrow->isConsumable;
            $designation = $isConsumable ? $borrow->designation : $borrow->product->name;
            $condition = $isConsumable ? "N/A" : $borrow->hardware->comment;
            ?>
            <tr>
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
                    <!-- The borrow end date -->
                    <?php echo $borrow->renderDate ? $borrow->renderDate : 'Pas encore retourné'; ?>
                </td>
                <td>
                    <!-- The borrow comment -->
                    <?php echo $borrow->userComment; ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
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

