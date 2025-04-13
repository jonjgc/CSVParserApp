<?php

header('Content-Type: application/json; charset=utf-8');

// Habilitar exibição de erros para desenvolvimento (remova em produção)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar a classe
require_once __DIR__ . '/../src/App/CsvProcessor.php';

use App\CsvProcessor;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    try {
        // Validar o separador
        $separator = $_POST['separator'] ?? ',';
        if (!in_array($separator, [',', ';'])) {
            throw new Exception('Separador inválido. Use "," ou ";".');
        }

        // Configurar upload
        $uploadDir = __DIR__ . '/../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['csv_file']['name']);

        // Validar tipo de arquivo
        $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (strtolower($fileType) !== 'csv') {
            throw new Exception('Apenas arquivos CSV são permitidos.');
        }

        // Mover o arquivo
        if (!move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadFile)) {
            throw new Exception('Erro ao mover o arquivo.');
        }

        // Processar o CSV
        $processor = new CsvProcessor($uploadFile, $separator);
        $result = $processor->process();

        // Retornar resposta
        echo json_encode([
            'status' => 'success',
            'message' => 'Arquivo processado com sucesso!',
            'data' => $result['products']
        ]);

        // Removendo o arquivo após processar
        unlink($uploadFile);
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