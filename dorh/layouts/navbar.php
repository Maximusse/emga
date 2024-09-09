<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("location: ./../index.php");
    exit;
}

if ($_SESSION["useretypeid"] != 1) {
    header("location: ./../forbidden.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_GET['title'] ?> | EMGA APP</title>
    <link rel="stylesheet" href="./../../style/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../../style/style.css">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><b>EMGA APP</b></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="./../accueil/accueil.php?title=Accueil">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="./../../dorh/users/user_liste.php?title=Utilisateurs">Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="#">Cartes d'accès</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="#">Personnels</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle link-success" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION['useremail'] ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item link-danger" href="./../../logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>