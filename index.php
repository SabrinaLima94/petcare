<?php
session_start(); // Inicia a sessão
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="estilo.css">
</head>

<body>

    <?php
    // Inicializa variáveis
    $email = '';
    $senha = '';
    $error_message = '';

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Configurações do banco de dados
        $servername = "localhost";
        $username = "root"; 
        $password = "usbw"; 
        $dbname = "petcare";

        // Cria a conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Obtém os dados do formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Prepara e executa a consulta SQL
        $sql = "SELECT idTutor FROM tutor WHERE email = ? AND senha = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se as credenciais são válidas
        if ($result->num_rows > 0) {
            // Login bem-sucedido, armazena o idTutor na sessão e redireciona para a página desejada
            $row = $result->fetch_assoc();
            $_SESSION['idTutor'] = $row['idTutor'];
            header("Location: perfil.php");
            exit();
        } else {
            // Credenciais inválidas, exibe mensagem de erro
            $error_message = "E-mail ou senha inválidos!";
        }

        // Fecha a conexão
        $conn->close();
    }
    ?>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <img src="imgs/logo/logo_petcare.png" alt="Logo Pet Care" class="logo">
                        <h1 class="text-center">Login</h1>
                        <?php if ($error_message): ?>
                            <div class='alert alert-danger text-center'><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" value="<?php echo htmlspecialchars($senha); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Entrar</button>
                            <p class="text-center mt-3">Não tem uma conta? <a href="cadastro.php" class="paginaLogin">Registre-se!</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>