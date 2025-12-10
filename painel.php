<?php
session_start();
include('verifica_login.php');
include('conexao.php');


$sql_total = "SELECT COUNT(*) as total FROM alunos";
$result_total = mysqli_query($conexao, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_alunos = $row_total['total'];

$sql_ultimos = "SELECT * FROM alunos ORDER BY data_cadastro DESC LIMIT 5";
$result_ultimos = mysqli_query($conexao, $sql_ultimos);

$sql_cursos = "SELECT curso, COUNT(*) as total FROM alunos GROUP BY curso ORDER BY total DESC";
$result_cursos = mysqli_query($conexao, $sql_cursos);


$sql_mensal = "SELECT 
                DATE_FORMAT(data_cadastro, '%Y-%m') as mes,
                COUNT(*) as total
               FROM alunos 
               GROUP BY DATE_FORMAT(data_cadastro, '%Y-%m')
               ORDER BY mes DESC
               LIMIT 6";
$result_mensal = mysqli_query($conexao, $sql_mensal);


$sql_responsaveis = "SELECT tipo_responsavel, COUNT(*) as total FROM alunos GROUP BY tipo_responsavel";
$result_responsaveis = mysqli_query($conexao, $sql_responsaveis);


$sql_bairros = "SELECT bairro, COUNT(*) as total FROM alunos GROUP BY bairro ORDER BY total DESC LIMIT 5";
$result_bairros = mysqli_query($conexao, $sql_bairros);


$sql_recentes = "SELECT COUNT(*) as total FROM alunos WHERE data_cadastro >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$result_recentes = mysqli_query($conexao, $sql_recentes);
$row_recentes = mysqli_fetch_assoc($result_recentes);
$total_recentes = $row_recentes['total'];

$sql_idades = "SELECT 
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) < 15 THEN 'Menos de 15'
                    WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 15 AND 18 THEN '15-18 anos'
                    WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 19 AND 25 THEN '19-25 anos'
                    ELSE 'Acima de 25'
                END as faixa_etaria,
                COUNT(*) as total
               FROM alunos
               GROUP BY faixa_etaria
               ORDER BY 
                CASE faixa_etaria
                    WHEN 'Menos de 15' THEN 1
                    WHEN '15-18 anos' THEN 2
                    WHEN '19-25 anos' THEN 3
                    ELSE 4
                END";
$result_idades = mysqli_query($conexao, $sql_idades);


$sql_periodo = "SELECT 
                CASE
                    WHEN HOUR(data_cadastro) BETWEEN 6 AND 12 THEN 'Manhã'
                    WHEN HOUR(data_cadastro) BETWEEN 12 AND 18 THEN 'Tarde'
                    WHEN HOUR(data_cadastro) BETWEEN 18 AND 23 THEN 'Noite'
                    ELSE 'Madrugada'
                END as periodo,
                COUNT(*) as total
               FROM alunos
               GROUP BY periodo
               ORDER BY 
                CASE periodo
                    WHEN 'Manhã' THEN 1
                    WHEN 'Tarde' THEN 2
                    WHEN 'Noite' THEN 3
                    ELSE 4
                END";
$result_periodo = mysqli_query($conexao, $sql_periodo);


$sql_curso_top = "SELECT curso, COUNT(*) as total FROM alunos GROUP BY curso ORDER BY total DESC LIMIT 1";
$result_curso_top = mysqli_query($conexao, $sql_curso_top);
$row_curso_top = mysqli_fetch_assoc($result_curso_top);
$curso_top = $row_curso_top['curso'];
$curso_top_total = $row_curso_top['total'];

$sql_resp_top = "SELECT tipo_responsavel, COUNT(*) as total FROM alunos GROUP BY tipo_responsavel ORDER BY total DESC LIMIT 1";
$result_resp_top = mysqli_query($conexao, $sql_resp_top);
$row_resp_top = mysqli_fetch_assoc($result_resp_top);
$resp_top = $row_resp_top['tipo_responsavel'];
$resp_top_total = $row_resp_top['total'];

