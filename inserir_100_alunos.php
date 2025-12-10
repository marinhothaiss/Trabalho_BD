<?php
session_start();
include('conexao.php');

// Verificar se é admin (opcional - pode remover se quiser)
if(!isset($_SESSION['email'])) {
    die("Acesso negado. Faça login primeiro.");
}

echo "<h2>Inserindo 100 alunos de teste...</h2>";

// Arrays de dados para gerar alunos aleatórios
$nomes = [
    'João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Souza',
    'Juliana Pereira', 'Lucas Rodrigues', 'Fernanda Almeida', 'Rafael Lima', 'Amanda Ferreira',
    'Bruno Carvalho', 'Carla Ribeiro', 'Diego Martins', 'Patrícia Gomes', 'Marcos Santos',
    'Vanessa Oliveira', 'Thiago Costa', 'Larissa Silva', 'Gustavo Pereira', 'Tatiane Alves',
    'Roberto Nunes', 'Daniela Castro', 'Fábio Mendes', 'Bianca Rocha', 'Ricardo Cardoso',
    'Camila Torres', 'Leandro Moreira', 'Priscila Barbosa', 'Sérgio Cunha', 'Monique Dias',
    'André Lopes', 'Natália Teixeira', 'Vinícius Moraes', 'Letícia Ramos', 'Eduardo Nascimento',
    'Mariana Batista', 'Felipe Guimarães', 'Isabela Freitas', 'Alexandre Coelho', 'Renata Pires'
];

$cursos = [
    'Desenvolvimento de Sistemas', 
    'Informática', 
    'Administração', 
    'Enfermagem'
];

$bairros = [
    'Centro', 'Jardim das Flores', 'Vila Nova', 'Boa Vista', 'São José',
    'Santa Maria', 'Industrial', 'Residencial', 'Universitário', 'Novo Mundo'
];

$ruas = [
    'Rua das Flores', 'Avenida Central', 'Rua dos Andradas', 'Avenida Brasil',
    'Rua São Paulo', 'Avenida Getúlio Vargas', 'Rua Amazonas', 'Avenida Paulista',
    'Rua XV de Novembro', 'Avenida Independência'
];

$tipos_responsavel = ['Pai', 'Mãe', 'Responsável Legal', 'Avô/Avó', 'Tito/Tia', 'Outro'];

$contador = 0;
$erros = 0;

// Limpar tabela antes de inserir (OPCIONAL - descomente se quiser limpar antes)
// mysqli_query($conexao, "TRUNCATE TABLE alunos");

for ($i = 1; $i <= 100; $i++) {
    // Gerar dados aleatórios
    $nome_completo = $nomes[array_rand($nomes)] . " " . rand(1, 99);
    $data_nascimento = date('Y-m-d', strtotime('-'.rand(15, 25).' years -'.rand(0, 365).' days'));
    $rua_nome = $ruas[array_rand($ruas)];
    $numero = rand(1, 9999);
    $bairro = $bairros[array_rand($bairros)];
    $cep = sprintf('%05d', rand(1, 99999)) . '-' . sprintf('%03d', rand(1, 999));
    $nome_responsavel = "Responsável do(a) " . $nome_completo;
    $tipo_responsavel = $tipos_responsavel[array_rand($tipos_responsavel)];
    $curso = $cursos[array_rand($cursos)];
    
    // Inserir no banco
    $sql = "INSERT INTO alunos (nome_completo, data_nascimento, rua, numero, bairro, cep, nome_responsavel, tipo_responsavel, curso) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexao, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssisssss', 
            $nome_completo, $data_nascimento, $rua_nome, $numero, $bairro, 
            $cep, $nome_responsavel, $tipo_responsavel, $curso);
        
        if (mysqli_stmt_execute($stmt)) {
            $contador++;
            echo "✅ Aluno {$i}: {$nome_completo} - {$curso}<br>";
        } else {
            $erros++;
            echo "❌ Erro ao inserir aluno {$i}<br>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $erros++;
        echo "❌ Erro na preparação da query para aluno {$i}<br>";
    }
    
    // Pequena pausa para não sobrecarregar
    usleep(50000); // 0.05 segundos
}

echo "<h3>Resultado:</h3>";
echo "<p>Total de alunos inseridos: <strong>{$contador}</strong></p>";
echo "<p>Total de erros: <strong>{$erros}</strong></p>";
echo "<p><a href='visualizar_alunos.php'>Ver todos os alunos</a> | <a href='painel.php'>Voltar ao painel</a></p>";

// Fechar conexão
mysqli_close($conexao);
?>