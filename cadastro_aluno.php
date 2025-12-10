<?php
session_start();
include('verifica_login.php');
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $dados = [
        'nome_completo' => mysqli_real_escape_string($conexao, trim($_POST['nome_completo'])),
        'data_nascimento' => mysqli_real_escape_string($conexao, trim($_POST['data_nascimento'])),
        'rua' => mysqli_real_escape_string($conexao, trim($_POST['rua'])),
        'numero' => mysqli_real_escape_string($conexao, trim($_POST['numero'])),
        'bairro' => mysqli_real_escape_string($conexao, trim($_POST['bairro'])),
        'cep' => mysqli_real_escape_string($conexao, trim($_POST['cep'])),
        'nome_responsavel' => mysqli_real_escape_string($conexao, trim($_POST['nome_responsavel'])),
        'tipo_responsavel' => mysqli_real_escape_string($conexao, trim($_POST['tipo_responsavel'])),
        'curso' => mysqli_real_escape_string($conexao, trim($_POST['curso']))
    ];

   
    $erros = [];

   
    foreach ($dados as $campo => $valor) {
        if (empty($valor)) {
            $erros[] = "O campo " . ucfirst(str_replace('_', ' ', $campo)) . " é obrigatório.";
        }
    }

  
    $data_nascimento = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
    $hoje = new DateTime();
    if ($data_nascimento > $hoje) {
        $erros[] = "Data de nascimento não pode ser futura.";
    }

  
    if (empty($erros)) {
       
        $sql_verifica = "SELECT COUNT(*) as total FROM alunos 
                         WHERE nome_completo = '{$dados['nome_completo']}' 
                         AND data_nascimento = '{$dados['data_nascimento']}'";
        
        $result_verifica = mysqli_query($conexao, $sql_verifica);
        $row_verifica = mysqli_fetch_assoc($result_verifica);

        if ($row_verifica['total'] > 0) {
            $_SESSION['erro'] = "Já existe um aluno cadastrado com este nome e data de nascimento.";
        } else {
         
            $sql = "INSERT INTO alunos (nome_completo, data_nascimento, rua, numero, bairro, cep, nome_responsavel, tipo_responsavel, curso) 
                    VALUES ('{$dados['nome_completo']}', '{$dados['data_nascimento']}', '{$dados['rua']}', '{$dados['numero']}', 
                            '{$dados['bairro']}', '{$dados['cep']}', '{$dados['nome_responsavel']}', '{$dados['tipo_responsavel']}', 
                            '{$dados['curso']}')";

            if (mysqli_query($conexao, $sql)) {
                $_SESSION['mensagem'] = "Aluno cadastrado com sucesso!";
                
              
                unset($_POST);
            } else {
                $_SESSION['erro'] = "Erro ao cadastrar aluno: " . mysqli_error($conexao);
            }
        }
    } else {
        $_SESSION['erro'] = implode("<br>", $erros);
       
        $_SESSION['dados_form'] = $_POST;
    }

    header('Location: formulario_aluno.php');
    exit();
} else {
    $_SESSION['erro'] = "Método de requisição inválido.";
    header('Location: formulario_aluno.php');
    exit();
}
?>