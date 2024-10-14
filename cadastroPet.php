<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}
$idTutor = $_SESSION['idTutor'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
            <img src="imgs/logo/logo_petcare.png" alt="Logo" />
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="perfil.php">
                    <img src="imgs/icones/user.png" alt="Perfil" class="icon-img icon-size" /> Perfil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="alimentacao.php">
                    <img src="imgs/icones/racao.png" alt="Alimentação" class="icon-img icon-size" /> Alimentação
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="higiene.php">
                    <img src="imgs/icones/cachorro-banho.png" alt="Higiene" class="icon-img icon-size" /> Higiene
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="exercicio.php">
                    <img src="imgs/icones/gato-exercicio.png" alt="Exercicio" class="icon-img icon-size" /> Exercícios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vacinas.php">
                    <img src="imgs/icones/vacina.png" alt="Vacina" class="icon-img icon-size" /> Vacinas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="controleparasitario.php">
                    <img src="imgs/icones/controle-parasita.png" alt="Controle Parasitário" class="icon-img icon-size" /> Controle Parasitário
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link logout" href="index.php">
                    <img src="imgs/icones/sair.png" alt="Logout" class="icon-img" /> Logoff
                </a>
            </li>
        </ul>
    </div>

    <div class="content">
        <div class="form-container">
            <h2 class="card-title text-center">Cadastro do Pet</h2>

            <?php
            $nomePet = $dataNascimentoPet = $especie = $raca = $sexo = $microchip = $castracao = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once 'conexaobd.php'; // Inclui o arquivo de conexão ao banco de dados

                $conn->set_charset("utf8mb4"); // Define o charset para UTF-8

                $nomePet = $_POST['nomePet'];
                $dataNascimentoPet = $_POST['dataNascimentoPet'];
                $especie = $_POST['especie'];
                $raca = $_POST['raca'];
                $sexo = $_POST['sexo'] == "true" ? 1 : 0; // Converte para inteiro
                $microchip = $_POST['microchip'] == "true" ? 1 : 0; // Converte para inteiro
                $castracao = $_POST['castracao'] == "true" ? 1 : 0; // Converte para inteiro

                // Validação de data no servidor
                $dataNascimento = new DateTime($dataNascimentoPet);
                $hoje = new DateTime();
                $anoMinimo = 1900;
                $dataMaxima = new DateTime();
                $dataMaxima->modify('-25 years');

                if ($dataNascimento > $hoje || $dataNascimento < $dataMaxima || $dataNascimento->format('Y') < $anoMinimo) {
                    die("Data de nascimento inválida. O animal deve ter no máximo 25 anos.");
                }

                $stmt = $conn->prepare("INSERT INTO animal (nomePet, dataNascimentoPet, especie, raca, sexo, microchip, castracao, idTutor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                if ($stmt === false) {
                    die("Erro na preparação da consulta: " . $conn->error);
                }

                // Corrige os tipos de parâmetro
                $stmt->bind_param("ssssiiii", $nomePet, $dataNascimentoPet, $especie, $raca, $sexo, $microchip, $castracao, $idTutor);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Novo registro criado com sucesso. Aguarde enquanto direcionamos você para a página de perfil.</div>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'perfil.php';
                            }, 1000); // Redireciona após 1 segundos
                          </script>";
                } else {
                    echo "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
                }

                $stmt->close();
                $conn->close();
            }
            ?>

            <form id="cadastroPetForm" action="" method="post">
                <input type="hidden" name="idTutor" value="<?php echo $_SESSION['idTutor']; ?>">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nomePet" class="form-control" value="<?php echo htmlspecialchars($nomePet); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dataNascimentoPet">Data de Nascimento:</label>
                    <input type="date" id="dataNascimentoPet" name="dataNascimentoPet" class="form-control" value="<?php echo htmlspecialchars($dataNascimentoPet); ?>" required>
                </div>
                <div class="form-group">
                    <label for="especie">Espécie:</label>
                    <select id="especie" name="especie" class="form-control" required>
                        <option value="Cão" <?php if ($especie == "Cão") echo "selected"; ?>>Cão</option>
                        <option value="Gato" <?php if ($especie == "Gato") echo "selected"; ?>>Gato</option>
                    </select>
                </div>
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
                        <option value="true" <?php if ($sexo == 1) echo "selected"; ?>>Macho</option>
                        <option value="false" <?php if ($sexo == 0) echo "selected"; ?>>Fêmea</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="microchip">Microchip:</label>
                    <select id="microchip" name="microchip" class="form-control">
                        <option value="true" <?php if ($microchip == 1) echo "selected"; ?>>Sim</option>
                        <option value="false" <?php if ($microchip == 0) echo "selected"; ?>>Não</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="castracao">Castrado:</label>
                    <select id="castracao" name="castracao" class="form-control">
                        <option value="true" <?php if ($castracao == 1) echo "selected"; ?>>Sim</option>
                        <option value="false" <?php if ($castracao == 0) echo "selected"; ?>>Não</option>
                    </select>
                </div>
                <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mx-2">
                            <i class="bi bi-check-circle"></i> Cadastrar Pet
                        </button>
                        <!-- Botão de Voltar -->
                        <a href="perfil.php" class="btn btn-secondary mx-2">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>