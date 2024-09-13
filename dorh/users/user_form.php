<?php
require_once('./../layouts/navbar.php');
?>

<?php

$nom1 = $email1 = $personnels_id ="";

require_once('./../../config/db/db.php'); // Assurez-vous que la connexion PDO est correctement incluse

$query_erreur = ""; // Initialiser la variable d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenir le matricule saisi et le sécuriser
    $matricule = $_POST['matricule'];
    $matricule = htmlspecialchars($matricule); // Assurez-vous que le matricule est sécurisé

    // Préparer la requête SQL pour rechercher le matricule
    $sql = "SELECT * FROM personnels WHERE matricule LIKE :matricule";
    $stmt = $pdo->prepare($sql);

    // Ajouter les pourcentages pour une recherche partielle
    $param_matricule = "%" . $matricule . "%";
    $stmt->bindParam(":matricule", $param_matricule, PDO::PARAM_STR);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Obtenir les résultats
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifier s'il y a des résultats
        if (count($result) > 0) {
            // Rediriger vers `formulaire.php` avec un matricule
            $matricule_result = urlencode($result[0]['matricule']);
            
            $nom1 = $resultat['nom_pers'] . $resultat['prenoms_pers'];
            $mail1 = $resultat['email_pers'];
            $personnels_id = $resultat['personnels_id'];
            exit(); // Assurez-vous d'utiliser `exit` après `header` pour éviter l'exécution du reste du script.
        } else {
            $query_erreur = "Le matricule n'existe pas";
        }
    } else {
        $query_erreur = "Une erreur s'est produite lors de la recherche.";
    }

    // Fermer la déclaration
    unset($stmt);
}

unset($pdo);
?>

<?php
// Include config file
require_once "./../../config/db/db.php";
 
// Define variables and initialize with empty values
$nom = $email = $psd = $per_id = $role_id = $type_user ="";
$nom_err = $email_err = $psd_err = $psd1_err = $role_err = $type_user_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_nom = trim($_POST["nom"]);
    if(empty($input_nom)){
        $nom_err = "Veuillez rentrer un nom.";
    } elseif(!filter_var($input_nom, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nom_err = "Please enter a valid name.";
    } else{
        $nom = $input_name;
    }
    
    // Validate address
    $input_email = trim($_POST["address"]);
    if(empty($input_email)){
        $email_err = "Vueillez entrer un email.";     
    } else{
        $email = $input_email;
    }
    
    // Validate salary
    $input_role = trim($_POST["role"]);
    if(empty($input_role)){
        $role_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_role)){
        $role_err = "Please enter a positive integer value.";
    } else{
        $role = $input_role;
    }

     // Validate salary
     $input_type_user = trim($_POST["type_user"]);
     if(empty($input_type_user)){
         $type_user_err = "Please enter the salary amount.";     
     } elseif(!ctype_digit($input_type_user)){
         $type_user_err = "Please enter a positive integer value.";
     } else{
         $type_user = $input_type_user;
     }


    $input_psd = trim($_POST["psd"]);
    $input_psd1 = trim($_POST["psd1"]);
    if (empty($input_psd) || empty($input_psd1)) {
        $psd1_err = "Veuillez remplir les deux champs de mot de passe.";
    } elseif ($input_psd !== $input_psd1) {
        // Si les mots de passe ne correspondent pas
        $psd_err = "Les mots de passe ne correspondent pas.";
    } else {
        $psd = $input_psd;
    }

    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password, personnels_id, roles_id, type_users_id) VALUES (:nom, :email, :password, :personnels_id, :roles_id, :type_uers_id)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $param_nom);
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":password", $param_password);
            $stmt->bindParam(":personnels_id", $param_pers_id);
            $stmt->bindParam(":roles_id", $param_role_id);
            $stmt->bindParam(":type_users_id", $param_type_user_id);
            
            // Set parameters
            $param_nom = $name;
            $param_email = $email;
            $param_password = $psd;
            $param_pers_id = $personnels_id;
            $param_role_id = $role_id;
            $param_type_user_id = $type_user;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: user_details.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>

<div class="container">
    <!-- User body -->
        <h1>FORMULAIRE D'ENREGISTREMENT</h1>
    <!-- User body -->
</div>

 
<div class="container">
           
        <form class="needs-validation" action="" method="post" novalidate>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <label for="matricule">MATRICULE</label>
                        <input type="text" class="form-control" id="matricule" name="matricule" required>
                        <?php
                            if (!empty($query_erreur)) {
                                echo '<div class="alert alert-warning" role="alert">
                                    ' . htmlspecialchars($query_erreur) . '
                                </div>';
                            }
                        ?>
                    </div>
                    <div class="col-md-6 mt-4">
                        <button type="submit" class="btn btn-primary mb-3">Rechercher</button>
                    </div>   
                </div>
            </div>
        </form>

        
        <form class="needs-validation" novalidate>
                <div class="form-group">
                     <div class="col-md-4 mb-3">
                         <label for="nom">Nom de l'utilisateur</label>
                         <input type="text" name="nom" class="form-control <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom1; ?>">
                            <span class="invalid-feedback"><?php echo $nom_err;?></span>
                     </div>
                     <div class="col-md-4 mb-3">
                         <label for="email">Email</label>
                         <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email1; ?>" required>
                            <span class="invalid-feedback"><?php echo $nom_err;?></span>
                     </div>
                 </div>

                 <div class="form-row">
                     <div class="col-md-4 mb-3">
                         <label for="psd">Mot de passe</label>
                         <input type="text" class="form-control" id="psd" name = "psd" required>
                         <span class="invalid-feedback"><?php echo $psd1_err;?></span>
                     </div>
                     <div class="col-md-4 mb-3">
                         <label for="psd">Reprennez le même mot de passe</label>
                         <input type="text" class="form-control" id="psd1" name ="psd1" required>
                         <span class="invalid-feedback"><?php echo $psd_err;?></span>
                     </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="role">Rôle</label>
                            <select class="form-select" name="role" id="role">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $role_err;?></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type">Type d'utilisateur</label>
                            <select class="form-select" name="type_user" id="type">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $type_user_err;?></span>
                        </div>
                 </div>
                 <button class="btn btn-primary" type="submit">Enregistrer</button>
             </form>
         </div>
     

<?php
require_once('./../layouts/footer.php');
?>