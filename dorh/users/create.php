<?php
require_once('./../layouts/navbar.php');
?>

<?php

    $resultat = null; // Initialiser la variable $resultat par défaut
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_matricule'])) {
        // Initialiser les variables
        $nom1 = $email1 = $personnels_id = "";
        $query_erreur = ""; // Initialiser la variable d'erreur
    
        require_once('./../../config/db/db.php'); // Inclure la connexion PDO
    
        // Vérifier si le champ matricule est défini et non vide
        if (isset($_POST['matricule']) && !empty(trim($_POST['matricule']))) {
            $matricule = htmlspecialchars(trim($_POST['matricule']));
    
            // Préparer la requête SQL pour rechercher le matricule exact
            $sql = "SELECT * FROM personnels WHERE matricule = :matricule";
            $stmt = $pdo->prepare($sql);
    
            // Lier le paramètre du matricule à la requête
            $stmt->bindParam(":matricule", $matricule, PDO::PARAM_STR);
    
            // Exécuter la requête
            if ($stmt->execute()) {
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer le résultat
    
                // Vérifier que le résultat est un tableau avant d'y accéder
                if ($resultat && is_array($resultat)) {
                    // Accès sécurisé aux éléments du tableau
                    $nom = isset($resultat['nom_pers']) ? $resultat['nom_pers'] : '';
                    $email = isset($resultat['email_pers']) ? $resultat['email_pers'] : '';
                    $personnels_id = isset($resultat['personnels_id']) ? $resultat['personnels_id'] : '';
                } else {
                    $query_erreur = "Aucun utilisateur trouvé pour ce matricule.";
                }
            } else {
                $query_erreur = "Une erreur s'est produite lors de la recherche.";
            }
    
            // Fermer la connexion PDO
            unset($stmt);
            unset($pdo);
        } else {
            $query_erreur = "Veuillez saisir un matricule.";
        }
    }
    
    // Utiliser $resultat seulement si elle est initialisée et contient des données
    if (isset($resultat) && is_array($resultat)) {
        echo "Nom: " . $resultat['nom_pers'];
        echo "Email: " . $resultat['email_pers'];
    }
    
?>


<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_user'])) {
        // Traitement du formulaire d'enregistrement
                // Include config file
require_once "./../../config/db/db.php";

// Define variables and initialize with empty values
$nom = $email = $psd = $role = $type_user = "";
$nom_err = $email_err = $psd_err = $psd1_err = $role_err = $type_user_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'])) {
    // Validate name
    $input_nom = trim($_POST["nom"]);
    if (empty($input_nom)) {
        $nom_err = "Veuillez entrer un nom.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $input_nom)) {
        $nom_err = "Veuillez entrer un nom valide.";
    } else {
        $nom = $input_nom;
    }

    // Validate email
    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Veuillez entrer un email.";
    } else {
        $email = $input_email;
    }

    // Validate role
    $input_role = trim($_POST["role"]);
    if (empty($input_role)) {
        $role_err = "Veuillez sélectionner un rôle.";
    } else {
        $role = $input_role;
    }

    // Validate user type
    $input_type_user = trim($_POST["type_user"]);
    if (empty($input_type_user)) {
        $type_user_err = "Veuillez sélectionner un type d'utilisateur.";
    } else {
        $type_user = $input_type_user;
    }

    if (empty($input_psd) || empty($input_psd1)) {
        $psd1_err = "Veuillez remplir les deux champs de mot de passe.";
    } elseif ($input_psd !== $input_psd1) {
        $psd_err = "Les mots de passe ne correspondent pas.";
    } else {
        $psd = password_hash($input_psd, PASSWORD_DEFAULT);
    }
    

    // Check input errors before inserting in database
    if (empty($nom_err) && empty($email_err) && empty($psd_err) && empty($role_err) && empty($type_user_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password, personnels_id, roles_id, type_users_id) 
                VALUES (:nom, :email, :password, :personnels_id, :roles_id, :type_users_id)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $psd);
            $stmt->bindParam(":personnels_id", $personnels_id);
            $stmt->bindParam(":roles_id", $role);
            $stmt->bindParam(":type_users_id", $type_user);

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to user details page
                header("location: user_details.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);

    }
    

?>


<div class="container">
    <h1>FORMULAIRE D'ENREGISTREMENT</h1>
</div>

<div class="container">
    <h1>FORMULAIRE D'ENREGISTREMENT</h1>
</div>

<div class="container">
    <!-- Formulaire de recherche de matricule -->
    <form action="" method="post">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label for="matricule">MATRICULE</label>
                    <input type="text" class="form-control" id="matricule" name="matricule" required>
                    <?php if (!empty($query_erreur)) : ?>
                        <div class="alert alert-warning" role="alert">
                            <?php echo htmlspecialchars($query_erreur); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mt-4">
                    <button type="submit" name="search_matricule" class="btn btn-primary mb-3">Rechercher</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Formulaire d'enregistrement de l'utilisateur -->
    <form action="" method="post">
        <div class="form-group">
            <div class="col-md-4 mb-3">
                <label for="nom">Nom de l'utilisateur</label>
                <input type="text" name="nom" class="form-control <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo isset($nom1) ? htmlspecialchars($nom1) : ''; ?>">
                <span class="invalid-feedback"><?php echo $nom_err; ?></span>
            </div>
            <div class="col-md-4 mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo isset($email1) ? htmlspecialchars($email1) : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
        </div>

        <!-- Autres champs de formulaire... -->

        <button type="submit" name="save_user" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

    
<?php
require_once('./../layouts/footer.php');
?>