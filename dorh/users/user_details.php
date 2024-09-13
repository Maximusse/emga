<?php
require_once('./../layouts/navbar.php');
?>



<!-- User body -->
<h1>DETAILS DES UTILISATEURS</h1>
<!-- User body -->
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                // Include config file
                require_once "./../../config/db/db.php";
                
                // Check existence of id parameter before processing further
                if (isset($_GET["id"]) && !empty(trim($_GET["id"])) && filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
                    // Prepare a select statement
                    $sql = "SELECT * FROM users WHERE type_users_id = :id";
                
                    if ($stmt = $pdo->prepare($sql)) {
                        // Bind variables to the prepared statement as parameters
                        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
                
                        // Set parameters
                        $param_id = trim($_GET["id"]);
                
                        // Attempt to execute the prepared statement
                        if ($stmt->execute()) {
                            if ($stmt->rowCount() > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Nom</th>";
                                echo "<th>Email</th>";
                                echo "<th>Role</th>";
                                echo "<th>Type</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['roles_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['type_users_id']) . "</td>";
                                    echo "<td>";
                                    echo '<a href="read.php?id1=' . htmlspecialchars($row['personnels_id']) . ' " class="mr-3" title="Voir l\'enregistrement" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo '<div class="alert alert-danger"><em>Aucun enregistrement trouvé.</em></div>';
                            }
                        } else {
                            echo "Oops! Une erreur s'est produite lors de la récupération des données.";
                        }
                
                        // Close statement
                        unset($stmt);
                    }
                } else {
                    echo '<div class="alert alert-danger"><em>ID invalide ou manquant.</em></div>';
                }
                
                // Close connection
                unset($pdo);
                ?>
            </div>
        </div>        
    </div>
</div>
<?php
require_once('./../layouts/footer.php');
?>
