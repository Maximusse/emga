<?php
session_start();

// Constantes pour les type d'utilisateur
define('DORH_USER', 1);
define('COMPAGNIE_USER', 2);
define('MESTRE_USER', 3);

// Filtre pour vérifier l'etat de la session
if (isset($_SESSION["loggedin"])) {
    if ($_SESSION["useretypeid"] == DORH_USER) {
        header("location: ./dorh/accueil/accueil.php");
    }
    exit;
}

// Inclusion du fichier de connection à la BD
require_once('./config/db/db.php');

$erreur_email = '';
$erreur_pwd = '';
$query_error = '';
$error_auth = '';

$email = '';

// Formulaire envoyé
if (isset($_POST) && isset($_POST['usermail']) && isset($_POST['userpass'])) {

    $email = trim($_POST['usermail']);
    $pwd = $_POST['userpass'];

    // Controlle de l'existance des valeurs
    if (empty($email)) {
        $erreur_email = "Veuillez entrer votre adresse email";
    }
    if (empty($pwd)) {
        $erreur_pwd = "Veuillez entrer votre mot de passe";
    }

    // Données récupérées pour traitement
    if (empty($erreur_email) || empty($erreur_pwd)) {
        // Recherche du user via l'email
        $param_email = $email;
        $sql = "SELECT * FROM users WHERE email = :useremail AND status = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":useremail", $param_email, PDO::PARAM_STR);

        if ($stmt->execute()) { // Tentative d'éxécution de la requete
            if ($stmt->rowCount() == 1) { // Utilisateur trouvé
                $row = $stmt->fetch();
                $hashed_password = $row["password"];
                if (password_verify($pwd, $hashed_password)) { // Vérification du mot de passe

                    $_SESSION["loggedin"] = true;
                    $_SESSION["userid"] = $row['id'];
                    $_SESSION["usercode"] = $row['code_users'];
                    $_SESSION["username"] = $row['name'];
                    $_SESSION["useremail"] = $row['email'];

                    $role_id = $row['roles_id'];
                    $type_id = $row['type_users_id'];
                    $pers_id = $row['personnels_id'];

                    // Récupération du rôle
                    $role_q = "SELECT * FROM roles WHERE id = $role_id";
                    $role_data = $pdo->query($role_q);
                    $role = $role_data->fetch(PDO::FETCH_ASSOC);
                    $_SESSION["usererole"] = $role['libelle_roles'];

                    // Récupération du type
                    $type_q = "SELECT * FROM type_users WHERE id = $type_id";
                    $type_data = $pdo->query($type_q);
                    $type = $type_data->fetch(PDO::FETCH_ASSOC);
                    $_SESSION["useretype"] = $role['libelle_types'];
                    $_SESSION["useretypeid"] = $role['id'];

                    // Récupération des données personnelles
                    $pers_q = "SELECT * FROM personnels WHERE id = $pers_id";
                    $pers_data = $pdo->query($pers_q);
                    $pers = $pers_data->fetch(PDO::FETCH_ASSOC);
                    $_SESSION["usercontact"] = $pers['contact_pers'];
                    $_SESSION["userphoto"] = $pers['photo_pers'];

                    // Redirection vers la page d'accueil en fonction du type d'utilisateur
                    if ($type_id == DORH_USER) {
                        header("location: ./dorh/accueil/accueil.php?title=Accueil");
                    }
                } else {
                    $erreur_pwd = "Mot de passe incorrect";
                }
            } else {
                $error_auth = "Email non invalide";
            }
        } else {
            $query_error = "Erreur rencontrée. Veuilez réessayer";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification | EMGA APP</title>
    <link rel="stylesheet" href="style/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="page-title mb-3">
                <h3>EMGA APP</h3>
            </div>
            <div class="login-bloc row">
                <div class="col-lg-12">
                    <div class="login-content">
                        <?php
                        if (!empty($query_error)) {
                            echo '<div class="alert alert-warning" role="alert">
                                ' . $query_error . '
                                    </div>';
                        }
                        ?>
                        <form id="login-form" action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label"><b>Email</b></label>
                                <input type="email" name="usermail" class="form-control" id="email" value="<?= $email ?>" placeholder="name@example.com" required>
                                <?php
                                if (!empty($erreur_email)) {
                                    echo '<small style="color:red;font-size:10px;">' . $erreur_email . '</small>';
                                }
                                if (!empty($error_auth)) {
                                    echo '<small style="color:red;font-size:10px;">' . $error_auth . '</small>';
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><b>Mot de passe</b></label>
                                <input type="password" name="userpass" class="form-control" id="password" placeholder=".........." required>
                                <?php
                                if (!empty($erreur_pwd)) {
                                    echo '<small style="color:red;font-size:10px;">' . $erreur_pwd . '</small>';
                                }
                                ?>
                            </div>
                            <div class="mb-3 d-grid">
                                <button class="btn btn-info btn-sm btn-block" type="submit">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="style/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="style/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>