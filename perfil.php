<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}
$idTutor = $_SESSION['idTutor'];

// Inclui o arquivo de conexão com o banco de dados
require 'conexaoBD.php';

$conn->set_charset("utf8mb4"); // Define o charset para UTF-8

// Consulta para obter os dados do tutor
$sqlTutor = "SELECT nomeTutor, email FROM tutor WHERE idTutor = ?";
$stmtTutor = $conn->prepare($sqlTutor);
if ($stmtTutor === false) {
    die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
}
$stmtTutor->bind_param("i", $idTutor);
$stmtTutor->execute();
$resultTutor = $stmtTutor->get_result();
$tutor = $resultTutor->fetch_assoc();
$stmtTutor->close();

// Consulta para obter os dados dos pets
$sql = "SELECT idPet, nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao FROM animal WHERE idTutor = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $idTutor);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="menu-toggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <div class="sidebar">
        <div class="logo">
            <img src="imgs/logo/logo_petcare.png" alt="Logo">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="perfil.php">
                    <img src="imgs/icones/user.png" alt="Perfil" class="icon-img icon-size"> Perfil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="alimentacao.php">
                    <img src="imgs/icones/racao.png" alt="Alimentação" class="icon-img icon-size"> Alimentação
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="higiene.php">
                    <img src="imgs/icones/cachorro-banho.png" alt="Higiene" class="icon-img icon-size"> Higiene
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="exercicio.php">
                    <img src="imgs/icones/gato-exercicio.png" alt="Exercicio" class="icon-img icon-size"> Exercícios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vacinas.php">
                    <img src="imgs/icones/vacina.png" alt="Vacina" class="icon-img icon-size"> Vacinas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="controleparasitario.php">
                    <img src="imgs/icones/controle-parasita.png" alt="Controle Parasitário" class="icon-img icon-size">
                    Controle Parasitário
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link logout" href="index.php">
                    <img src="imgs/icones/sair.png" alt="Logout" class="icon-img"> Logoff
                </a>
            </li>
        </ul>
    </div>

    <div class="content">
        <div class="form-container">
            <h2 class="card-title text-center">Perfil</h2>

            <!-- Seção de Dados do Tutor -->
            <section class="tutor-data">
                <div>
                    <h3>Seus Dados</h3>
                    <p>Nome: <?php echo htmlspecialchars($tutor['nomeTutor']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($tutor['email']); ?></p>
                    <p>Senha: ********</p>
                </div>
                <div class="buttons">
                    <button class="btn btn-primary" id="update-tutor" data-id="<?php echo $_SESSION['idTutor']; ?>">
                        <i class="bi bi-pencil"></i> Atualizar Dados
                    </button>

                    <script>
                        document.getElementById('update-tutor').addEventListener('click', function() {
                            const idTutor = this.getAttribute('data-id');
                            window.location.href = 'atualizar.php?id=' + idTutor;
                        });
                    </script>

                    <button class="btn btn-danger" id="delete-account">
                        <i class="bi bi-trash"></i> Excluir Conta
                    </button>


                </div>
            </section>

            <!-- Seção de Pets -->
            <section class="pet-list">
                <h3>Seus Pets</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nascimento</th>
                            <th>Espécie</th>
                            <th>Raça</th>
                            <th>Sexo</th>
                            <th>Microchip</th>
                            <th>Castrado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pets as $pet): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pet['nomePet']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($pet['dataNascimentoPet']))); ?></td>
                                <td><?php echo htmlspecialchars($pet['especie']); ?></td>
                                <td><?php echo htmlspecialchars($pet['raca']); ?></td>
                                <td><?php echo $pet['sexo'] ? 'Macho' : 'Fêmea'; ?></td>
                                <td><?php echo $pet['microchip'] ? 'Sim' : 'Não'; ?></td>
                                <td><?php echo $pet['castracao'] ? 'Sim' : 'Não'; ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-secondary btn-sm update-pet" data-id="<?php echo $pet['idPet']; ?>">
                                        <i class="bi bi-pencil"></i> Atualizar
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-pet" data-id="<?php echo $pet['idPet']; ?>">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Seção de Ações -->
            <section class="actions">
                <div class="buttons">
                    <button class="btn btn-success" id="cadastrar-pet">
                        <i class="bi bi-plus-circle"></i> Cadastrar Pet
                    </button>
                    <button class="btn btn-primary">
                        <i class="bi bi-clock-history"></i> Histórico
                    </button>
                </div>
            </section>
        </div> <!-- Close form-container -->
    </div> <!-- Close content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        document.getElementById('update-tutor').addEventListener('click', function() {
            window.location.href = 'atualizar.php';
        });

        document.querySelectorAll('.update-pet').forEach(button => {
            button.addEventListener('click', function() {
                const idPet = this.getAttribute('data-id');
                window.location.href = 'atualizarpet.php?id=' + idPet;
            });
        });

        document.getElementById('delete-account').addEventListener('click', function() {
            if (confirm('Tem certeza que deseja excluir sua conta? Todos os seus dados, incluindo os pets, serão excluídos permanentemente.')) {
                fetch('deletarTutor.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Conta excluída com sucesso.');
                            window.location.href = 'index.php'; // Redireciona para a página inicial
                        } else {
                            alert('Falha ao excluir a conta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao tentar excluir a conta.');
                    });
            }
        });

        document.querySelectorAll('.delete-pet').forEach(button => {
            button.addEventListener('click', function() {
                const idPet = this.getAttribute('data-id');
                if (confirm('Tem certeza que deseja excluir este pet?')) {
                    fetch('deletarPet.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                idPet: idPet
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert('Pet excluído com sucesso.');
                                location.reload(); // Recarrega a página para atualizar a lista de pets
                            } else {
                                alert('Falha ao excluir o pet: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            console.log('Erro:', error);    
                            alert('Ocorreu um erro ao tentar excluir o pet.');
                        });
                }
            });
        });

        document.querySelector('.logout').addEventListener('click', function() {
            window.location.href = 'index.php';
        });

        document.getElementById('cadastrar-pet').addEventListener('click', function() {
            window.location.href = 'cadastroPet.php';
        });
    </script>
</body>

</html>