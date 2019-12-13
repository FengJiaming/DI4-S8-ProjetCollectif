<!DOCTYPE html>
<html>
<head>
    <title>Liste de matériel</title>
    <?php
    include "BaseHeader.php";
    ?>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/UserPage.css'); ?>>
</head>
<body>
<?php
include "UserMenu.php";
?>

<h2 class="display-5 text-center mb-4">Emprunter :</h2>
<?php
echo validation_errors();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php
                echo form_open('User/BorrowingUserController/userBorrowing');
                echo form_label("Nom", 'Name');
                echo form_input('ProductName', set_value("ProductName"), 'class="form-control"');

                ?>
            </div>

            <div class="form-group">
                <?php
                echo form_label("Catégorie", 'Categorie');
                $options = array('Sélectionner une catégorie');
                foreach ($categories as $category) {
                    array_push($options, $category->name);
                }
                ?>
                <div>
                    <?php
                    echo form_dropdown("CategoryName", $options, set_value("CategoryName"));
                    ?>
                </div>
                <br/>
                <?php
                echo form_submit('Research', 'Rechercher', 'class = "btn btn-info"');
                echo form_close();
                ?>
            </div>

            <div class="form-group">
                <?php
                $button = array(
                    'title' => 'BorrowingConsumable',
                    'class' => 'btn btn-info'

                );
                echo anchor('User/BorrowingUserController/userBorrowingConsumable', "Demander un consommable", $button);
                ?>
            </div>
            <div class="form-group">
                <?php
                $button = array(
                    'title' => 'BorrowingNewProduct',
                    'class' => 'btn btn-info'

                );
                echo anchor('User/BorrowingUserController/userBorrowingNewProduct', "Demander un nouveau produit", $button);
                ?>
            </div>
        </div>
        <div class="col-md-8">
            <table id="products" class="table mt-5">
                <thead>

                </thead>
                <tbody>
                <?php
                if (empty($products)) {
                ?>
                <tr>
                    <td align="center">Aucun élément</td>
                </tr>
                <?php
                }
                ?>
                <?php
                $i = 0;
                foreach ($products as $product) {
                    if ($i % 4 == 0) {
                        ?>
                        <tr>
                        <?php
                    }
                    ?>
                    <td>
                        <?php
                        $button = array(
                            'title' => $product->name,
                            'class' => 'btn btn-info'

                        );
                        echo anchor('User/BorrowingUserController/userProducts/' . urlencode($product->id), $product->name, $button);
                        ?>
                    </td>
                    <?php
                    $i++; //Possible problème si plus de 4 produits à vérifier
                    if ($i % 4 == 0) {
                        ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#products').DataTable();
</script>

<?php
include "UserFooter.php";
?>
</body>
</html>