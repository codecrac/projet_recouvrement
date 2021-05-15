<?php
include "db.php";

$today= date('Y-m-d');

//deja reglees
$nb_facture_payer = $db->query("SELECT COUNT(id) as nb FROM $table_factures WHERE statut='payer'");
$nb_facture_payer = $nb_facture_payer->fetch();
$nb_facture_payer = $nb_facture_payer['nb'];

//deja non reglees
$nb_facture_attente = $db->query("SELECT COUNT(id) as nb FROM $table_factures WHERE statut!='payer'");
$nb_facture_attente = $nb_facture_attente->fetch();
$nb_facture_attente = $nb_facture_attente['nb'];

//deja en retard
$nb_facture_retard = $db->query("SELECT COUNT(id) as nb FROM $table_factures WHERE statut!='payer' AND date_limite<='$today' ");
$nb_facture_retard = $nb_facture_retard->fetch();
$nb_facture_retard = $nb_facture_retard['nb'];

//bientot a echeance
$dans_3_jour = date("Y-m-d", strtotime("+3 day"));

//    $dans_3_jour = new DateTime($today);
//    $dans_3_jour->modify('+3 day');
//    echo $dans_3_jour->format('Y-m-d');
//    $dans_3_jour= (string) $dans_3_jour;

$nb_deadline_inferieure_a_3_jour = $db->query("SELECT COUNT(id) as nb FROM $table_factures WHERE statut!='payer' AND date_limite>'$today' AND date_limite <='$dans_3_jour' ");

$nb_deadline_inferieure_a_3_jour = $nb_deadline_inferieure_a_3_jour->fetch();
$nb_deadline_inferieure_a_3_jour = $nb_deadline_inferieure_a_3_jour['nb'];

$totaux = $db->query("SELECT SUM(reste_a_payer) as total_creances,SUM(grand_total-reste_a_payer) as solde_caisse FROM factures ");
$totaux = $totaux->fetch();

$solde = $totaux['solde_caisse'];
$creance = $totaux['total_creances'];
$db->query("UPDATE $table_caisse SET solde='$solde', total_creances='$creance' WHERE id=1");

$caisse = $db->query("SELECT * FROM $table_caisse limit 1");
$caisse = $caisse->fetch();

 include 'backend_header.php'
?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tableau de bord</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?=$nb_facture_retard?></h3>
                                <p>Factures en retard de paiement</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a  href="rappel.php?type_etat_facture=retard" class="small-box-footer">Voir <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?=$nb_deadline_inferieure_a_3_jour?></h3>

                                <p>Facture a 3 jours de la date limite</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="rappel.php?type_etat_facture=bientot_en_retard" class="small-box-footer">Voir <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?=$nb_facture_attente?></h3>

                                <p>Factures en Attente</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a  href="rappel.php?type_etat_facture=attente" class="small-box-footer">Voir <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?=$nb_facture_payer?></h3>

                                <p>Factures reglées</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a  href="rappel.php?type_etat_facture=payer" class="small-box-footer">Voir <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
<!--                <h1 class="text-left">Caisse</h1>-->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger py-2">
                            <div class="inner">
                                <h3><?= number_format($caisse['total_creances'],0,',',' ') ?> FCFA</h3>
                                <p>Total creances </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info py-2">
                            <div class="inner">
                                <h3><?= number_format($caisse['solde'],0,',',' ') ?> FCFA</h3>
                                <p>Montant en Caisse </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<?php include 'backend_footer.php'; ?>