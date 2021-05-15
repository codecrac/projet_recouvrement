<?php
include "db.php";

$notification ="";

if(isset($_GET['id_a_effacer'])){
    $id_a_effacer = $_GET['id_a_effacer'];
    if($db->query("DELETE FROM clients WHERE id=$id_a_effacer")){
        $notification = "<div class='alert alert-success text-center'> Suppression effectué  </div>";
    }else{
        $notification = "<div class='alert alert-danger text-center'> Echec Suppression  </div>";
    }
}


if($_SERVER['REQUEST_METHOD']=="POST"){
    $nom = htmlspecialchars($_POST['nom']);
    $prenoms = htmlspecialchars($_POST['prenoms']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $societe = htmlspecialchars($_POST['societe']);

//        die(var_dump($_POST));



    if(!empty($nom) AND !empty($prenoms) AND !empty($email) AND !empty($telephone) AND !empty($adresse)){
        $resultat = $db->query("INSERT INTO $table_clients(nom, prenoms,email, telephone, adresse, societe) 
                                        VALUES ( '$nom', '$prenoms', '$email', '$telephone', '$adresse', '$societe');");

        if($resultat){
            $notification = "<div class='alert alert-success text-center'> Enregistrement effectué  </div>";
        }else{
            $notification = "<div class='alert alert-danger text-center'> Echec de l'enregistrement   </div>";
        }
    }else{
        $notification = "<div class='alert alert-danger text-center'> Vous devez remplir tous champs avec une etoile(*) devant.  </div>";
    }
}

$les_clients = $db->query("SELECT * FROM $table_clients ORDER BY id DESC");
$les_clients = $les_clients->fetchAll();

 include 'backend_header.php'
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <?=$notification?>
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h5 class="text-center">
                                    Nouveau client
                                    <a href="#liste" type="submit" class="btn btn-info mx-4">Voir la liste des clients</a>
                                </h5>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <label>Nom *</label>
                                            <input type="text" name="nom" class="form-control" placeholder="Coulibaly" value>

                                            <label>Prenom(s) *</label>
                                            <input type="text" name="prenoms" class="form-control"placeholder="Maimouna" value>

                                            <label>Adresse email *</label>
                                            <input type="email" name="email" class="form-control" placeholder="cmaimouna@novasys.com" value>

                                        </div>
                                        <div class="col-md-6">
                                            <label>Telephone *</label>
                                            <input type="text" name="telephone" class="form-control" placeholder="0789898989" value>

                                            <label>Adresse *</label>
                                            <input type="text" name="adresse" class="form-control" placeholder="Cocody, rivera" value>

                                            <label>Societe</label>
                                            <input type="text" name="societe" class="form-control" placeholder="Novasys" value>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <h3 class="text-center">
                                        <input type="submit" class="btn btn-primary" value="Enregistrer le client">
                                    </h3>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->



            <!-- Main content -->
            <section id="liste">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title"> Liste des clients</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Nom et Prenoms</th>
                                            <th>Telephone(s)</th>
                                            <th>Email</th>
                                            <th>Adresse</th>
                                            <th>Societe</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($les_clients as $item_client): ?>
                                            <tr>
                                                <td> <?= $item_client['nom'] .' '. $item_client['prenoms'] ?> </td>
                                                <td> <?= $item_client['telephone'] ?> </td>
                                                <td> <?= $item_client['email'] ?> </td>
                                                <td> <?= $item_client['adresse'] ?> </td>
                                                <td> <?= $item_client['societe'] ?> </td>
                                                <td>
                                                    <div class="col-12 my-2 text-center">
                                                        <a class="btn btn-primary" href="factures_client.php?id_client=<?=$item_client['id']?>">Consulter les factures </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <a href="modifer_client.php?id_client=<?=$item_client['id']?>" class="btn btn-warning">Editer</a>
                                                        <a href="?id_a_effacer=<?=$item_client['id']?>" class="btn btn-danger">Supprimer</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Nom et Prenoms</th>
                                            <th>Telephone(s)</th>
                                            <th>Email</th>
                                            <th>Adresse</th>
                                            <th>Societe</th>
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