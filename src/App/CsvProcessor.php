<?php

namespace App;

// Classe irá processar o CSV

class CsvProcessor
{
    private string $filePath;
    private string $separator;

    public function __construct(string $filePath, string $separator = ',')
    {
        $this->filePath = $filePath;
        $this->separator = in_array($separator, [',', ';']) ? $separator : ',';
    }

    public function process(): array
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception('Arquivo CSV não encontrado.');
        }

        $data = [];
        $handle = fopen($this->filePath, 'r');

        if ($handle === false) {
            throw new \Exception('Erro ao abrir o arquivo CSV.');
        }

        // Ler a primeira linha como cabeçalho
        $headers = fgetcsv($handle, 0, $this->separator, '"', "\\");

        // Fechar o arquivo
        fclose($handle);

        return [
            'headers' => $headers,
            'data' => $data // Ainda vazio, será preenchido depois
        ];
    }
}
