<?php
include "db.php";

$notification ="";

if(isset($_GET['id_a_effacer'])){
    $id_a_effacer = $_GET['id_a_effacer'];
    if($db->query("DELETE FROM $table_mode_de_paiements WHERE id=$id_a_effacer")){
        $notification = "<div class='alert alert-success text-center'> Suppression effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger text-center'> Echec Suppression  </div>";
    }
}


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['nouveau_mode_de_paiement'])){
    $nom = htmlspecialchars($_POST['nom']);

    if(!empty($nom)){
        $resultat = $db->query("INSERT INTO $table_mode_de_paiements(nom) VALUES ( '$nom')");

        if($resultat){
            $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
        }else{
            $notification = "<div class='alert alert-danger text-center'> Echec de l'enregistrement   </div>";
        }
    }else{
        $notification = "<div class='alert alert-danger text-center'> Vous devez remplir tous champs avec une etoile(*) devant.  </div>";
    }
}

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['modifier_mode_de_paiement'])){
    $id_mode = htmlspecialchars($_POST['id_mode']);
    $nom = htmlspecialchars($_POST['nom']);

    if(!empty($id_mode) && !empty($nom)){
        $resultat = $db->query("UPDATE $table_mode_de_paiements SET nom='$nom' WHERE id=$id_mode");

        if($resultat){
            $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
        }else{
            $notification = "<div class='alert alert-danger text-center'> Echec de l'enregistrement   </div>";
        }
    }else{
        $notification = "<div class='alert alert-danger text-center'> Vous devez remplir tous champs avec une etoile(*) devant.  </div>";
    }
}

$les_mode_de_paiement = $db->query("SELECT * FROM $table_mode_de_paiements ORDER BY id DESC");
$les_mode_de_paiement = $les_mode_de_paiement->fetchAll();

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
                            <form method="POST" action="" class="row">
                                <div class="card-body">
                                    <div class="col-12">
                                        <label>Denomination du mode paiement</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="nom" class="form-control" value>
                                    </div>
                                    <div class="col-12 my-2">
                                        <input type="submit" value="Enregistrer" name="nouveau_mode_de_paiement" class="btn btn-success">
                                    </div>
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
                                <!-- Main content -->
                                <section id="liste">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="card card-dark">
                                                    <div class="card-header">
                                                        <h3 class="card-title"> Liste des mode de paiements</h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <table id="example1" class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>Nom</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php foreach ($les_mode_de_paiement as $item_mode_de_paiement): ?>
                                                                <tr>
                                                                    <td> <?= $item_mode_de_paiement['nom'] ?> </td>
                                                                    <td>
                                                                        <div class="col-12">
                                                                            <a href="#" onclick="toggle_garde_fou(<?=$item_mode_de_paiement['id']?>)" class="btn btn-primary">Editer</a>
                                                                            <a href="#" onclick="toggle_garde_fou('suppr_<?=$item_mode_de_paiement['id']?>')"  class="btn btn-warning">Supprimer</a>
                                                                        </div>

                                                                        <!--                            //formulaire de modification-->
                                                                        <div class="col-12 garde_fou" id="garde_fou_<?=$item_mode_de_paiement['id']?>">
                                                                            <form method="POST" action="" class="row">
                                                                                <div class="offset-2 col-md-4">
                                                                                    <input type="text" name="nom" class="form-control" value="<?=$item_mode_de_paiement['nom']?>">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <input type="hidden" value="<?=$item_mode_de_paiement['id']?>" name="id_mode" >
                                                                                    <input type="submit" value="Enregistrer" name="modifier_mode_de_paiement" class="btn btn-success">
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <!--//                            //formulaire de modification-->

                                                                        <div class="col-12 garde_fou" id="garde_fou_suppr_<?=$item_mode_de_paiement['id']?>">
                                                                            <div class="col-12"> Confirmer la suppression </div>
                                                                            <div class="row">
                                                                                <div class="offset-md-4 col-md-4">
                                                                                    <a href="?id_a_effacer=<?=$item_mode_de_paiement['id']?>" class="btn btn-danger">Oui</a>
                                                                                    <a href="#" onclick="toggle_garde_fou('suppr_<?=$item_mode_de_paiement['id']?>')" class="btn btn-default">Non</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>

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