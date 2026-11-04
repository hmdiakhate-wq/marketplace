<?php

session_start();
require_once '../config/database_connexion.php';

header("Content-type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Utilisateur non connecté !"
    ]);
    exit();
}
$roleStatement = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$roleStatement->execute([$_SESSION['user_id']]);
$currentRole = $roleStatement->fetchColumn();
if ($currentRole !== 'seller') {
    echo json_encode(["success" => false, "message" => "Seuls les vendeurs peuvent ajouter un produit."]);
    exit();
}

$user_id = $_SESSION['user_id'];

// récupérer les données
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = trim($_POST['price'] ?? '');
$image = $_FILES['image'] ?? null;
$price = filter_var($price, FILTER_VALIDATE_FLOAT);

if ($name === '' || $price === false || $price < 0 || !$image || $image['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "success" => false,
        "message" => "Les champs sont obligatoires !"
    ]);
    exit();
}

// Dossier de stockage
$targetDir = __DIR__ . "/uploads/";

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Nom de l'image
$allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($image['tmp_name']);
if (!isset($allowedTypes[$mimeType]) || $image['size'] > 5 * 1024 * 1024) {
    echo json_encode(["success" => false, "message" => "Image invalide (JPG, PNG ou WEBP, 5 Mo maximum)."]);
    exit();
}
$imageName = bin2hex(random_bytes(12)) . '.' . $allowedTypes[$mimeType];
$targetFile = $targetDir . $imageName;

// Déplacer l'image uploadée
if (move_uploaded_file($image['tmp_name'], $targetFile)) {

    try {

        $sql = "INSERT INTO products 
                (user_id, product_name, product_description, product_price, product_image)
                VALUES (?, ?, ?, ?, ?)";

        $stm = $pdo->prepare($sql);

        $stm->execute([
            $user_id,
            $name,
            $description,
            $price,
            "uploads/" . $imageName
        ]);

        echo json_encode([
            "success" => true,
            "message" => "Produit ajouté avec succès !"
        ]);

    } catch (PDOException $er) {

        echo json_encode([
            "success" => false,
            "message" => $er->getMessage()
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "message" => "Impossible d'enregistrer l'image !"
    ]);
}