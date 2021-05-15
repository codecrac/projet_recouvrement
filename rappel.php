<?php
include 'db.php';

if($type_etat_facture = $_GET['type_etat_facture']){

    $today= date('Y-m-d');
    $dans_3_jour = date("Y-m-d", strtotime("+3 day"));

    switch ($type_etat_facture){
        case 'payer' :  $liste_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE statut='payer' AND $table_factures.id_client=$table_clients.id ");
            break;
        case 'attente' :  $liste_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE statut!='payer' AND $table_factures.id_client=$table_clients.id ");
            break;
        case 'retard' :  $liste_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms  FROM $table_factures,$table_clients WHERE statut!='payer' AND date_limite<='$today' AND $table_factures.id_client=$table_clients.id ");
            break;
        case 'bientot_en_retard' :  $liste_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.prenoms FROM $table_factures,$table_clients WHERE statut!='payer' AND date_limite>'$today' AND date_limite <='$dans_3_jour' AND $table_factures.id_client=$table_clients.id ");
    }
}else{
    header("Location:tableau_de_bord.php");
}

$liste_facture = $liste_facture->fetchAll();

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
                            <table class="table">
                                <tr>
                                    <td class="<?= $type_etat_facture=='retard' ? 'btn btn-dark' :'' ?>">
                                        <a href="rappel.php?type_etat_facture=retard "> <button class="btn btn-default"> Creance en retard </button></a>
                                    </td>
                                    <td class="<?= $type_etat_facture=='bientot_en_retard' ? 'btn btn-dark' :'' ?>">
                                        <a href="rappel.php?type_etat_facture=bientot_en_retard">
                                            <button class="btn btn-default"> Creance Bientot en retard </button>
                                        </a>

                                    </td>
                                    <td class="<?= $type_etat_facture=='attente' ? 'btn btn-dark' :'' ?>">
                                        <a href="rappel.php?type_etat_facture=attente"> <button class="btn btn-default"> Creance non reglées </button> </a>
                                    </td>
                                    <td class="<?= $type_etat_facture=='payer' ? 'btn btn-dark' :'' ?>">
                                        <a href="rappel.php?type_etat_facture=payer"> <button class="btn btn-default" >Creance reglées</button> </a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-12">
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h5 class="text-center"> Etats des creances </h5>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Prestations</th>
                                            <th>Cout</th>
                                            <th>Etat</th>
                                            <th>Reste a payer</th>
                                            <th>Date prestation</th>
                                            <th>Date limite</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($liste_facture as $item_facture): ?>
                                            <tr>
                                                <td><?= $item_facture['nom'].' '.$item_facture['prenoms'] ?></td>
                                                <td><?= $item_facture['titre'] ?></td>
                                                <td><?= $item_facture['grand_total'] ?></td>
                                                <td>
                                                    <?php if($item_facture['statut']=='payer'): ?>
                                                        <a class="btn btn-success"> <?= $item_facture['statut'] ?> </a>
                                                    <?php else: ?>
                                                        <a class="btn btn-danger"> <?= $item_facture['statut'] ?> </a>
                                                    <?php endif ?>
                                                </td>
                                                <td><?= $item_facture['reste_a_payer'] ?> FCFA</td>
                                                <td><?= date('d-m-Y',strtotime($item_facture['date_prestation'])) ?> </td>
                                                <td> <btn class="btn btn-dark"><?= date('d-m-Y',strtotime($item_facture['date_limite']))?></btn> </td>
                                                <td>
                                                    <div class="my-2">
                                                        <?php if($item_facture['reste_a_payer']>0): ?>
                                                            <a href="relancer.php?id_facture=<?=$item_facture['id']?>" class="btn btn-danger">relancer</a>
                                                        <?php endif ?>
                                                    </div>
                                                    <div class="my-2">
                                                        <a href="gestion_reglement.php?id_facture=<?=$item_facture['id']?>" class="btn btn-success">reglement</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Client</th>
                                            <th>Prestations</th>
                                            <th>Cout</th>
                                            <th>Etat</th>
                                            <th>Reste a payer</th>
                                            <th>Date prestation</th>
                                            <th>Date limite</th>
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

<?php include 'backend_footer.php'; ?>