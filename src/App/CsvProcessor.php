<?php

namespace App;

class CsvProcessor
{
    private string $filePath;
    private string $separator;
    private array $requiredColumns = ['nome', 'codigo', 'preco'];

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

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new \Exception('Erro ao abrir o arquivo CSV.');
        }

        // Ler o cabeçalho
        $headers = fgetcsv($handle, 0, $this->separator);
        if ($headers === false || empty($headers)) {
            fclose($handle);
            throw new \Exception('Cabeçalho do CSV inválido ou vazio.');
        }

        // Mapear índices das colunas requeridas
        $columnIndexes = $this->mapColumns($headers);

        // Ler os dados
        $products = [];
        while (($row = fgetcsv($handle, 0, $this->separator)) !== false) {
            if (count($row) >= count($this->requiredColumns)) {
                // Limpar e converter o preço
                $rawPrice = trim($row[$columnIndexes['preco']]);
                $price = $this->convertPrice($rawPrice);

                $product = [
                    'nome' => trim($row[$columnIndexes['nome']]),
                    'codigo' => trim($row[$columnIndexes['codigo']]),
                    'preco' => $price,
                    'is_negative' => $price < 0,
                    'has_even_number' => $this->hasEvenNumber($row[$columnIndexes['codigo']])
                ];
                $products[] = $product;
            }
        }

        fclose($handle);

        // Ordenar por nome
        usort($products, function ($a, $b) {
            return strcmp($a['nome'], $b['nome']);
        });

        return [
            'products' => $products
        ];
    }

    private function mapColumns(array $headers): array
    {
        $indexes = [];
        foreach ($this->requiredColumns as $column) {
            $index = array_search($column, array_map('strtolower', $headers));
            if ($index === false) {
                throw new \Exception("Coluna '$column' não encontrada no CSV.");
            }
            $indexes[$column] = $index;
        }
        return $indexes;
    }

    private function hasEvenNumber(string $code): bool
    {
        preg_match_all('/\d/', $code, $matches);
        $numbers = $matches[0] ?? [];
        foreach ($numbers as $number) {
            if (intval($number) % 2 === 0) {
                return true;
            }
        }
        return false;
    }

    private function convertPrice(string $rawPrice): float
    {
        // Remover espaços iniciais e finais
        $cleanPrice = trim($rawPrice);

        // Verificar se é negativo (antes de remover R$)
        $isNegative = strpos($cleanPrice, '-') === 0;

        // Remover o prefixo "R$" e o sinal "-", se presente
        $cleanPrice = preg_replace('/^(-)?R\$/', '', $cleanPrice);

        // Substituir vírgula por ponto para formato decimal
        $cleanPrice = str_replace(',', '.', $cleanPrice);

        // Converter para float
        $price = floatval($cleanPrice);

        // Aplicar o sinal negativo, se necessário
        return $isNegative ? -$price : $price;
    }
}