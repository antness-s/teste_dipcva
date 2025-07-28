<?php
include '../../backend/conexao.php'; // Caminho relativo a partir de Pag2-AreaVolunter

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
}

$erro = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['name'] ?? '');
    $data_nascimento = trim($_POST['birthdate'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $rg = trim($_POST['rg'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $genero = trim($_POST['gender'] ?? '');

    if (empty($nome) || empty($data_nascimento) || empty($cpf) || empty($rg) || empty($email)) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Insere os dados do voluntário
        $sql_insere = "INSERT INTO voluntarios (nome, data_nascimento, cpf, rg, email, telefone, genero, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt_insere = $conexao->prepare($sql_insere);
        if ($stmt_insere === false) {
            $erro = "Erro ao preparar consulta: " . $conexao->error;
        } else {
            $stmt_insere->bind_param("sssssss", $nome, $data_nascimento, $cpf, $rg, $email, $telefone, $genero);
            if ($stmt_insere->execute()) {
                $erro = "Ficha preenchida com sucesso!";
                // Opcional: redirecionar ou limpar o formulário
                // header("Location: ./home.html");
                // exit();
            } else {
                $erro = "Erro ao preencher ficha: " . $stmt_insere->error;
            }
            $stmt_insere->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cad-volunter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Cadastro</title>
</head>

<body>
    <main id="form_container">
        <div id="form_header">
            <h1 id="form_title">
                Ficha de voluntariado
            </h1>

            <button onclick="window.location.href='home.php'" class="btn-default-i">
                <i class="fa-solid fa-right-to-bracket"></i>
            </button>
        </div>

        <form action="" method="POST" id="formVoluntario"> <!-- Mudado para POST e action vazio -->
            <div id="input_container">
                <div class="input-box">
                    <label for="name" class="form-label">
                        Nome completo
                    </label>  
                    <div class="input-field">
                        <input type="text" name="name" id="name" class="form-control" placeholder="" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>

                <div class="input-box">
                    <label for="birthdate" class="form-label">
                        Data de nascimento
                    </label>
                    <div class="input-field">
                        <input type="date" name="birthdate" id="birthdate" class="form-control" value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>" required>
                    </div>
                </div>

                <div class="input-box">
                    <label for="cpf" class="form-label">
                        CPF
                    </label>
                    <div class="input-field">
                        <input type="tel" name="cpf" id="cpf" class="form-control" placeholder="123.456.789-00" value="<?php echo isset($_POST['cpf']) ? htmlspecialchars($_POST['cpf']) : ''; ?>" required>
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>

                <div class="input-box">
                    <label for="rg" class="form-label">
                        RG
                    </label>
                    <div class="input-field">
                        <input type="tel" name="rg" id="rg" class="form-control" placeholder="" value="<?php echo isset($_POST['rg']) ? htmlspecialchars($_POST['rg']) : ''; ?>" required>
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>

                <div class="input-box">
                    <label for="email" class="form-label">
                        E-mail
                    </label>
                    <div class="input-field">
                        <input type="email" name="email" id="email" class="form-control" placeholder="exemplo@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <div class="input-box">
                    <label for="telefone" class="form-label">
                        Telefone
                    </label>
                    <div class="input-field">
                        <input type="tel" name="telefone" id="telefone" class="form-control" placeholder="(--) 99628-8313" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                </div>

                <div class="radio-container">
                    <label class="form-label">
                        Gênero
                    </label>

                    <div id="gender_inputs">
                        <div class="radio-box">
                            <input type="radio" name="gender" id="female" class="form-control" value="female" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'female' ? 'checked' : ''; ?>>
                            <label for="female" class="form-label">
                                Feminino
                            </label>
                        </div>

                        <div class="radio-box">
                            <input type="radio" name="gender" id="male" class="form-control" value="male" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'male' ? 'checked' : ''; ?>>
                            <label for="male" class="form-label">
                                Masculino
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-default">
                    Enviar
                </button>
            </div>
            <?php if ($erro) echo "<p style='color: red; text-align: center; margin-top: 10px;'>$erro</p>"; ?>
        </form>
    </main>

    <script src="script.js"></script>
</body>

</html>