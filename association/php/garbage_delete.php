 <?php
    require 'config.php';


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_trash = (int) $_GET['id'];

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gestion_collectes", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $stmt = $pdo->prepare("DELETE FROM dechets_collectes WHERE id = :id");
            $stmt->bindParam(':id', $id_trash, PDO::PARAM_INT);

            if ($stmt->execute()) {
            header("Location: collection_edit.php?id=".$_SESSION['id']);
                exit;
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
