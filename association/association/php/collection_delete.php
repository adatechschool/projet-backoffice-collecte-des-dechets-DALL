<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestion_collectes", "nom_utilisateur_choisi", "mot_de_passe_solide", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $stmt = $pdo->prepare("DELETE FROM collectes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: collection_list.php?success=1");
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
