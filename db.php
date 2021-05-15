<?php
    try{
        $db = new PDO("mysql:host=localhost;dbname=recouvrement","root","");
    }catch (Exception $e){
        echo $e->getMessage();
    }
    $table_gestionnaire = "gestionnaires";
    $table_clients = "clients";
    $table_factures = "factures";
    $table_reglements = "reglements";
    $table_mode_de_paiements = "mode_de_paiements";
    $table_caisse = "caisse";
    $table_mail = "mail_de_relance";
    session_start();

    if(!isset($page_de_connexion)){
        if(!isset($_SESSION['info_gestionnaire'])){
            header("Location:index.php");
        }
    }

//echo "*************<br/>";
//var_dump($_SESSION['info_gestionnaire']);
//die();
?>