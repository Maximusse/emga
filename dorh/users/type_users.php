<?php
require_once('./../layouts/navbar.php');
?>
 
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div class="mt-5 mb-3 clearfix">
                        <a href="user_form.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Ajouter un utilisateur</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "./../../config/db/db.php";
                    
                    // Attempt select query execution
                    // Include config file
                    require_once "./../../config/db/db.php";
                    
                    // Prepare a select statement
                    $sql = "SELECT * FROM type_users";
                    
                    if($result = $pdo->query($sql)){
                        if($result->rowCount() > 0){
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>TYPES D'UTILISATEURS</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                    
                            // Fetch all results and loop through them
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['libelle_type_users']) . "</td>";
                                echo "<td>";
                                echo '<a href="user_details.php?id=' . htmlspecialchars($row['id']) . '" class="mr-3" title="voir" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                    
                            echo "</tbody>";                            
                            echo "</table>";
                    
                            // Free result set
                            unset($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>Aucun element trouvé.</em></div>';
                        }
                    } else {
                        echo "OOps.";
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