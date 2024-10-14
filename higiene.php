<?php
session_start();
if (!isset($_SESSION['idTutor'])) {
    header("Location: index.php");
    exit();
}

$mensagemSucesso = $mensagemErro = "";

require_once 'conexaobd.php'; // Inclui o arquivo de conexão ao banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTutor = $_SESSION['idTutor']; // Obtém o id do tutor da sessão
    $idPet = $_POST['idPet'];
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : [];

    // Verifica se o campo "Outros" foi preenchido
    if (in_array('outros', $categorias)) {
        $outros = $_POST['outraCategoria'];
        $categorias = array_diff($categorias, ['outros']); // Remove 'outros' da lista
        $categorias[] = "outros ($outros)"; // Adiciona 'outros (valor digitado)'
    }

    $categoria = implode(',', $categorias); // Converte o array em uma string separada por vírgulas
    $dataHigiene = $_POST['dataHigiene'];
    $anotacoes = $_POST['anotacoes'];

    $stmt = $conn->prepare("INSERT INTO higiene (idPet, idTutor, categoria, dataHigiene, anotacoes) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("iisss", $idPet, $idTutor, $categoria, $dataHigiene, $anotacoes);

    if ($stmt->execute()) {
        $mensagemSucesso = "<div class='alert alert-success'>Dados de higiene registrados com sucesso.</div>";
    } else {
        $mensagemErro = "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$idTutor = $_SESSION['idTutor']; // Supondo que o id do tutor esteja na sessão
$result = $conn->query("SELECT idPet, nomePet FROM animal WHERE idTutor = $idTutor");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Higiene</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="style.css" />
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
            <h2 class="card-title text-center">Higiene</h2>

            <?php
            if (!empty($mensagemSucesso)) {
                echo $mensagemSucesso;
            }
            if (!empty($mensagemErro)) {
                echo $mensagemErro;
            }
            ?>

            <form method="POST" action="higiene.php">
                <div class="form-group">
                    <label for="idPet">Selecione o Pet:</label>
                    <select class="form-control" id="idPet" name="idPet" required>
                        <?php
                        if ($result) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['idPet'] . "'>" . $row['nomePet'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum pet encontrado</option>";
                            }
                        } else {
                            echo "<option value=''>Erro na consulta: " . $conn->error . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Categoria:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categoria[]" id="banho" value="banho">
                        <label class="form-check-label" for="banho">Banho</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categoria[]" id="tosa" value="tosa">
                        <label class="form-check-label" for="tosa">Tosa</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categoria[]" id="dentes" value="dentes">
                        <label class="form-check-label" for="dentes">Dentes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categoria[]" id="orelhas" value="orelhas">
                        <label class="form-check-label" for="orelhas">Orelhas</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categoria[]" id="outros" value="outros">
                        <label class="form-check-label" for="outros">Outros</label>
                    </div>
                </div>

                <div class="form-group" id="outraCategoria" style="display:none;">
                    <label for="outraCategoriaInput">Especifique:</label>
                    <input type="text" class="form-control" id="outraCategoriaInput" name="outraCategoria">
                </div>

                <div class="form-group">
                    <label for="dataHigiene">Data:</label>
                    <input type="date" class="form-control" id="dataHigiene" name="dataHigiene" required>
                </div>

                <div class="form-group">
                    <label for="anotacoes">Anotações:</label>
                    <textarea class="form-control" id="anotacoes" name="anotacoes"></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-block btn-center">
                    <i class="bi bi-check-circle"></i> Registrar
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>

    <script>
        document.getElementById('outros').addEventListener('change', function() {
            var outraCategoria = document.getElementById('outraCategoria');
            if (this.checked) {
                outraCategoria.style.display = 'block';
            } else {
                outraCategoria.style.display = 'none';
            }
        });

        
    </script>
</body>

</html>