 <?php
    require 'config.php';

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gestion_collectes", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $stmt = $pdo->prepare("DELETE FROM dechets_collectes WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                //require_once "./collection_edit.php?id=$id&&success=1";
            header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de la suppression.";
            }
        } catch (PDOException $e) {
            die("Erreur: " . $e->getMessage());
        }
    } else {
        echo "ID invalide.";
    }
?>
