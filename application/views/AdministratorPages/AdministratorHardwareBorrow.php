<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 27/03/2019
 * Time: 11:27
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
    <title>HOME PAGE ADMINISTRATOR</title>
</head>
<body>

<?php
$studentNumber = "numéro de l'étudiant";
$lastName = "nom";
$firstName = "prénom";
$hardwardBorrowed = "matériel emprunté";
$remainingDays = "jours restants";
?>
<table id="tab" border="1px solid #333">
    <tbody>
    <tr>
        <td> <?php echo $studentNumber ?> </td>
        <td> <?php echo $lastName ?> </td>
        <td> <?php echo $firstName ?> </td>
        <td> <?php echo $hardwardBorrowed ?> </td>
        <td> <?php echo $remainingDays ?> </td>
    </tr>

    </tbody>
</table>




</body>

</html>


