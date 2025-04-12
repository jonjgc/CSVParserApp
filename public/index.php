<?php

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar a classe automaticamente (simples, sem Composer por enquanto)
require_once __DIR__ . '/../src/App/CsvProcessor.php';

use App\CsvProcessor;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    try {
        $separator = $_POST['separator'] ?? ',';
        $uploadDir = __DIR__ . '/../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['csv_file']['name']);

        if (move_uploaded_file(
            $_FILES['csv_file']['tmp_name'],
            $uploadFile
        )) {
            // Processar o CSV
            $processor = new CsvProcessor($uploadFile, $separator);
            $result = $processor->process();
        
            echo json_encode([
                'status' => 'success',
                'message' => 'Arquivo processado com sucesso!',
                'result' => $result
            ]);
        } else {
            throw new Exception('Erro ao mover o arquivo.');
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Requisição inválida. Envie um arquivo CSV via POST.'
    ]);
}

/*

Definir o cabeçalho JSON para todas as respostas.

Habilitei erros para desenvolvimento (desative em produção).

Verificação se a requisição é POST e contém um arquivo (csv_file).

Recebemos o separador enviado pelo formulário (separator).

Movemos o arquivo CSV para a pasta uploads.

Retornamos uma resposta JSON para confirmar o recebimento (por enquanto, apenas para testar).

*/ 