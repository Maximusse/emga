<?php
require_once('./../layouts/navbar.php');
?>

<?php

require_once('./../../config/db/db.php');  // Assurez-vous que ce fichier initialise $pdo

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

    // Form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $input_psd = trim($_POST["psd"]);
        $input_psd1 = trim($_POST["psd1"]);
        $id = $_GET['id'];

        $psd1_err = "";
        $psd2_err = "";
        
        // Validation des champs de mot de passe
        if (empty($input_psd) || empty($input_psd1)) {
            $psd1_err = "Veuillez remplir les deux champs de mot de passe.";
        } elseif ($input_psd !== $input_psd1) {
            // Si les mots de passe ne correspondent pas
            $psd2_err = "Les mots de passe ne correspondent pas.";
        } else {
            // Hashage du mot de passe avant la mise à jour
            $hashed_password = password_hash($input_psd, PASSWORD_DEFAULT);

            // Prepare the SQL update statement
            $sql2 = "UPDATE users SET password = :password WHERE personnels_id = :id";

            if ($stmt = $pdo->prepare($sql2)) {
                // Bind the variables
                $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);

                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Le mot de passe a été mis à jour avec succès!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Une erreur est survenue lors de la mise à jour.</div>";
                }
            }
        }

        // Close statement and connection
        unset($stmt);
        unset($pdo);
    }

} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<div class="container mt-4 mb-3">
    <!-- User body -->
    <h3>MODIFICATION DU MOT DE PASSE</h3>
    <!-- User body -->
</div>

<div class="container mt-5 mb-3">
    <div class="row">
        <form class="needs-validation" action="" method="POST" novalidate>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="psd">Mot de passe</label>
                        <input type="password" class="form-control" name="psd" id="psd" required>
                        <span class="invalid-feedback"><?php echo $psd1_err;?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="psd1">Reprenez le même mot de passe</label>
                        <input type="password" class="form-control" id="psd1" name="psd1" required>
                        <span class="invalid-feedback"><?php echo $psd2_err;?></span>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-2 mb-3" type="submit">Enregistrer</button>
        </form>
    </div>
</div>

<?php
require_once('./../layouts/footer.php');
?>
