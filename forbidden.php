<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("location: ./../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès non autorisé | EMGA APP</title>
    <link rel="stylesheet" href="./style/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="container forbidden-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="msg-bloc">
                    <h3>ACCES NON AUTORISE</h3>
                </div>
            </div>
        </div>
    </div>

    <script src="./style/bootstrap/js/bootstrap.min.js"></script>
    <script src="./style/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./style/bootstrap/js/popper.min.js"></script>
</body>

</html>