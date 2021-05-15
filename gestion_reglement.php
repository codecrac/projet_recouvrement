<?php
include "db.php";

if(!isset($_GET['id_facture'])){
    header("Location:clients.php");
}

$notification="";
//******************************ajouter versement********************************************
//******************************ajouter versement********************************************
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['enregistrer_versement'])){
    $id_facture = $_POST['id_facture'];
    $date_versement = $_POST['date_versement'];
    $montant = $_POST['montant'];
    $mode_de_paiement = $_POST['mode_de_paiement'];

    $versement = $db->query("SELECT versements FROM $table_reglements WHERE id_facture=$id_facture");

    $liste_versement = [];
    $le_versement = [
        'date_versement'=>$date_versement,
        'montant'=>$montant,
        'mode_de_paiement'=>$mode_de_paiement
    ];

    if($versement = $versement->fetch()){
        $liste_versement = json_decode($versement['versements']);
        array_push($liste_versement,$le_versement);
        $liste_versement = json_encode($liste_versement);
        $resultats = $db->query("UPDATE $table_reglements SET versements='$liste_versement',total_versements=total_versements+$montant WHERE id_facture='$id_facture'");
    }else{
        array_push($liste_versement,$le_versement);

        $liste_versement = json_encode($liste_versement);
        $resultats = $db->query("INSERT INTO $table_reglements(id_facture,versements,total_versements) 
                                VALUES('$id_facture','$liste_versement',$montant)");
    }

    //mettre a jour le reste a payer
    $db->query("UPDATE $table_factures SET reste_a_payer=reste_a_payer-$montant WHERE id='$id_facture'");


    if($resultats){
        $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger text-center'> Echec de l'enregistrement   </div>";
    }

}
elseif(isset($_GET['effacer_versement'])){
    $index_a_retirer = $_GET['effacer_versement'];

//*******************************effacer_versement*****************************************
//*******************************effacer_versement*****************************************
    $id_facture = $_GET['id_facture'];
    $versement = $db->query("SELECT * FROM $table_reglements WHERE id_facture=$id_facture");
    if($versement = $versement->fetch()){
        $liste_versement = json_decode($versement['versements']);
        if(!is_array($liste_versement)){
            $liste_versement = [$liste_versement];
        }
        $montant = $liste_versement[$index_a_retirer]->montant;
//        var_dump($liste_versement);
//        echo "<br/>***************************************<br/>";
        array_splice($liste_versement,$index_a_retirer,1);
//        $liste_versement[$index_a_retirer];
//        var_dump($liste_versement);
//        die();
        $liste_versement = json_encode($liste_versement);
        $resultats = $db->query("UPDATE $table_reglements SET versements='$liste_versement' WHERE id_facture='$id_facture'");

        //retrait augmente le reste a payer et diminue le total des versements
        $db->query("UPDATE $table_factures SET reste_a_payer=reste_a_payer+$montant WHERE id='$id_facture'");
        $db->query("UPDATE $table_reglements SET total_versements=total_versements-$montant WHERE id_facture='$id_facture'");


        if($resultats){
            $notification = "<div class='alert alert-success text-center'> Retrait du versement effectué  </div>";
        }else{
            $notification = "<div class='alert alert-danger text-center'> Echec de retrait du versement   </div>";
        }
    }

}

$id_facture= $_GET['id_facture'];
$info_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE $table_factures.id='$id_facture' AND $table_factures.id_client=$table_clients.id");
//var_dump($info_facture->fetchAll());
//die();
$reglement = $db->query("SELECT * FROM $table_reglements WHERE id_facture='$id_facture'");

$liste_versement=[];

if($reglement= $reglement->fetch()){
    if(!is_array($reglement)){
        $reglement = [$reglement];
    }
    $liste_versement= array_reverse(json_decode($reglement['versements']));
}

if(!($info_facture = $info_facture->fetch())){
    header("Location:clients.php");
}

if($info_facture['reste_a_payer']<1){
    $db->query("UPDATE $table_factures SET statut='payer' WHERE id='$id_facture'");
    $info_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE $table_factures.id='$id_facture' AND $table_factures.id_client=$table_clients.id");
    $info_facture = $info_facture ->fetch();
}else{
    $db->query("UPDATE $table_factures SET statut='attente' WHERE id='$id_facture'");
    $info_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE $table_factures.id='$id_facture' AND $table_factures.id_client=$table_clients.id");
    $info_facture = $info_facture ->fetch();
}


$mode_de_paiement = $db->query("SELECT * FROM $table_mode_de_paiements ORDER BY id DESC");
$mode_de_paiement = $mode_de_paiement->fetchAll();

include 'backend_header.php'
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <?=$notification?>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">
                        <!-- general form elements -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau Versement</h3>
                            </div>
                            <!-- form start -->
                            <form method="post" action="">
                                <div class="card-body">
                                    <label>Date *</label>
                                    <input type="date" name="date_versement" max="<?=date('Y-m-d')?>" class="form-control" value required>
                                    <br/>
                                    <label>Montant *</label>
                                    <input type="number" name="montant" min="5" max="<?=$info_facture['reste_a_payer']?>" class="form-control" required value>
                                    <br/>
                                    <label>Mode de paiement *</label>
                                    <select name="mode_de_paiement" class="form-control" required >
                                        <option value>Choississez le mode de paiement</option>
                                        <?php foreach ($mode_de_paiement as $item_mode): ?>
                                            <option value="<?=$item_mode['id']?>"><?=$item_mode['nom']?></option>
                                        <?php endforeach;?>
                                    </select>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <h3 class="text-center">
                                        <input type="hidden" name="id_facture" class="form-control" value="<?=$info_facture['id']?>" required>
                                        <input type="submit" class="btn btn-primary" name="enregistrer_versement" value="Enregistrer le versement">
                                    </h3>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6">
                        <!-- Form Element sizes -->
                        <div class="card ">
                            <!-- /.card-body -->
                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <p>
                                            <b>Client(e) :</b> <?= $info_facture['nom'] .' '.$info_facture['prenoms'] ?>
                                        </p>
                                        <p>
                                            <b>Facture :</b> <?= $info_facture['titre'] ?>
                                        </p>
                                        <p>
                                            <b> Montant :</b> <?= $info_facture['grand_total'] ?> FCFA
                                        </p>
                                        <p>
                                            <b> Reste à payer :</b> <?= $info_facture['reste_a_payer'] ?> FCFA
                                        </p>
                                    </div>
                                </div>
                                <!-- Main content -->
                                <section id="liste">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="card card-dark">
                                                    <div class="card-header">
                                                        <h3 class="card-title"> Historique de versements</h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <table id="example1" class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Montant</th>
                                                                <th>Mode de paiement</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $i=sizeof($liste_versement)-1; foreach ($liste_versement as $item_versement):
                                                                $nom_mode_de_paiement = $db->query("SELECT nom FROM $table_mode_de_paiements WHERE id=$item_versement->mode_de_paiement");
                                                                $nom_mode_de_paiement = $nom_mode_de_paiement->fetch();
                                                                $nom_mode_de_paiement = $nom_mode_de_paiement['nom'];
                                                                ?>
                                                                <tr>
                                                                    <td> <?= $item_versement->date_versement ?> </td>
                                                                    <td> <?= $item_versement->montant ?> </td>
                                                                    <td> <?= $nom_mode_de_paiement?> </td>
                                                                    <td>
                                                                        <a href="?id_facture=<?=$id_facture?>&effacer_versement=<?=$i?>" class="btn btn-danger">Supprimer le versement</a>
                                                                    </td>
                                                                </tr>
                                                                <?php $i--; endforeach; ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Montant</th>
                                                                <th>Mode de paiement</th>
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
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->



        </section>
        <!-- /.content -->
    </div>

<?php include 'backend_footer.php'; ?>