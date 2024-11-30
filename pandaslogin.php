<?php
// Dados de exemplo para login (usuários e senhas)
$usuarios = [
    "maserati" => ["senha" => "maserati", "expiracao" => "2025-01-25"],
    "baby" => ["senha" => "baby2264", "expiracao" => "2025-01-25"],
];

// Recebe os dados enviados pelo cliente
$dados = json_decode(file_get_contents("php://input"), true);

// Log da entrada para depuração
file_put_contents('log.txt', "Dados recebidos: " . json_encode($dados) . PHP_EOL, FILE_APPEND);

$usuario = $dados['usuario'] ?? '';
$senha = $dados['senha'] ?? '';

if (isset($usuarios[$usuario]) && $usuarios[$usuario]['senha'] === $senha) {
    $dataAtual = date("Y-m-d");
    $dataExpiracao = $usuarios[$usuario]['expiracao'];

    file_put_contents('log.txt', "Data atual: $dataAtual, Data de Expiração: $dataExpiracao" . PHP_EOL, FILE_APPEND);

    if ($dataAtual <= $dataExpiracao) {
        $diasRestantes = (new DateTime($dataExpiracao))->diff(new DateTime($dataAtual))->days;

        file_put_contents('log.txt', "Dias Restantes Calculados: $diasRestantes" . PHP_EOL, FILE_APPEND);

        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Login válido",
            "diasrestantes" => $diasRestantes // Nome em letras minúsculas
        ]);
    } else {
        echo json_encode([
            "status" => "expirado",
            "mensagem" => "Acesso expirado. Entre em contato para renovação."
        ]);
    }
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Usuário ou senha inválidos."
    ]);
}
?>
