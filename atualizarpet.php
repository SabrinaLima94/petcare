<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexaobd.php'; // Inclui o arquivo de conexão ao banco de dados

$conn->set_charset("utf8mb4"); // Define o charset para UTF-8

$pet = null; // Inicializa a variável $pet

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $petId = $_GET['id'];
    $idTutor = $_SESSION['idTutor']; // Obtém o idTutor da sessão
    $sql = "SELECT nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao FROM animal WHERE idPet = ? AND idTutor = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error . " - SQL: " . $sql);
    }
    $stmt->bind_param("ii", $petId, $idTutor); // Corrige o tipo de parâmetro
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica se o formulário foi submetido
    $idPet = htmlspecialchars($_POST['idPet']);
    $nomePet = htmlspecialchars($_POST['nomePet']);
    $dataNascimentoPet = htmlspecialchars($_POST['dataNascimentoPet']);
    $especie = htmlspecialchars($_POST['especie']);
    $raca = htmlspecialchars($_POST['raca']);
    $sexo = htmlspecialchars($_POST['sexo']);
    $microchip = htmlspecialchars($_POST['microchip']);
    $castracao = htmlspecialchars($_POST['castracao']);

    $idTutor = $_SESSION['idTutor']; // Obtém o idTutor da sessão

    $sql = "UPDATE animal SET nomePet = ?, dataNascimentoPet = ?, especie = ?, raca = ?, sexo = ?, microchip = ?, castracao = ? WHERE idPet = ? AND idTutor = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error . " - SQL: " . $sql);
    }
    $stmt->bind_param("ssssiiiii", $nomePet, $dataNascimentoPet, $especie, $raca, $sexo, $microchip, $castracao, $idPet, $idTutor);
    if ($stmt->execute()) {
        header("Location: perfil.php?id=" . $idPet);
        exit();
    } else {
        echo "Erro ao atualizar os dados do pet: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro de Pet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
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
            <h2 class="card-title text-center">Atualizar Cadastro do Pet</h2>
            <?php if ($pet): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="idPet" value="<?php echo htmlspecialchars($petId); ?>">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nomePet" class="form-control" value="<?php echo htmlspecialchars($pet['nomePet']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dataNascimentoPet">Data de Nascimento:</label>
                        <input type="date" id="dataNascimentoPet" name="dataNascimentoPet" class="form-control" value="<?php echo htmlspecialchars($pet['dataNascimentoPet']); ?>" required>
                    </div>
                    <!-- Campo Espécie -->
                    <div class="form-group">
                        <label for="especie">Espécie:</label>
                        <select id="especie" name="especie" class="form-control" required>
                            <option value="Cão" <?php echo $pet['especie'] == 'Cão' ? 'selected' : ''; ?>>Cão</option>
                            <option value="Gato" <?php echo $pet['especie'] == 'Gato' ? 'selected' : ''; ?>>Gato</option>
                        </select>
                    </div>
                    <!-- Campo Raça -->
                    <div class="form-group">
                        <label for="raca">Raça:</label>
                        <select id="raca" name="raca" class="form-control" required>
                            <!-- Opções serão preenchidas pelo JavaScript -->
                        </select>
                        <input type="text" id="outraRaca" name="outraRaca" class="form-control mt-2" placeholder="Especifique a raça" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" name="sexo" class="form-control">
                            <option value="1" <?php echo $pet['sexo'] ? 'selected' : ''; ?>>Macho</option>
                            <option value="0" <?php echo !$pet['sexo'] ? 'selected' : ''; ?>>Fêmea</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="microchip">Microchip:</label>
                        <select id="microchip" name="microchip" class="form-control">
                            <option value="1" <?php echo $pet['microchip'] ? 'selected' : ''; ?>>Sim</option>
                            <option value="0" <?php echo !$pet['microchip'] ? 'selected' : ''; ?>>Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="castracao">Castrado:</label>
                        <select id="castracao" name="castracao" class="form-control">
                            <option value="1" <?php echo $pet['castracao'] ? 'selected' : ''; ?>>Sim</option>
                            <option value="0" <?php echo !$pet['castracao'] ? 'selected' : ''; ?>>Não</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mx-2">
                            <i class="bi bi-check-circle"></i> Atualizar Pet
                        </button>
                        <!-- Botão de Voltar -->
                        <a href="perfil.php" class="btn btn-secondary mx-2">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>

                </form>
        </div>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Pet não encontrado ou você não tem permissão para atualizar este pet.
        </div>
    <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
    <script src="script.js"></script>
</body>

</html>