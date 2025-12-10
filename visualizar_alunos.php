<?php
session_start();
include('verifica_login.php');
include('conexao.php');

$sql = "SELECT * FROM alunos ORDER BY data_cadastro DESC";
$resultado = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Visualizar Alunos</title>
    <style>
        .table th {
            background-color: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="container mt-4">
        <h2><i class="fas fa-list me-2"></i>Lista de Alunos Cadastrados</h2>
        <p class="text-muted">Todos os alunos cadastrados no sistema</p>
        
        <?php if(mysqli_num_rows($resultado) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>Data Nasc.</th>
                            <th>Endereço</th>
                            <th>Responsável</th>
                            <th>Curso</th>
                            <th>Data Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($aluno = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?= htmlspecialchars($aluno['nome_completo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?></td>
                                <td>
                                    <?= htmlspecialchars($aluno['rua']) ?>, <?= $aluno['numero'] ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($aluno['bairro']) ?> - CEP: <?= $aluno['cep'] ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($aluno['nome_responsavel']) ?><br>
                                    <small class="text-muted">(<?= $aluno['tipo_responsavel'] ?>)</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= $aluno['curso'] ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($aluno['data_cadastro'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Nenhum aluno cadastrado ainda.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_free_result($resultado); ?>