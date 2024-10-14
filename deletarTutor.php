<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexaoBD.php';

    $idTutor = $_SESSION['idTutor'];

    // Verifica se o ID do tutor foi recebido corretamente
    if (!$idTutor) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
        exit();
    }

    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Exclui os pets do tutor
        $sqlPets = "DELETE FROM animal WHERE idTutor = ?";
        $stmtPets = $conn->prepare($sqlPets);
        if ($stmtPets === false) {
            throw new Exception('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
        }
        $stmtPets->bind_param("i", $idTutor);
        $stmtPets->execute();
        $stmtPets->close();

        // Exclui o tutor
        $sqlTutor = "DELETE FROM tutor WHERE idTutor = ?";
        $stmtTutor = $conn->prepare($sqlTutor);
        if ($stmtTutor === false) {
            throw new Exception('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
        }
        $stmtTutor->bind_param("i", $idTutor);
        $stmtTutor->execute();
        $stmtTutor->close();

        // Commit da transação
        $conn->commit();

        // Destrói a sessão
        session_destroy();

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback da transação em caso de erro
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
}
?>