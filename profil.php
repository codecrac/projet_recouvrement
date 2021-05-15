<?php
include "db.php";

$notification="";

if(!isset($_SESSION['info_gestionnaire']['id'])){
    header("Location:index.php");
}

$id_gestionnaire = $_SESSION['info_gestionnaire']['id'];

if($_SERVER['REQUEST_METHOD']=='POST' AND isset($_POST['modifier_profil'])){

    $info_gestionnaire = $db->query("SELECT * FROM $table_gestionnaire WHERE id=$id_gestionnaire");
    $info_gestionnaire = $info_gestionnaire->fetch();
//    var_dump($info_gestionnaire);

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mdp_actuel = $_POST['mdp_actuel'];
    $nv_mdp = $_POST['nv_mdp'];
    $nv_mdp_confirmer = $_POST['nv_mdp_confirmer'];


    if(empty($nom) || empty($email)){
        $notification = "<div class='alert alert-danger text-center'> Nom et adresse email obilgatoire </div>";
    }else{
        if(!empty($mdp_actuel)){
            if($mdp_actuel == $info_gestionnaire['mot_de_passe']){
                if($db->query("UPDATE $table_gestionnaire SET nom='$nom',email='$email' WHERE id=$id_gestionnaire")){
                    $notification = "<div class='alert alert-success text-center'> Modification enregistrées </div>";

                    if(!empty($nv_mdp)){
                        if($nv_mdp_confirmer==$nv_mdp){
                            if($db->query("UPDATE $table_gestionnaire SET mot_de_passe='$nv_mdp_confirmer' WHERE id=$id_gestionnaire")){
                                $notification = "<div class='alert alert-success text-center'> Modification enregistrées, utilisez le nouveau mot de passe a la prochaine connexion </div>";
                            }else{
                                $notification = "<div class='alert alert-danger text-center'> Echec modification du mot de passe </div>";
                            }
                        }else{
                            $notification = "<div class='alert alert-danger text-center'> Les nouveaux mot de passe ne sont pas identiques </div>";
                        }
                    }

                }else{
                    $notification = "<div class='alert alert-danger text-center'> Echec de la modification </div>";
                }
            }else{
                $notification = "<div class='alert alert-danger text-center'> Mot de passe actuel incorrect </div>";
            }
        }else{
            $notification = "<div class='alert alert-danger text-center'> Mot de passe obligatoire   </div>";
        }
    }
}


$le_gestionnaire = $db->query("SELECT * FROM $table_gestionnaire WHERE id=$id_gestionnaire");
$le_gestionnaire = $le_gestionnaire->fetch();


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
                                <h5 class="text-center"> Modifier le profil </h5>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <form method="post" action="">

                                    <div class="container">
                                        <label>Mote de passe Actuel <span class="text-danger">(Obligatoire)</span> </label>
                                        <input class="form-control" required type="password" name="mdp_actuel" >
                                    </div>
                                    <div class="container">
                                        <label>Nom  <span class="text-danger">(Obligatoire)</span></label>
                                        <input class="form-control" required type="text" name="nom" value="<?=$le_gestionnaire['nom']?>" >
                                    </div>
                                    <div class="container">
                                        <label>Email  <span class="text-danger">(Obligatoire)</span></label>
                                        <input class="form-control" required type="email" name="email" value="<?=$le_gestionnaire['email']?>">
                                    </div>
                                    <div class="container">
                                        <label>Nouveau mot de passe</label>
                                        <input class="form-control"  type="text" name="nv_mdp" >
                                    </div>
                                    <div class="container">
                                        <label>Confirmez nouveau mot de passe</label>
                                        <input class="form-control"  type="text" name="nv_mdp_confirmer" >
                                    </div>


                                    <input type="submit" class="btn btn-primary" value="Enregistrer les modifications" name="modifier_profil">
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

<?php include 'backend_footer.php'; ?>