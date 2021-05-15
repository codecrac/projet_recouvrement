<?php

$page_de_connexion = true;
include "db.php";

$notification ="";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
    if(!empty($email) AND !empty($mot_de_passe)){
        $resultat =  $db->query("SELECT * FROM $table_gestionnaire WHERE email='$email' AND mot_de_passe='$mot_de_passe'");
        if($le_gestionnaire = $resultat->fetch()){
            $_SESSION['info_gestionnaire'] = [
                "id"=>$le_gestionnaire['id'],
                "nom"=>$le_gestionnaire['nom'],
                "email"=>$le_gestionnaire['email'],
                "contact"=>$le_gestionnaire['contact'],
            ];
            header("Location:tableau_de_bord.php");
        }else{
            $notification = "<div class='alert alert-danger text-center'> Adresse email ou mot de passe incorrect  </div>";
        }
    }else{
        $notification = "<div class='alert alert-danger text-center'> Vous devez remplir tous champs  </div>";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title> Recouvrement | Novasys </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>
<body style="background-color: #666666;">

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="post">
					<span class="login100-form-title p-b-43">
						Se Connecter
					</span>

                <?= $notification ?>

                <div class="wrap-input100 validate-input" data-validate = "Adresse email invalide">
                    <input class="input100" type="text" name="email">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Adresse Email</span>
                </div>


                <div class="wrap-input100 validate-input" data-validate="Champ Obligatoire">
                    <input class="input100" type="password" name="mot_de_passe">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Mot de Passe</span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Connexion
                    </button>
                </div>
            </form>

            <div class="login100-more" style="background-image: url('images/1recouvrement2.jpg');">
            </div>
        </div>
    </div>
</div>





<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/daterangepicker/moment.min.js"></script>
<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>