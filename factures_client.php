<?php

if(!isset($_GET['id_client'])){
    header("Location:tableau_de_bord.php");
}
include "db.php";

$notification='';
if(isset($_GET['id_facture_a_effacer'])){
    $id_a_effacer = $_GET['id_facture_a_effacer'];
    if($db->query("DELETE FROM $table_factures WHERE id=$id_a_effacer")){
        $notification = "<div class='alert alert-success text-center'> Suppression effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger  text-center'> Echec Suppression  </div>";
    }
}


$id_client = $_GET['id_client'];
$info_client = $db->query("SELECT nom, prenoms FROM $table_clients WHERE id=$id_client");
$info_client= $info_client->fetch();
$liste_facture = $db->query("SELECT $table_factures.*, $table_clients.nom, $table_clients.prenoms FROM $table_factures,$table_clients WHERE $table_factures.id_client=$id_client AND $table_factures.id_client=$table_clients.id ");
$liste_facture= $liste_facture->fetchAll();

include 'backend_header.php'
?>

    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <!-- Main content -->
            <section id="liste">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <?=$notification?>
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h5 class="text-center"> Liste des facture de : <?=$info_client['nom']. ' '.$info_client['prenoms']?> </h5>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Prestation</th>
                                            <th>Prix Total</th>
                                            <th>Reste à Payer</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php foreach ($liste_facture as $item_facture): ?>
                                            <tr>
                                                <td><?= $item_facture['nom'] .' '. $item_facture['prenoms'] ?></td>
                                                <td><?= $item_facture['titre'] ?></td>
                                                <td><?= $item_facture['grand_total'] ?> FCFA</td>
                                                <td><?= $item_facture['reste_a_payer'] ?> FCFA</td>
                                                <td>
                                                    <button class="<?= $item_facture['statut']=='payer'? 'btn btn-success' : 'btn btn-danger' ?>">
                                                        <?= $item_facture['statut'] ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="col-12 my-2 text-center">
                                                        <a class="btn btn-primary col-10" href="gestion_reglement.php?id_facture=<?=$item_facture['id']?>">reglements</a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <a class="btn btn-warning" href="editer_facture.php?id_facture=<?=$item_facture['id']?>">Details</a>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <a class="btn btn-danger" href="?id_client=<?=$id_client?>&id_facture_a_effacer=<?=$item_facture['id']?>"> Supprimer </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Client</th>
                                            <th>Prestation</th>
                                            <th>Prix Total</th>
                                            <th>Reste à Payer</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
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
        prix_unitaire[id-1].id="prix_unitaire_"+id;
        quantite[id-1].id="quantite_"+id;
        prix_total[id-1].id="prix_total_"+id;

        prix_unitaire[id-1].onkeyup = function (){ calcul_prix_total_ditem(id) };
        quantite[id-1].onkeyup = function (){ calcul_prix_total_ditem(id) };
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

