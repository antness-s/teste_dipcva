<?php
session_start();

// Verifica se o usuário está logado e tem permissão de administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../../login.php");
    exit();
}

include '../../backend/conexao.php';

// Contagem de voluntarios
$sql_voluntarios = "SELECT COUNT(*) as total FROM voluntarios";
$result_voluntarios = $conexao->query($sql_voluntarios);
$total_voluntarios = $result_voluntarios->fetch_assoc()['total'] ?? 0;

$sql_ativos = "SELECT COUNT(*) as ativos FROM voluntarios WHERE status = 'ativo'";
$result_ativos = $conexao->query($sql_ativos);
$ativos = $result_ativos->fetch_assoc()['ativos'] ?? 0;

$sql_pendentes = "SELECT COUNT(*) as pendentes FROM voluntarios WHERE status = 'pendente'";
$result_pendentes = $conexao->query($sql_pendentes);
$pendentes = $result_pendentes->fetch_assoc()['pendentes'] ?? 0;

// Dados da tabela
$sql_dados = "SELECT nome, status, data_cadastro FROM voluntarios LIMIT 3";
$result_dados = $conexao->query($sql_dados);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <header>
        <h1>Dashboard do Gestor</h1>
        <nav>
            <a href="index.php" class="navlinks">Dashboard</a>
            <a href="../Pag6-Cad.acp/index.php" class="navlinks">Cadastrar Acampamento</a>
        </nav>
    </header>
    <section class="filtros">
        <label for="tipos"><h3>Tipo de Acampamento:</h3></label>
        <select id="tipos" class="tipos">
            <option value="juvenil">Juvenil</option>
            <option value="adulto">Adulto</option>
            <option value="infantil">Infantil</option>
        </select>
    </section>
    <section class="cards">
        <div class="card">
            <h2>Voluntários</h2>
            <p id="total-voluntarios"><?php echo $total_voluntarios; ?></p>
        </div>
        <div class="card">
            <h2>Voluntários Ativos</h2>
            <p id="voluntarios-ativos"><?php echo $ativos; ?></p>
        </div>
        <div class="card">
            <h2>Pendentes</h2>
            <p id="voluntarios"><?php echo $pendentes; ?></p>
        </div>
    </section>
    <section class="tabela-dados">
        <h2>Detalhamento dos Dados</h2>
        <table class="tabela-dados-style">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Data de Cadastro</th>
                    <!-- Removido 'Função' pois não existe -->
                </tr>
            </thead>
            <tbody id="dados-tabela">
                <?php
                if ($result_dados && $result_dados->num_rows > 0) {
                    while ($row = $result_dados->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nome'] ?? 'Não informado') . "</td>";
                        echo "<td>" . htmlspecialchars($row['status'] ?? 'Não informado') . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_cadastro'] ?? 'Não informado') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhum dado disponível</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>