$sql_crescimento = "SELECT 
                    DATE(data_cadastro) as data,
                    COUNT(*) as total
                   FROM alunos 
                   WHERE data_cadastro >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                   GROUP BY DATE(data_cadastro)
                   ORDER BY data";
$result_crescimento = mysqli_query($conexao, $sql_crescimento);

$cursos_labels = [];
$cursos_data = [];
while($curso = mysqli_fetch_assoc($result_cursos)) {
    $cursos_labels[] = $curso['curso'];
    $cursos_data[] = $curso['total'];
}

$mensal_labels = [];
$mensal_data = [];
while($mes = mysqli_fetch_assoc($result_mensal)) {
    $mensal_labels[] = date('M/Y', strtotime($mes['mes'] . '-01'));
    $mensal_data[] = $mes['total'];
}
$mensal_labels = array_reverse($mensal_labels);
$mensal_data = array_reverse($mensal_data);

$responsaveis_labels = [];
$responsaveis_data = [];
while($resp = mysqli_fetch_assoc($result_responsaveis)) {
    $responsaveis_labels[] = $resp['tipo_responsavel'];
    $responsaveis_data[] = $resp['total'];
}

$bairros_labels = [];
$bairros_data = [];
while($bairro = mysqli_fetch_assoc($result_bairros)) {
    $bairros_labels[] = $bairro['bairro'];
    $bairros_data[] = $bairro['total'];
}

$idades_labels = [];
$idades_data = [];
while($idade = mysqli_fetch_assoc($result_idades)) {
    $idades_labels[] = $idade['faixa_etaria'];
    $idades_data[] = $idade['total'];
}

$periodo_labels = [];
$periodo_data = [];
while($periodo = mysqli_fetch_assoc($result_periodo)) {
    $periodo_labels[] = $periodo['periodo'];
    $periodo_data[] = $periodo['total'];
}

$roxo_cores = [
    '#667eea', '#764ba2', '#5a67d8', '#4c51bf', '#6b46c1',
    '#805ad5', '#9f7aea', '#b794f4', '#d6bcfa', '#e9d8fd'
];

$azul_cores = [
    '#3182ce', '#4299e1', '#63b3ed', '#90cdf4', '#bee3f8',
    '#2b6cb0', '#2c5282', '#2a4365', '#1a365d', '#153e75'
];

$graf_pizza_cores = [
    'rgba(102, 126, 234, 0.9)',    // Roxo médio
    'rgba(118, 75, 162, 0.9)',     // Roxo escuro
    'rgba(113, 127, 255, 0.9)',     // Roxo azulado
    'rgba(76, 81, 191, 0.9)',      // Azul roxo
    'rgba(79, 46, 156, 0.9)'      // Roxo vibrante
];

$graf_linha_cores = [
    'rgba(102, 126, 234, 1)',      // Roxo principal (borda)
    'rgba(102, 126, 234, 0.2)'     // Roxo claro (fundo)
];

$graf_barra_cores = [
    'rgba(0, 132, 255, 0.9)',     // Azul
    'rgba(20, 114, 190, 0.9)',     // Azul claro
    'rgba(73, 179, 255, 0.9)',     // Azul muito claro
    'rgba(185, 228, 255, 0.9)'     // Azul pastel
];

