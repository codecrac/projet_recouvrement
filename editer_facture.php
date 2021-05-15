<?php
include "db.php";

if(!isset($_GET['id_facture'])){
    header("Location:tableau_de_bord.php");
}
$id_facture = $_GET['id_facture'];

//***************************************//***************************************//***************************************
$notification ="";
if($_SERVER['REQUEST_METHOD']=='POST' AND isset($_POST['modifier_facture'])){
    $nombre_produit_et_service = sizeof($_POST['produit_servces']);

    $id_facture = $_POST['id_facture'];
    $id_client = $_POST['id_client'];
    $titre_facture = $_POST['titre_facture'];
    $grand_total = $_POST['grand_total'];
    $date_prestation = $_POST['date_prestation'];
    $date_limite = $_POST['date_limite'];


    $liste_produit_services_fourni = [];
    for ($i=0;$i<$nombre_produit_et_service;$i++){
        $produit_service = $_POST['produit_servces'][$i];
        $quantite = $_POST['quantite'][$i];
        $prix_unitaire = $_POST['prix_unitaire'][$i];
        $prix_total = $_POST['prix_total'][$i];

        if(!empty($produit_service)){
            $item_produit_services_fourni = [
                'produit_service' => $produit_service,
                'quantite' => $quantite,
                'prix_unitaire' => $prix_unitaire,
                'prix_total' => $prix_total
            ];
            array_push($liste_produit_services_fourni,$item_produit_services_fourni);
        }else{
            $grand_total -= $prix_total;
        }
    }
    $liste_produit_services_fourni = json_encode($liste_produit_services_fourni);
//    die(var_dump($liste_produit_services_fourni));
    if(
    $db->query("UPDATE $table_factures SET id_client='$id_client',
                     titre='$titre_facture',
                     prestations='$liste_produit_services_fourni',
                     grand_total='$grand_total',
                     date_prestation='$date_prestation',
                     date_limite='$date_limite'
                        WHERE id=$id_facture
                     ")
    ){
        $notification = "<div class='alert alert-success text-center'> Modification effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger text-center'> Echec de la Modification   </div>";
    }
}
//**************************************************************************************
$la_facture = $db->query("SELECT $table_factures.*,$table_clients.id as id_client,$table_clients.nom,$table_clients.prenoms 
                                        FROM $table_factures,$table_clients 
                                        WHERE $table_factures.id=$id_facture AND $table_factures.id_client=$table_clients.id
                                        "
);
//var_dump($la_facture);
$la_facture= $la_facture->fetch();


$les_clients = $db->query("SELECT * FROM clients ORDER BY id DESC");
$les_clients = $les_clients->fetchAll();


    include 'backend_header.php'
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?= $notification ?>
                <div class="row">
                    <!-- left column -->
                    <div class="offset-md-1 col-md-10" style="padding: 15px">
                        <!-- general form elements -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h5 class="text-center"> Edition de facture </h5>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <form method="post" action="">

                                    <div class="container">
                                        <label>Client</label>
                                        <select name="id_client" class="form-control">
                                            <option value="<?=$la_facture['id_client']?>"> <?= $la_facture['nom'].' '.$la_facture['prenoms'] ?> </option>
                                            <?php foreach ($les_clients as $item_client): ?>
                                                <option value="<?=$item_client['id']?>"><?= $item_client['nom'] .' '.$item_client['prenoms'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="container">
                                        <label>Titre de la facture</label>
                                        <input class="form-control" type="text" name="titre_facture" value="<?=$la_facture['titre']?>" placeholder="Installation de fibre optique a Novasys">
                                    </div>

                                    <div class="py-4">
                                        <table class="table table-bordered">
                                            <thead>
                                            <th>Produit(s) / service(s)</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantite</th>
                                            <th>Prix total</th>
                                            </thead>

                                            <tbody id="le_corps_de_la_table">
                                            <?php
                                            $liste_prestations = json_decode($la_facture['prestations']);
                                            //                            var_dump($liste_prestations);
                                            $i=0;
                                            foreach ($liste_prestations as $item_prestation): ?>
                                                <tr>
                                                    <td>
                                                        <input class="form-control" type="text" value="<?= $item_prestation->produit_service ?>" onkeyup="calcul_prix_total_ditem('<?=$i?>')"  name="produit_servces[]"/>
                                                    </td>
                                                    <td>
                                                        <input class="form-control prix_unitaire" type="number" name="prix_unitaire[]" value="<?= $item_prestation->prix_unitaire ?>"  id="prix_unitaire_<?=$i?>" onkeyup="calcul_prix_total_ditem('<?=$i?>')" required/>
                                                    </td>
                                                    <td>
                                                        <input class="form-control quantite" type="number" value="<?= $item_prestation->quantite ?>" name="quantite[]"  id="quantite_<?=$i?>" onkeyup="calcul_prix_total_ditem('<?=$i?>')" required />
                                                    </td>
                                                    <td>
                                                        <input class="form-control prix_total" type="text" name="prix_total[]"value="<?= $item_prestation->prix_total ?>"  id="prix_total_<?=$i?>" onkeyup="calcul_prix_total_ditem('<?=$i?>')" required />
                                                    </td>
                                                </tr>
                                                <?php $i++; endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="col-12 text-center">
                                            <a class="btn btn-info" onclick="addNewRow()">+</ah>
                                                <a class="btn btn-danger" onclick="removeLastRow()">-</a>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="col-md-8">
                                            <div>
                                                <label>Grand Total</label>
                                                <input readonly class="form-control" type="number" name="grand_total" id="grand_total_input" value="<?=$la_facture['grand_total']?>"/>
                                                <br/>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Date de prestation</label>
                                                    <input class="form-control" type="date" name="date_prestation" value="<?=$la_facture['date_prestation']?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Date limite de paiment</label>
                                                    <input class="form-control" type="date" name="date_limite" placeholder="1000" value="<?=$la_facture['date_limite']?>" />
                                                </div>
                                            </div>

                                            <div class="row py-2">
                                                <input type="hidden" name="id_facture" value="<?=$la_facture['id']?>">
                                                <input class="form-control btn btn-success" type="submit" name="modifier_facture" value="Enregistrer la facture">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>



    <script>

        function addNewRow(){
            var la_ligne = document.getElementById("la_ligne");
            // alert(la_ligne);
            var le_corps_de_la_table = document.getElementById("le_corps_de_la_table");
            var le_clone = la_ligne.cloneNode(true);
            le_clone.id ="";
            le_corps_de_la_table.appendChild(le_clone);

            var quantite = document.getElementsByClassName('quantite');
            var prix_unitaire = document.getElementsByClassName('prix_unitaire');
            var prix_total = document.getElementsByClassName('prix_total');

            var id = prix_unitaire.length -1;
            prix_unitaire[id].id="prix_unitaire_"+id;
            quantite[id].id="quantite_"+id;
            prix_total[id].id="prix_total_"+id;

            prix_unitaire[id].onkeyup = function (){ calcul_prix_total_ditem(id) };
            quantite[id].onkeyup = function (){ calcul_prix_total_ditem(id) };
        }
        function removeLastRow(){
            var le_corps_de_la_table = document.getElementById("le_corps_de_la_table");
            var rowCount = le_corps_de_la_table.rows.length;
            if(rowCount>1){
                le_corps_de_la_table.deleteRow(rowCount -1);
            }
        }
    </script>
<?php include 'backend_footer.php'; ?>