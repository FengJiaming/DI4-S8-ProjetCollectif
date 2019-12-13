<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 20/03/2019
 * Time: 11:03
 */

?>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    function printHardwareForAProductName(productName,outOfService,reserved,donation) {
        const axiosInstance = axios.create({
            baseURL: '<?php echo base_url(); ?>'
        });

        axiosInstance.post(
            "Admin/HardwareRestAdminController/AdminGetHardwareFilteredByProductName",
            {
                productName: productName,
                outOfService:outOfService,
                reserved:reserved,
                donation:donation
            })
            .then(function (response) {
                console.log(response);
                var table = document.createElement('TABLE');
                var tableHead = document.createElement('THEAD');
                var tableBody = document.createElement('TBODY');

                for (var nbLine = 0; nbLine < response.data.length; nbLine++) {
                    var tableLine = document.createElement("TR");
                    tableLine.id = response.data[nbLine].barCode;
                    var tableColumn = document.createElement("TD");
                    //tableColumn.width = '500';
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '500';
                    tableColumn.appendChild(document.createTextNode(response.data[nbLine].barCode));
                    tableLine.appendChild(tableColumn);


                    tableColumn = document.createElement("TD");
                    tableColumn.width = '500';
                    tableColumn.appendChild(document.createTextNode(response.data[nbLine].name));
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    tableColumn.width = '500';
                    tableColumn.appendChild(document.createTextNode(response.data[nbLine].description));
                    tableLine.appendChild(tableColumn);


                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '500';
                    if (response.data[nbLine].comment == null) {
                        tableColumn.appendChild(document.createTextNode("RAS"));
                    } else {
                        tableColumn.appendChild(document.createTextNode(response.data[nbLine].comment));
                    }
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '50';
                    if (response.data[nbLine].donation == 0) {
                        tableColumn.appendChild(document.createTextNode("Libre"));
                    } else {
                        tableColumn.appendChild(document.createTextNode("Donation"));
                    }

                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '50';
                    if (response.data[nbLine].outOfService == 0) {
                        tableColumn.appendChild(document.createTextNode("En service"));
                    } else {
                        tableColumn.appendChild(document.createTextNode("Hors service"));
                    }
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '50';
                    if (response.data[nbLine].reserved == 0) {
                        tableColumn.appendChild(document.createTextNode("Libre circulation"));
                    } else {
                        tableColumn.appendChild(document.createTextNode("Réserver TP"));
                    }
                    tableLine.appendChild(tableColumn);

                    tableBody.appendChild(tableLine);
                }
                table.appendChild(tableBody);
                document.getElementById(productName).appendChild(table);

            })
            .catch(function (error) {
                console.log("bad");
                console.log(error);
            });
    }

    function printProductForACategoryName(categoryName,outOfService,reserved,donation) {
        const axiosInstance = axios.create({
            baseURL: '<?php echo base_url(); ?>'
        });

        axiosInstance.post(
            "Admin/ProductRestAdminController/AdminGetProductsFilteredByCategoryName",
            {
                categoryName: categoryName,
                outOfService:outOfService,
                reserved:reserved,
                donation:donation
            })
            .then(function (response) {
                console.log(response.data);


                var table = document.createElement('TABLE');
                var tableHead = document.createElement('THEAD');
                var tableBody = document.createElement('TBODY');

                for (var nbLine = 0; nbLine < response.data.length; nbLine++) {
                    var tableLine = document.createElement("TR");
                    tableLine.id = response.data[nbLine].name;
                    var tableColumn = document.createElement("TD");
                    //tableColumn.width = '1000';
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '1000';
                    tableColumn.appendChild(document.createTextNode(response.data[nbLine].name));
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    //tableColumn.width = '1000';
                    tableColumn.appendChild(document.createTextNode(response.data[nbLine].description));
                    tableLine.appendChild(tableColumn);

                    tableColumn = document.createElement("TD");
                    var buttonForHardware = document.createElement("BUTTON");
                    buttonForHardware.addEventListener("click",printHardwareForAProductName(response.data[nbLine].name,outOfService,reserved,donation));
                    buttonForHardware.className = "btn btn-info";
                    buttonForHardware.innerText = "Afficher le matériel";
                    tableColumn.appendChild(buttonForHardware);
                    tableLine.appendChild(tableColumn);

                    /*var buttonForHardware = document.createElement("input");

                    buttonForHardware.onclick(printHardwareForAProductName(response.data[nbLine].name));
                    buttonForHardware.type = "btn btn-info";
                    buttonForHardware.value = "Afficher le matériel";
                    tableColumn.appendChild(buttonForHardware);
                    tableLine.appendChild(tableColumn);*/

                    //tableBody.appendChild(tableLine);*/

                    
                    tableLine = document.createElement("TR");
                    tableLine.id = response.data[nbLine].name;
                    tableColumn = document.createElement("TD");
                    tableLine.appendChild(tableColumn);
                    tableBody.appendChild(tableLine);

                }

                table.appendChild(tableBody);
                document.getElementById(categoryName).appendChild(table);

            })
            .catch(function (error) {
                console.log("bad");
                console.log(error);
            });
    }
</script>

<!DOCTYPE html>
<html>
<head>
    <title>HOME PAGE ADMINISTRATOR</title>
    <link rel="stylesheet" href= <?php echo base_url('assets/css/Administrator/AdministratorHardwareManagement.css'); ?>>
</head>
<body>


<?php
$labelAddHardware = "Ajouter matériel";
$labelCategory = "Catégories";
$labelProduct = "Produits";
$labelBarcode = "Code barre";
$labelComment = "Commentaire";
$labelReserved = "Réservation TP";
$labelOutOfService = "Hors service";
$labelDonation = "Donation";
$labelEdit = "Modifier";
$labelValidate = "Valider";
$labelDelete = "Supprimer";
?>

<h2 class="display-5 text-center mb-4">Actions administrateurs sur le stock :</h2>

<div class="container-fluid col-lg-11">

    <div class="col">
        <?php
        var_dump($filters);
        echo "<input type=\"submit\" value=\"" . $labelAddHardware . "\" class='btn btn-info mb-5'>";
        ?>
        <div>
            <form method="post" action="AdministratorHardwareManagement">
                <input type="checkbox" ="TP" name="reserved">
                <label for="TP">TP</label>
                <input type="checkbox" id="Don" name="donation">
                <label for="Donation">Donation</label>
                <input type="checkbox" id="HS" name="outOfService">
                <label for="HS">HS</label>
                <input type="submit" value="Envoyer" />
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <?php echo $labelCategory ?>
                    </th>
                </tr>
                </thead>

                <tbody>
                <?php
                foreach ($categoryList as $category) {
                    echo "<tr><td id=\"$category->name\">" . $category->name;
                    echo "<button class = 'btn btn-info ml-5 mb-2' onclick=printProductForACategoryName(\"{$category->name}\",\"{$filters['outOfService']}\",\"{$filters['reserved']}\",\"{$filters['donation']}\")>Afficher les produits</button></td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

