<?php
include "db.php";
$notification = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['modifier_client'])) {

    $id_client = htmlspecialchars($_POST['id_client']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenoms = htmlspecialchars($_POST['prenoms']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $societe = htmlspecialchars($_POST['societe']);

    $update = $db->query("UPDATE $table_clients SET nom='$nom',prenoms='$prenoms',email='$email',telephone='$telephone',adresse='$adresse',societe='$societe' WHERE id='$id_client'");
    if ($update) {
        $notification = "<div class='alert alert-success text-center'> Modification effectué  </div>";
    } else {
        $notification = "<div class='alert alert-danger text-center'> Echec Modification  </div>";
    }
}


$id_a_editer = $_GET['id_client'];
$le_client = $db->query("SELECT * FROM $table_clients WHERE id=$id_a_editer");
if (!$le_client = $le_client->fetch()) {
    header("Location:clients.php");
}

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
                    <div class="offset-md-2 col-md-8" style="padding: 15px">
                        <!-- general form elements -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h5 class="text-center"> Edition client(e) : <?= $le_client['nom'] .' ' .$le_client['prenoms'] ?> </h5>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" action="" class="row">
                                <div class="card-body">
                                    <label>Nom *</label>
                                    <input type="text" name="nom" class="form-control" required value="<?= $le_client['nom'] ?>">
                                    <br/>
                                    <label>Prenom(s) *</label>
                                    <input type="text" name="prenoms" class="form-control" required value="<?= $le_client['prenoms'] ?>">
                                    <br/>
                                    <label>Adresse email *</label>
                                    <input type="email" name="email" class="form-control" required value="<?= $le_client['email'] ?>">
                                    <br/>
                                    <label>Telephone *</label>
                                    <input type="text" name="telephone" class="form-control" required value="<?= $le_client['telephone'] ?>">
                                    <br/>
                                    <label>Adresse *</label>
                                    <input type="text" name="adresse" class="form-control" required value="<?= $le_client['adresse'] ?>">
                                    <br/>
                                    <label>Societe</label>
                                    <input type="text" name="societe" class="form-control" required value="<?= $le_client['societe'] ?>">
                                    <br/>
                                    <div class="text-center py-1">
                                        <h3 class="text-center">
                                            <input type="hidden" name="id_client" class="form-control" required value="<?= $_GET['id_client'] ?>">
                                            <input type="submit" name="modifier_client" class="btn btn-primary" value="Enregistrer les modifications">
                                        </h3>
                                    </div>
                                </div>
                            </form>

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