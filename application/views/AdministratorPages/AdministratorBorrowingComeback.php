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
    <title>HOME PAGE ADMINISTRATOR</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>

<?php
$barcode = "Code barre";
$designation = "Désignation";
$userComment = "Commentaire utilisateur";
$adminComment = "Commentaire administrateur";
$userInfo = "Information utilisateur";
$validate = "Valider";
$userCommentTexte = "Blablablabla";
$userInfoTexte = "0102030405 Red Yannick";
?>

<h2 class="display-5 text-center mb-4">Retour d'emprunt :</h2>

<nav class="nav nav-tabs mb-5 justify-content-center">
    <a class="nav-item nav-link active" href="#p1" data-toggle="tab">Matériels</a>
    <a class="nav-item nav-link" href="#p2" data-toggle="tab">Consommables</a>
</nav>


<div class="tab-content">
    <div class="tab-pane active" id="p1">
        <form action="/action_page.php">

            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <?php echo "<img class=\"mb-5\" src=\"https://www.w3schools.com/images/w3schools_green.jpg\" style=\"width:100px;height:100px;\"> "; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $barcode; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $barcode . "\" value=\"000000000\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $userComment; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $barcode . "\" value=\"" . $userCommentTexte . "\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $adminComment; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $barcode . "\" value=\"\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $userInfo; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo $userInfoTexte; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-center">
                        <?php echo "<input type=\"submit\" value=\"" . $validate . "\" class='btn btn-info mt-5 mb-5'>"; ?>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="tab-pane" id="p2">
        <form action="/action_page.php">

            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <?php echo "<img class=\"mb-5\" src=\"https://www.w3schools.com/images/w3schools_green.jpg\" style=\"width:100px;height:100px;\"> "; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $designation; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $designation . "\" value=\"test\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $userComment; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $designation . "\" value=\"" . $userCommentTexte . "\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $adminComment; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo "<input type=\"text\" name=\"" . $designation . "\" value=\"\">"; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 text-center">
                        <?php echo $userInfo; ?>
                    </div>
                    <div class="col-6 text-center">
                        <?php echo $userInfoTexte; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-center">
                        <?php echo "<input type=\"submit\" value=\"" . $validate . "\" class='btn btn-info mt-5 mb-5'>"; ?>
                    </div>
                </div>
            </div>

        </form>
    </div>

</div>

</body> 