$graf_polar_cores = [
    'rgba(102, 126, 234, 0.8)',    // Roxo
    'rgba(118, 75, 162, 0.8)',     // Roxo escuro
    'rgba(90, 103, 216, 0.8)',     // Roxo azulado
    'rgba(76, 81, 191, 0.8)'       // Azul roxo
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Painel Principal</title>
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .stat-card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.15);
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.08);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4c51bf;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
        }
        .dashboard-icon {
            font-size: 1.3rem;
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            padding: 8px;
            border-radius: 8px;
        }
        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
            color: #4c51bf;
        }
        .stat-label {
            font-size: 0.9rem;
            color: #718096;
            font-weight: 500;
        }
        .card-link {
            text-decoration: none;
            color: inherit;
        }
        .card-link:hover {
            color: inherit;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%) !important;
        }
        .bg-gradient-purple {
            background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%) !important;
        }
        .badge-purple {
            background-color: #667eea;
            color: white;
        }
        .text-purple {
            color: #667eea !important;
        }
        .border-purple {
            border-color: #667eea !important;
        }
        .btn-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-purple:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            color: white;
        }
    </style>
</head>
<body style="background-color: #f7fafc;">
    <?php include('navbar.php'); ?>
    
    <div class="container mt-4">
        <!-- Card de Boas-Vindas -->
        <div class="card welcome-card shadow-lg mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="card-title mb-2"><i class="fas fa-chart-line me-2"></i>Dashboard Acadêmico</h2>
                        <p class="card-text mb-0">Bem-vindo, <?= htmlspecialchars($_SESSION['email'] ?? 'Usuário') ?>! Visualize as estatísticas do sistema.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="bg-white text-purple d-inline-block p-3 rounded-circle">
                            <i class="fas fa-graduation-cap fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de Estatísticas (3 cards) -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <a href="visualizar_alunos.php" class="card-link">
                    <div class="card stat-card bg-gradient-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-number"><?= $total_alunos ?></div>
                                    <div class="stat-label">Total de Alunos</div>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-purple">
                                            <i class="fas fa-database me-1"></i>
                                            <?= mysqli_num_rows($result_cursos) ?> cursos ativos
                                        </span>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-3x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <a href="visualizar_alunos.php" class="card-link">
                    <div class="card stat-card bg-gradient-blue text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stat-number"><?= $total_recentes ?></div>
                                    <div class="stat-label">Novos (7 dias)</div>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-purple">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d/m/Y') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-3x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card stat-card bg-gradient-purple text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?= count($bairros_labels) ?></div>
                                <div class="stat-label">Bairros Atendidos</div>
                                <div class="mt-2">
                                    <span class="badge bg-light text-purple">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Top: <?= htmlspecialchars($bairros_labels[0] ?? 'N/A') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-map-marked-alt fa-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Gráficos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-purple"><i class="fas fa-chart-pie me-2"></i>Gráficos e Análises</h4>
                    <span class="badge badge-purple">
                        <i class="fas fa-sync-alt me-1"></i>
                        Atualizado agora
                    </span>
                </div>
            </div>
        </div>

        <!-- Primeira Linha de Gráficos -->
        <div class="row mb-4">
            <!-- Gráfico 1: Alunos por Curso -->
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-graduation-cap dashboard-icon"></i>
                        Distribuição por Curso
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartCursos" height="250"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Total: <?= $total_alunos ?> alunos em <?= count($cursos_labels) ?> cursos
                        </small>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Evolução Mensal -->
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-calendar-alt dashboard-icon"></i>
                        Evolução de Matrículas (6 meses)
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartMensal" height="250"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-chart-line me-1"></i>
                            Crescimento mensal de matrículas
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Linha de Gráficos -->
        <div class="row mb-4">
            <!-- Gráfico 3: Tipos de Responsável -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-user-friends dashboard-icon"></i>
                        Tipos de Responsável
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartResponsaveis" height="220"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-user-tie me-1"></i>
                            Mais comum: <?= htmlspecialchars($resp_top) ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Gráfico 4: Faixa Etária -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-user-clock dashboard-icon"></i>
                        Distribuição por Idade
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartIdades" height="220"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-birthday-cake me-1"></i>
                            Faixa predominante: <?= htmlspecialchars($idades_labels[0] ?? 'N/A') ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Gráfico 5: Período do Dia -->
            <div class="col-lg-4 mb-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-clock dashboard-icon"></i>
                        Cadastros por Período
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartPeriodo" height="220"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-sun me-1"></i>
                            Período mais ativo: <?= htmlspecialchars($periodo_labels[0] ?? 'N/A') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Informativos -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-white border-purple border-bottom-2">
                        <h5 class="mb-0 text-purple"><i class="fas fa-trophy me-2"></i>Destaques e Rankings</h5>
                    </div>
                    <div class="card-body">
                        <!-- Curso mais popular -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="me-3">
                                        <span class="badge bg-gradient-primary p-2">
                                            <i class="fas fa-crown fa-lg"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Curso Mais Popular</h6>
                                        <p class="mb-0">
                                            <strong class="text-purple"><?= htmlspecialchars($curso_top) ?></strong>
                                            <span class="badge bg-purple float-end"><?= $curso_top_total ?> alunos</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Responsável mais comum -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="me-3">
                                        <span class="badge bg-gradient-blue p-2">
                                            <i class="fas fa-user-check fa-lg"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Responsável Mais Comum</h6>
                                        <p class="mb-0">
                                            <strong class="text-purple"><?= htmlspecialchars($resp_top) ?></strong>
                                            <span class="badge bg-purple float-end"><?= $resp_top_total ?> alunos</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Bairros -->
                        <h6 class="mt-4 mb-3 text-purple"><i class="fas fa-map-marker-alt me-2"></i>Top 5 Bairros</h6>
                        <div class="list-group">
                            <?php 
                            // Re-executar query para bairros
                            $result_bairros2 = mysqli_query($conexao, $sql_bairros);
                            $counter = 1;
                            while($bairro = mysqli_fetch_assoc($result_bairros2)): 
                                $percent = ($bairro['total'] / $total_alunos) * 100;
                            ?>
                            <div class="list-group-item border-0 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-gradient-purple me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                            <?= $counter++ ?>
                                        </span>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($bairro['bairro']) ?></div>
                                            <div class="text-muted small"><?= $bairro['total'] ?> alunos</div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="progress" style="width: 80px; height: 6px;">
                                            <div class="progress-bar bg-gradient-purple" style="width: <?= min($percent, 100) ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= round($percent, 1) ?>%</small>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimos Alunos Cadastrados -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-white border-purple border-bottom-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-purple"><i class="fas fa-clock me-2"></i>Últimos Cadastros</h5>
                            <a href="formulario_aluno.php" class="btn btn-sm btn-purple">
                                <i class="fas fa-plus me-1"></i> Novo
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="table-light">
                                        <th><i class="fas fa-user text-purple"></i> Aluno</th>
                                        <th><i class="fas fa-book text-purple"></i> Curso</th>
                                        <th><i class="fas fa-calendar text-purple"></i> Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($aluno = mysqli_fetch_assoc($result_ultimos)): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-user-circle text-purple"></i>
                                                </div>
                                                <div class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($aluno['nome_completo']) ?>">
                                                    <?= htmlspecialchars($aluno['nome_completo']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-gradient-primary"><?= $aluno['curso'] ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="<?= date('d/m/Y H:i', strtotime($aluno['data_cadastro'])) ?>">
                                                <?= date('d/m', strtotime($aluno['data_cadastro'])) ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="visualizar_alunos.php" class="btn btn-outline-purple">
                                <i class="fas fa-list me-1"></i> Ver todos os alunos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo Estatístico -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white border-purple border-bottom-2">
                        <h5 class="mb-0 text-purple"><i class="fas fa-chart-bar me-2"></i>Resumo Geral do Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded border-purple">
                                    <div class="stat-number"><?= $total_alunos ?></div>
                                    <div class="stat-label">Total de Alunos</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded border-purple">
                                    <div class="stat-number"><?= $total_recentes ?></div>
                                    <div class="stat-label">Última Semana</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded border-purple">
                                    <div class="stat-number"><?= count($cursos_labels) ?></div>
                                    <div class="stat-label">Cursos Ativos</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-3 border rounded border-purple">
                                    <div class="stat-number"><?= count($bairros_labels) ?></div>
                                    <div class="stat-label">Bairros</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Última atualização: <?= date('d/m/Y H:i:s') ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para Gráficos -->
    <script>
        // Gráfico 1: Alunos por Curso (Pizza)
        const ctxCursos = document.getElementById('chartCursos').getContext('2d');
        new Chart(ctxCursos, {
            type: 'pie',
            data: {
                labels: <?= json_encode($cursos_labels) ?>,
                datasets: [{
                    data: <?= json_encode($cursos_data) ?>,
                    backgroundColor: <?= json_encode($graf_pizza_cores) ?>,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            font: {
                                family: 'Segoe UI, system-ui'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(102, 126, 234, 0.9)',
                        titleFont: {
                            size: 14
                        }
                    }
                }
            }
        });

        // Gráfico 2: Evolução Mensal (Linha)
        const ctxMensal = document.getElementById('chartMensal').getContext('2d');
        new Chart(ctxMensal, {
            type: 'line',
            data: {
                labels: <?= json_encode($mensal_labels) ?>,
                datasets: [{
                    label: 'Matrículas',
                    data: <?= json_encode($mensal_data) ?>,
                    borderColor: '<?= $graf_linha_cores[0] ?>',
                    backgroundColor: '<?= $graf_linha_cores[1] ?>',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '<?= $graf_linha_cores[0] ?>',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 13
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)'
                        }
                    }
                }
            }
        });

        // Gráfico 3: Tipos de Responsável (Rosquinha)
        const ctxResponsaveis = document.getElementById('chartResponsaveis').getContext('2d');
        new Chart(ctxResponsaveis, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($responsaveis_labels) ?>,
                datasets: [{
                    data: <?= json_encode($responsaveis_data) ?>,
                    backgroundColor: <?= json_encode($graf_pizza_cores) ?>,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Gráfico 4: Faixa Etária (Barras Horizontais)
        const ctxIdades = document.getElementById('chartIdades').getContext('2d');
        new Chart(ctxIdades, {
            type: 'bar',
            data: {
                labels: <?= json_encode($idades_labels) ?>,
                datasets: [{
                    label: 'Alunos',
                    data: <?= json_encode($idades_data) ?>,
                    backgroundColor: <?= json_encode($graf_barra_cores) ?>,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)'
                        }
                    }
                }
            }
        });

        // Gráfico 5: Período do Dia (Radar)
        const ctxPeriodo = document.getElementById('chartPeriodo').getContext('2d');
        new Chart(ctxPeriodo, {
            type: 'polarArea',
            data: {
                labels: <?= json_encode($periodo_labels) ?>,
                datasets: [{
                    data: <?= json_encode($periodo_data) ?>,
                    backgroundColor: <?= json_encode($graf_polar_cores) ?>,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                scales: {
                    r: {
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)'
                        },
                        ticks: {
                            display: false
                        }
                    }
                }
            }
        });

        // Atualizar automaticamente a página a cada 5 minutos
        setTimeout(function() {
            window.location.reload();
        }, 300000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .btn-outline-purple {
            color: #667eea;
            border-color: #667eea;
        }
        .btn-outline-purple:hover {
            background-color: #667eea;
            color: white;
        }
        .border-bottom-2 {
            border-bottom: 2px solid;
        }
    </style>
</body>
</html>

<?php 
// Liberar resultados
mysqli_free_result($result_total);
mysqli_free_result($result_ultimos);
mysqli_free_result($result_cursos);
mysqli_free_result($result_mensal);
mysqli_free_result($result_responsaveis);
mysqli_free_result($result_bairros);
mysqli_free_result($result_recentes);
mysqli_free_result($result_idades);
mysqli_free_result($result_periodo);
mysqli_free_result($result_curso_top);
mysqli_free_result($result_resp_top);
mysqli_free_result($result_crescimento);
?>