<?php

include 'db.php';
$notification="";
$info_facture =[];
if($id_facture = $_GET['id_facture']){
    $info_facture = $db->query("SELECT $table_factures.*,$table_clients.nom,$table_clients.email,$table_clients.prenoms 

                                            FROM $table_factures,$table_clients 
                                            WHERE $table_factures.id=$id_facture 
                                            AND $table_factures.id_client=$table_clients.id "
    );
    $info_facture=$info_facture->fetch();
}else{
    header("Location:tableau_de_bord.php");
}


$le_mail = $db->query("SELECT texte,sujet from mail_de_relance limit 1");
$le_mail =$le_mail->fetch();
$le_texte = $le_mail['texte'];
$sujet = $le_mail['sujet'];


$sujet = str_replace("\$info_facture['nom']",$info_facture['nom'],$sujet);
$sujet = str_replace("\$info_facture['prenoms']",$info_facture['prenoms'],$sujet);
$sujet = str_replace("\$info_facture['grand_total']",$info_facture['grand_total'],$sujet);
$sujet = str_replace("\$info_facture['total_versement']",$info_facture['grand_total']-$info_facture['reste_a_payer'],$sujet);
$sujet = str_replace("\$info_facture['reste_a_payer']",$info_facture['reste_a_payer'],$sujet);
$sujet = str_replace("\$info_facture['titre']",$info_facture['titre'],$sujet);
$sujet = str_replace("\$info_facture['date_prestation']",$info_facture['date_prestation'],$sujet);
$sujet = str_replace("\$info_facture['date_limite']",$info_facture['date_limite'],$sujet);

$le_texte = str_replace("\$info_facture['nom']",$info_facture['nom'],$le_texte);
$le_texte = str_replace("\$info_facture['prenoms']",$info_facture['prenoms'],$le_texte);
$le_texte = str_replace("\$info_facture['grand_total']",$info_facture['grand_total'],$le_texte);
$le_texte = str_replace("\$info_facture['total_versement']",$info_facture['grand_total']-$info_facture['reste_a_payer'],$le_texte);
$le_texte = str_replace("\$info_facture['reste_a_payer']",$info_facture['reste_a_payer'],$le_texte);
$le_texte = str_replace("\$info_facture['titre']",$info_facture['titre'],$le_texte);
$le_texte = str_replace("\$info_facture['date_prestation']",$info_facture['date_prestation'],$le_texte);
$le_texte = str_replace("\$info_facture['date_limite']",$info_facture['date_limite'],$le_texte);

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['relancer'])){
    $le_mail_a_envoyer = $_POST['relancer'];
    $email_client = $info_facture['email'];
    $sujet = $_POST['sujet'];

    if(mail($email_client,$sujet,$le_mail_a_envoyer)){
        $notification =" <div class='text-center alert alert-success'> Relance effectuer </div> ";
        $db->query("UPDATE $table_factures SET date_derniere_relance=NOW() WHERE id=$id_facture");
    }else{
        $notification =" <div class='text-center alert alert-danger'> Echec de l'envoi du mail de relancement </div> ";
    }
}

include 'backend_header.php'
?>

    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <!-- Main content -->
            <section id="liste">
                <div class="container-fluid">
                    <?=$notification?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h5 class="text-center"> RELANCER LE CLIENT </h5>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card card-dark">
                                                    <div class="card-header">
                                                        <h5 class="text-center"> Le mail de relance </h5>
                                                    </div>
                                                    <div class="card-body">

                                                            <form method="post" action="">
                                                                <label>Sujet</label>
                                                                <input type="text" name="sujet" value="<?=$sujet?>" class="form-control">
                                                                <br/>
                                                                <label>Le corps du mail</label>
                                                                <textarea name="le_mail" class="form-control" rows="10" required><?=$le_texte?></textarea>
                                                                <input type="submit" name="relancer" value="Envoyer le mail" class="btn btn-success" />
                                                            </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card card-dark">
                                                    <div class="card-header">
                                                        <h5 class="text-center"> Details facture </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td>Date derniere relance</td>
                                                                <td class="alert alert-danger text-center"><h3>
                                                                    <?php
                                                                        if(!empty($info_facture['date_derniere_relance'])){
                                                                            echo date('d-m-Y',strtotime($info_facture['date_derniere_relance']));
                                                                        } else{
                                                                            echo "-";
                                                                        }
                                                                    ?>
                                                                    </h3></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Client</td>
                                                                <td><?=$info_facture['nom'] . ' '.$info_facture['prenoms'] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Facture</td>
                                                                <td><?=$info_facture['titre']?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Montant Toatal</td>
                                                                <td><?=$info_facture['grand_total']?> FCFA</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total versement</td>
                                                                <td> <?=$info_facture['grand_total'] - $info_facture['reste_a_payer'] ?>  FCFA</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Reste a payer</td>
                                                                <td> <?=$info_facture['reste_a_payer']?> FCFA</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date limite</td>
                                                                <td><?=date('d-m-Y',strtotime($info_facture['date_limite']))?></td>
                                                            </tr>

                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


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