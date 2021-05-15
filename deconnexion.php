<?php
session_start();
unset($_SESSION['info_gestionnaire']);
header("Location:index.php");
?>