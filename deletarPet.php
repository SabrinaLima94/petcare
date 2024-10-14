<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexaoBD.php';

    // Recebe os dados da requisição
    $data = json_decode(file_get_contents('php://input'), true);
    $petId = $data['idPet'];
    $idTutor = $_SESSION['idTutor'];

    // Verifica se os dados foram recebidos corretamente
    if (!$petId || !$idTutor) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
        exit();
    }

    // Verifica se o pet pertence ao tutor logado
    $sql = "DELETE FROM animal WHERE idPet = ? AND idTutor = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ii", $petId, $idTutor);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao excluir o pet.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
