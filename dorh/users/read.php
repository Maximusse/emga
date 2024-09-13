<?php
require_once('./../layouts/navbar.php');
require_once('./../../config/db/db.php'); // Assurez-vous que ce fichier initialise $pdo

// Check existence of id parameter before processing further
if (isset($_GET["id1"]) && !empty(trim($_GET["id1"]))) {
    $id = trim($_GET["id1"]);

    // Prepare select statements
    $sqlUser = "SELECT * FROM users WHERE personnels_id = :id1";
    $sqlPersonnels = "SELECT * FROM personnels WHERE id = :id1";
    
    // Fetch personnels details
    if ($stmtPersonnels = $pdo->prepare($sqlPersonnels)) {
        $stmtPersonnels->bindParam(":id1", $id, PDO::PARAM_INT);
        
        if ($stmtPersonnels->execute()) {
            if ($stmtPersonnels->rowCount() == 1) {
                $row = $stmtPersonnels->fetch(PDO::FETCH_ASSOC);
                // Retrieve individual field values
                $nom = $row["nom_pers"];
                $prenoms = $row["prenoms_pers"];
                $email = $row["email_pers"];
                $matricule = $row["matricule"];
                $contact = $row["contact_pers"];
                $sexe = $row["sexe"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oups ! Une erreur s'est produite. Veuillez réessayer plus tard.";
            exit();
        }
    }

    // Fetch user details
    if ($stmtUser = $pdo->prepare($sqlUser)) {
        $stmtUser->bindParam(":id1", $id, PDO::PARAM_INT);
        
        if ($stmtUser->execute()) {
            if ($stmtUser->rowCount() == 1) {
                $row1 = $stmtUser->fetch(PDO::FETCH_ASSOC);
                $roles = $row1["roles_id"];
                $type_users_id = $row1["type_users_id"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            exit();
        }
    }

    // Form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST['role'];
        $type_user = $_POST['type_user'];

        $sqlUpdate = "UPDATE users SET roles_id = :role, type_users_id = :type_user WHERE personnels_id = :id1";

        if ($stmtUpdate = $pdo->prepare($sqlUpdate)) {
            $stmtUpdate->bindParam(":role", $role, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":type_user", $type_user, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":id1", $id, PDO::PARAM_INT);

            if ($stmtUpdate->execute()) {
                echo "Le rôle et le type d'utilisateur ont été mis à jour avec succès!";
            } else {
                echo "Une erreur est survenue lors de la mise à jour.";
            }

            unset($stmtUpdate);
        }

        unset($pdo);
    }

    unset($stmtPersonnels);
    unset($stmtUser);
    unset($pdo);
} else {
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mt-5 mb-3">Les informations sur un utilisateur</h4>
                    
                    <div class="form-group mt-5 mb-3">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Nom</label>
                                <p><b><?php echo htmlspecialchars($nom); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Prenoms</label>
                                <p><b><?php echo htmlspecialchars($prenoms); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Matricule</label>
                                <p><b><?php echo htmlspecialchars($matricule); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Contact</label>
                                <p><b><?php echo htmlspecialchars($contact); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Email</label>
                                <p><b><?php echo htmlspecialchars($email); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Sexe</label>
                                <p><b><?php echo htmlspecialchars($sexe); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Type utilisateur</label>
                                <p><b><?php echo htmlspecialchars($type_users_id); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Rôle</label>
                                <p><b><?php echo htmlspecialchars($roles); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <p><a href="user_list.php" class="btn btn-primary">Retour</a></p>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <p><a href="update.php?id1=<?php echo htmlspecialchars($id); ?>" class="btn btn-success">Modifier</a></p>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <p><a href="delete.php?id1=<?php echo htmlspecialchars($id); ?>" class="btn btn-danger">Supprimer</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-6">
                    <form action="" method="POST">
                        <div class="form-row">
                            <h4 class="mt-5 mb-3">Formulaire pour changer le rôle et le type d'utilisateur</h4>
                            <div class="col-md-12 mb-3">
                                <label for="role">Rôle</label>
                                <select class="form-select" name="role" id="role">
                                    <option value="1" <?php echo $roles == 1 ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo $roles == 2 ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo $roles == 3 ? 'selected' : ''; ?>>3</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="type">Type d'utilisateur</label>
                                <select class="form-select" name="type_user" id="type">
                                    <option value="1" <?php echo $type_users_id == 1 ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo $type_users_id == 2 ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo $type_users_id == 3 ? 'selected' : ''; ?>>3</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2 mb-3" type="submit">Enregistrer</button>
                    </form>
                </div>
                            
            </div>
        </div>
    </div>
</body>
</html>
<?php
require_once('./../layouts/navbar.php');
require_once('./../../config/db/db.php'); // Assurez-vous que ce fichier initialise $pdo

// Check existence of id parameter before processing further
if (isset($_GET["id1"]) && !empty(trim($_GET["id1"]))) {
    $id = trim($_GET["id1"]);

    // Prepare select statements
    $sqlUser = "SELECT * FROM users WHERE personnels_id = :id1";
    $sqlPersonnels = "SELECT * FROM personnels WHERE id = :id1";
    
    // Fetch personnels details
    if ($stmtPersonnels = $pdo->prepare($sqlPersonnels)) {
        $stmtPersonnels->bindParam(":id1", $id, PDO::PARAM_INT);
        
        if ($stmtPersonnels->execute()) {
            if ($stmtPersonnels->rowCount() == 1) {
                $row = $stmtPersonnels->fetch(PDO::FETCH_ASSOC);
                // Retrieve individual field values
                $nom = $row["nom_pers"];
                $prenoms = $row["prenoms_pers"];
                $email = $row["email_pers"];
                $matricule = $row["matricule"];
                $contact = $row["contact_pers"];
                $sexe = $row["sexe"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oups ! Une erreur s'est produite. Veuillez réessayer plus tard.";
            exit();
        }
    }

    // Fetch user details
    if ($stmtUser = $pdo->prepare($sqlUser)) {
        $stmtUser->bindParam(":id1", $id, PDO::PARAM_INT);
        
        if ($stmtUser->execute()) {
            if ($stmtUser->rowCount() == 1) {
                $row1 = $stmtUser->fetch(PDO::FETCH_ASSOC);
                $roles = $row1["roles_id"];
                $type_users_id = $row1["type_users_id"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            exit();
        }
    }

    // Form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST['role'];
        $type_user = $_POST['type_user'];

        $sqlUpdate = "UPDATE users SET roles_id = :role, type_users_id = :type_user WHERE personnels_id = :id1";

        if ($stmtUpdate = $pdo->prepare($sqlUpdate)) {
            $stmtUpdate->bindParam(":role", $role, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":type_user", $type_user, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":id1", $id, PDO::PARAM_INT);

            if ($stmtUpdate->execute()) {
                echo "Le rôle et le type d'utilisateur ont été mis à jour avec succès!";
            } else {
                echo "Une erreur est survenue lors de la mise à jour.";
            }

            unset($stmtUpdate);
        }

        unset($pdo);
    }

    unset($stmtPersonnels);
    unset($stmtUser);
    unset($pdo);
} else {
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mt-5 mb-3">Les informations sur un utilisateur</h4>
                    
                    <div class="form-group mt-5 mb-3">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Nom</label>
                                <p><b><?php echo htmlspecialchars($nom); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Prenoms</label>
                                <p><b><?php echo htmlspecialchars($prenoms); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Matricule</label>
                                <p><b><?php echo htmlspecialchars($matricule); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Contact</label>
                                <p><b><?php echo htmlspecialchars($contact); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Email</label>
                                <p><b><?php echo htmlspecialchars($email); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Sexe</label>
                                <p><b><?php echo htmlspecialchars($sexe); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Type utilisateur</label>
                                <p><b><?php echo htmlspecialchars($type_users_id); ?></b></p>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label>Rôle</label>
                                <p><b><?php echo htmlspecialchars($roles); ?></b></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <p><a href="user_list.php" class="btn btn-primary">Retour</a></p>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <p><a href="update.php?id1=<?php echo htmlspecialchars($id); ?>" class="btn btn-success">Modifier</a></p>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <p><a href="delete.php?id1=<?php echo htmlspecialchars($id); ?>" class="btn btn-danger">Supprimer</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-6">
                    <form action="" method="POST">
                        <div class="form-row">
                            <h4 class="mt-5 mb-3">Formulaire pour changer le rôle et le type d'utilisateur</h4>
                            <div class="col-md-12 mb-3">
                                <label for="role">Rôle</label>
                                <select class="form-select" name="role" id="role">
                                    <option value="1" <?php echo $roles == 1 ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo $roles == 2 ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo $roles == 3 ? 'selected' : ''; ?>>3</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="type">Type d'utilisateur</label>
                                <select class="form-select" name="type_user" id="type">
                                    <option value="1" <?php echo $type_users_id == 1 ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo $type_users_id == 2 ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo $type_users_id == 3 ? 'selected' : ''; ?>>3</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2 mb-3" type="submit">Enregistrer</button>
                    </form>
                </div>
                            
            </div>
        </div>
    </div>
</body>
</html>