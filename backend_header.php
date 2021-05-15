<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recouvrement- Novasys | Tableau de bord</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" width="130">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Novasys</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="tableau_de_bord.php" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tableau de bord
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="clients.php" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Clients
                            </p>
                        </a>
                    </li>

                    <li class="nav-header">Factures</li>

                    <li class="nav-item">
                        <a href="factures.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Nouvelle Facture</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="clients.php#liste" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Consulter Facture</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="mode_de_paiement.php" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Mode de paiement
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="definir_mail_de_relance.php" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Relancement
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profil.php" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Profil
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="deconnexion.php" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Deconnexion
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!--************a cloner*************-->
    <div class="hidden" style="display: none">
        <table>
            <tbody>
            <tr id="la_ligne">
                <td>
                    <input class="form-control" type="text" name="produit_servces[]"   required/>
                </td>
                <td>
                    <input class="form-control prix_unitaire" type="text" name="quantite[]"  required/>
                </td>
                <td>
                    <input class="form-control quantite" type="text" name="prix_unitaire[]"  required/>
                </td>
                <td>
                    <input readonly class="form-control prix_total" type="text" name="prix_total[]" required/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--************//a cloner*************-->
