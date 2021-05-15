<?php
include "db.php";

$notification="";
if($_SERVER['REQUEST_METHOD']=='POST' AND isset($_POST['enregistrer_facture'])){
    $nombre_produit_et_service = sizeof($_POST['produit_servces']);

    $id_client = $_POST['id_client'];
    $titre_facture = $_POST['titre_facture'];
    $grand_total = $_POST['grand_total'];
    $reste_a_payer = $_POST['reste_a_payer'];
    $date_prestation = $_POST['date_prestation'];
    $date_limite = $_POST['date_limite'];


    if($reste_a_payer>0){
        $statut = "attente";
    }else{
        $statut = "payer";
    }

    $liste_produit_services_fourni = [];
    for ($i=0;$i<$nombre_produit_et_service;$i++){
        $produit_service = $_POST['produit_servces'][$i];
        $quantite = $_POST['quantite'][$i];
        $prix_unitaire = $_POST['prix_unitaire'][$i];
        $prix_total = $_POST['prix_total'][$i];

        $item_produit_services_fourni = [
            'produit_service' => $produit_service,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_total' => $prix_total
        ];

        $date_versement = $_POST['date_avance'];
        $montant_avance = $_POST['avance_percue'];
        $mode_de_paiement = $_POST['mode_de_paiement'];

        $liste_versement = [];
        $le_versement = [
            'date_versement'=>$date_versement,
            'montant'=>$montant_avance,
            'mode_de_paiement'=>$mode_de_paiement
        ];
        array_push($liste_versement,$le_versement);
        $liste_versement =json_encode($liste_versement);
        array_push($liste_produit_services_fourni,$item_produit_services_fourni);
    }
    $liste_produit_services_fourni = json_encode($liste_produit_services_fourni);
//    die(var_dump($liste_produit_services_fourni));
    if(
    $db->query("INSERT INTO $table_factures(id_client,titre,prestations,grand_total,reste_a_payer,statut,date_prestation,date_limite) 
            VALUES ('$id_client','$titre_facture','$liste_produit_services_fourni','$grand_total','$reste_a_payer','$statut','$date_prestation','$date_limite')")
    ){
        $id_facture = $db->lastInsertId();
        if(!empty($date_versement) && !empty($mode_de_paiement) && !empty($montant_avance)){
            $resultats = $db->query("INSERT INTO $table_reglements(id_facture,versements,total_versements) 
                                VALUES('$id_facture','$liste_versement',$montant_avance)");
        }
        $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger text-center'> Echec de l'enregistrement   </div>";
    }
}


$les_clients = $db->query("SELECT * FROM clients ORDER BY id DESC");
$les_clients = $les_clients->fetchAll();


$mode_de_paiement = $db->query("SELECT * FROM $table_mode_de_paiements ORDER BY id DESC");
$mode_de_paiement = $mode_de_paiement->fetchAll();


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
                                <h5 class="text-center"> Enregistrer une facture </h5>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <form method="post" action="">
                                    <h4>Nouvelle Facture</h4>
                                    <div class="container">
                                        <label>Client</label>
                                        <select name="id_client" required class="form-control">
                                            <option value>Choississez le client concerné</option>
                                            <?php foreach ($les_clients as $item_client): ?>
                                                <option value="<?=$item_client['id']?>"><?= $item_client['nom'] .' '.$item_client['prenoms'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="container">
                                        <label>Titre de la facture</label>
                                        <input class="form-control" required type="text" name="titre_facture" placeholder="Installation de fibre optique a Novasys">
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
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="text" name="produit_servces[]" required/>
                                                </td>
                                                <td>
                                                    <input class="form-control prix_unitaire" type="number" name="prix_unitaire[]" id="prix_unitaire_0" onkeyup="calcul_prix_total_ditem('0')"  required/>
                                                </td>
                                                <td>
                                                    <input class="form-control quantite" type="number" name="quantite[]" id="quantite_0" onkeyup="calcul_prix_total_ditem('0')" required />
                                                </td>
                                                <td>
                                                    <input readonly class="form-control prix_total" type="number" name="prix_total[]" id="prix_total_0" onkeyup="calcul_prix_total_ditem('0')"  required/>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="col-12 text-center">
                                            <a class="btn btn-info" onclick="addNewRow()">+</a>
                                                <a class="btn btn-danger" onclick="removeLastRow()">-</a>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="col-md-8">
                                            <div>
                                                <label>Grand Total</label>
                                                <input readonly class="form-control" type="number" name="grand_total" id="grand_total_input" />
                                                <br/>
                                                <label>Date perception d'avance</label>
                                                <input class="form-control" type="date" name="date_avance" />
                                                <br/>
                                                <label>Avance percue</label>
                                                <input class="form-control" type="number" name="avance_percue" id="avance_percue" onkeyup="calcul_reste_a_payer()" />
                                                <br/>
                                                <label>Mode de paiement</label>
                                                <select name="mode_de_paiement" class="form-control" required value>
                                                    <option>Choississez le mode de paiement</option>
                                                    <?php foreach ($mode_de_paiement as $item_mode): ?>
                                                        <option value="<?=$item_mode['id']?>"><?=$item_mode['nom']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <br/>
                                                <label>Reste a payer(Creance)</label>
                                                <input readonly class="form-control" type="number" required name="reste_a_payer" id="reste_a_payer" />
                                                <br/>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Date de prestation</label>
                                                    <input class="form-control" type="date" required name="date_prestation" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Date limite de paiment</label>
                                                    <input class="form-control" type="date" name="date_limite" required placeholder="1000" />
                                                </div>
                                            </div>

                                            <div class="row py-2">
                                                <input class="form-control btn btn-success" type="submit" name="enregistrer_facture" value="Enregistrer la facture">
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