<?php
require_once('./../layouts/navbar.php');

// Process delete operation after confirmation
if (isset($_POST["id2"]) && !empty($_POST["id"])) {
    // Include config file
    require_once "./../../config/db/db.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE id = :id2";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id2", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id2"]);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: ./../../../index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter in URL
    if (isset($_GET["id2"]) && !empty(trim($_GET["id2"]))) {
        // Get URL parameter
        $id = trim($_GET["id2"]);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: erreur/error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer l'enregistrement</title>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Supprimer l'utilisateur</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
                            <p>
                                <input type="submit" value="Oui" class="btn btn-danger">
                                <a href="user_details.php" class="btn btn-secondary ml-2">Non</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
