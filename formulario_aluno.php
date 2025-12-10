<?php
session_start();
include('verifica_login.php');
include('conexao.php');


$dados_form = isset($_SESSION['dados_form']) ? $_SESSION['dados_form'] : [];
unset($_SESSION['dados_form']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Cadastro de Aluno</title>
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .form-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-cadastrar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        .btn-cadastrar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Cadastro de Aluno</h3>
                                <p class="mb-0 mt-1 opacity-75">Preencha todos os campos abaixo</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-database me-1"></i>
                                    <?php
                                    $sql_count = "SELECT COUNT(*) as total FROM alunos";
                                    $result_count = mysqli_query($conexao, $sql_count);
                                    $row_count = mysqli_fetch_assoc($result_count);
                                    echo $row_count['total'] . " alunos cadastrados";
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                      
                        <?php if(isset($_SESSION['mensagem'])): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <?= $_SESSION['mensagem']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['mensagem']); ?>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['erro'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= $_SESSION['erro']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['erro']); ?>
                        <?php endif; ?>

                        <form action="cadastro_aluno.php" method="POST" id="formAluno">
                           
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user"></i>
                                    Dados Pessoais do Aluno
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="nome_completo" class="form-label">Nome Completo *</label>
                                            <input type="text" class="form-control" id="nome_completo" name="nome_completo" 
                                                   required placeholder="Digite o nome completo do aluno"
                                                   value="<?= isset($dados_form['nome_completo']) ? htmlspecialchars($dados_form['nome_completo']) : '' ?>">
                                            <div class="form-text">Nome completo como consta no documento oficial</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
                                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" 
                                                   required max="<?= date('Y-m-d'); ?>"
                                                   value="<?= isset($dados_form['data_nascimento']) ? htmlspecialchars($dados_form['data_nascimento']) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-home"></i>
                                    Endereço
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rua" class="form-label">Rua *</label>
                                            <input type="text" class="form-control" id="rua" name="rua" 
                                                   required placeholder="Nome da rua"
                                                   value="<?= isset($dados_form['rua']) ? htmlspecialchars($dados_form['rua']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="numero" class="form-label">Número *</label>
                                            <input type="text" class="form-control" id="numero" name="numero" 
                                                   required placeholder="Nº"
                                                   value="<?= isset($dados_form['numero']) ? htmlspecialchars($dados_form['numero']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="cep" class="form-label">CEP *</label>
                                            <input type="text" class="form-control" id="cep" name="cep" 
                                                   required placeholder="00000-000" maxlength="9"
                                                   value="<?= isset($dados_form['cep']) ? htmlspecialchars($dados_form['cep']) : '' ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="bairro" class="form-label">Bairro *</label>
                                            <input type="text" class="form-control" id="bairro" name="bairro" 
                                                   required placeholder="Nome do bairro"
                                                   value="<?= isset($dados_form['bairro']) ? htmlspecialchars($dados_form['bairro']) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                         
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user-friends"></i>
                                    Dados do Responsável
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="nome_responsavel" class="form-label">Nome do Responsável *</label>
                                            <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" 
                                                   required placeholder="Nome completo do responsável"
                                                   value="<?= isset($dados_form['nome_responsavel']) ? htmlspecialchars($dados_form['nome_responsavel']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tipo_responsavel" class="form-label">Tipo de Responsabilidade *</label>
                                            <select class="form-select" id="tipo_responsavel" name="tipo_responsavel" required>
                                                <option value="">Selecione...</option>
                                                <option value="Pai" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Pai') ? 'selected' : '' ?>>Pai</option>
                                                <option value="Mãe" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Mãe') ? 'selected' : '' ?>>Mãe</option>
                                                <option value="Responsável Legal" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Responsável Legal') ? 'selected' : '' ?>>Responsável Legal</option>
                                                <option value="Avô/Avó" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Avô/Avó') ? 'selected' : '' ?>>Avô/Avó</option>
                                                <option value="Tio/Tia" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Tio/Tia') ? 'selected' : '' ?>>Tio/Tia</option>
                                                <option value="Outro" <?= (isset($dados_form['tipo_responsavel']) && $dados_form['tipo_responsavel'] == 'Outro') ? 'selected' : '' ?>>Outro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-book"></i>
                                    Dados Acadêmicos
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="curso" class="form-label">Curso *</label>
                                            <select class="form-select" id="curso" name="curso" required>
                                                <option value="">Selecione um curso...</option>
                                                <option value="Desenvolvimento de Sistemas" <?= (isset($dados_form['curso']) && $dados_form['curso'] == 'Desenvolvimento de Sistemas') ? 'selected' : '' ?>>Desenvolvimento de Sistemas</option>
                                                <option value="Informática" <?= (isset($dados_form['curso']) && $dados_form['curso'] == 'Informática') ? 'selected' : '' ?>>Informática</option>
                                                <option value="Administração" <?= (isset($dados_form['curso']) && $dados_form['curso'] == 'Administração') ? 'selected' : '' ?>>Administração</option>
                                                <option value="Enfermagem" <?= (isset($dados_form['curso']) && $dados_form['curso'] == 'Enfermagem') ? 'selected' : '' ?>>Enfermagem</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status do Cadastro</label>
                                            <div class="form-control bg-light">
                                                <i class="fas fa-calendar me-2"></i>
                                                Data atual: <?= date('d/m/Y'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                       
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <button type="reset" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-eraser me-2"></i>Limpar Formulário
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-cadastrar w-100">
                                        <i class="fas fa-save me-2"></i>Cadastrar Aluno
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Campos marcados com * são obrigatórios
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });

        
        document.getElementById('data_nascimento').addEventListener('change', function(e) {
            const birthDate = new Date(e.target.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            
            if (age < 5) {
                alert('O aluno deve ter pelo menos 5 anos de idade.');
                e.target.value = '';
            }
        });

       
        document.getElementById('formAluno').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cadastrando...';
        });
    </script>
</body>
</html>