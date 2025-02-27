
<?php
    require 'config.php';
    require 'garbage.php';

    // Vérifier si un ID de collecte est fourni
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: collection_list.php");
        exit;
    }

    $id = $_GET['id'];
    $_SESSION['id'] = $id;

    // Récupérer les informations de la collecte
    $stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
    $stmt->execute([$id]);
    $collecte = $stmt->fetch();

    if (!$collecte) {
        header("Location: collection_list.php");
        exit;
    }    
    
    // Récupérer la liste des bénévoles
    $stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
    $stmt_benevoles->execute();
    $benevoles = $stmt_benevoles->fetchAll();
    
    // Mettre à jour la collecte
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $date = $_POST["date"];
        $lieu = $_POST["lieu"];
        $dechet = $_POST["dechet"];
        $quantite_kg = $_POST["quantite_kg"];
        $benevole_id = $_POST["benevole"]; // Récupérer l'ID du bénévole sélectionné

        $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
        $stmt->execute([$date, $lieu, $benevole_id, $id]);
        
        $stmtDechet = $pdo -> prepare("INSERT INTO dechets_collectes (id_collecte, type_dechet, quantite_kg) VALUES (?, ?, ?)");
        $stmtDechet -> execute([$id, $dechet, $quantite_kg]);

    }
    //Récupérer les déchets de la collecte
    $stmt_dechets = $pdo->prepare("SELECT * FROM dechets_collectes WHERE id_collecte = ?");
    $stmt_dechets->execute([$id]);
    $dechets= $stmt_dechets->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="flex h-screen">
        <!-- Dashboard -->
        <div class="bg-black text-white w-64 p-6">
            <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

                <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
                <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
                <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole</a></li>
                <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>

            <div class="mt-6">
                <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">Déconnexion</button>
            </div>

        </div>

        <!-- Contenu principal -->
        <div class="flex-1 p-8 overflow-y-auto">
            <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>

            <!-- Formulaire -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date :</label>
                        <input type="date" name="date" value="<?= htmlspecialchars($collecte['date_collecte']) ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                        <input type="text" name="lieu" value="<?= htmlspecialchars($collecte['lieu']) ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                        <select name="benevole" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                            <?php foreach ($benevoles as $benevole): ?>
                                <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($benevole['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="overflow-hidden rounded-lg shadow-lg bg-white">
                        <label class="text-xl font-bold text-blue-900 mb-4">Déchets collectés :</label>
                        <table class="w-full table-auto border-collapse">
                            <thead class="bg-gray-700 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">Type</th>
                                    <th class="py-3 px-4 text-left">Quantité</th>
                                    <th class="py-3 px-4 text-left"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300">
                                <?php foreach ($dechets as $dechet) : ?>
                                    <tr class="hover:bg-gray-100 transition duration-200">
                                        <td class="py-3 px-4"><?= htmlspecialchars($dechet['type_dechet']) ?></td>
                                        <td class="py-3 px-4"><?= htmlspecialchars($dechet['quantite_kg'])."kg" ?></td>
                                        <td class="py-3 px-4"><a class="bg-red-700 text-white py-1 px-2 text-base rounded-lg" href="garbage_delete.php?id=<?=$dechet['id']?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette collecte ?')">Supprimer</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900 mb-4">Ajouter des déchets</h2>
                        <label class="block text-sm font-medium text-gray-700">Déchet :</label>
                        <select name="dechet" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="" disabled selected></option>
                            <?php foreach ($trashs as $trash): ?>
                                <option value="<?= htmlspecialchars($trash)?>"><?= htmlspecialchars($trash)?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="quantite_kg" placeholder="Quantité en kg" class="w-full p-2 border border-gray-300 rounded-lg mt-3">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                        <button type="submit" class="bg-teal-400 text-black px-4 py-2 rounded-lg">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
