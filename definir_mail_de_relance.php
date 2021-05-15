<?php
include "db.php";

$notification="";

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['modifier_le_mail'])){

    $id_mail = htmlspecialchars($_POST['id_mail']);
    $sujet = htmlspecialchars($_POST['sujet']);
    $texte_du_mail = htmlspecialchars($_POST['texte_du_mail']);
    $sujet = str_replace("'","''",$sujet);
    $texte_du_mail = str_replace("'","''",$texte_du_mail);

    if(!empty($id_mail)){
        $update = $db->query("UPDATE $table_mail SET sujet='$sujet',texte='$texte_du_mail'");
    }else{
        $update = $db->query("INSERT INTO $table_mail(sujet,texte) VALUES('$sujet','$texte_du_mail')");
    }
    if($update){
        $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger  text-center'> Echec Enregistrement  </div>";
    }
}

$le_mail = $db->query("SELECT sujet,texte from mail_de_relance limit 1");
$le_mail =$le_mail->fetch();


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
                                                        <form class="col-12" method="post" action="">
                                                            <label>Sujet</label>
                                                            <input type="text" name="sujet" value="<?=$le_mail['sujet']?>" class="form-control">
                                                            <br/>
                                                            <label>Le mail</label>
                                                            <input type="hidden" value="<?=$le_mail['id']?>" name="id_mail">
                                                            <textarea rows="10" placeholder="Entrez le mail de relance ici" name="texte_du_mail" class="form-control"> <?= $le_mail['texte'] ?> </textarea>
                                                            <input type="submit" value="Enregistrer" name="modifier_le_mail" class="btn btn-success py-2 my-2">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card card-dark">
                                                    <div class="card-header">
                                                        <h5 class="text-center"> Information sur la facture  </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-bordered">

                                                            <tr>
                                                                <td>Nom du client :  </td>
                                                                <td>$info_facture['nom']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Prenoms du client:  </td>
                                                                <td>$info_facture['nom']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Titre de la facture :  </td>
                                                                <td>$info_facture['prenoms']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Titre de la facture  :  </td>
                                                                <td>$info_facture['titre']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Montant total facture:  </td>
                                                                <td>$info_facture['grand_total']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Reste a payer :  </td>
                                                                <td>$info_facture['reste_a_payer']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nom du client :  </td>
                                                                <td>$info_facture['total_versement']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date limite:  </td>
                                                                <td>$info_facture['date_limite']  </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date prestation/founiture de service:  </td>
                                                                <td>$info_facture['date_prestation']  </td>
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