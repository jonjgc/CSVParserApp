# Teste Soulmkt - Processamento de CSV de Produtos

Este projeto é uma aplicação web para processar arquivos CSV contendo informações de produtos (nome, código e preço). Ele permite o upload de arquivos CSV via um formulário, processa os dados no backend e exibe os resultados em uma tabela interativa no frontend, com funcionalidades como ordenação, destaque de preços negativos e cópia de dados em formato JSON.

## Funcionalidades

- **Envio de arquivos CSV com colunas `nome`, `codigo` e `preco`, usando `,` ou `;` como separador**.
- **Suporta preços positivos e negativos no formato `R$100,00` ou `-R$50,00`**.
- **Os produtos são exibidos ordenados alfabeticamente por `nome`**.
- **As Linhas com preços negativos são destacadas em vermelho claro.**
- **Botões para copiar os dados de produtos (nome, código, preço) em formato JSON, disponíveis para produtos na qual o código contém pelo menos um número par.**
- **Também foram feitas validações**:
  - Verifica se o arquivo é CSV.
  - Garante que as colunas obrigatórias (`nome`, `codigo`, `preco`) estão presentes.
  - Valida o separador escolhido (`,` ou `;`).
- **O backend retorna os dados processados em formato JSON, incluindo campos `is_negative` (preço < 0) e `has_even_number` (código com número par).**

## Pré-requisitos

- **PHP**: Versão 7.4 ou superior (testado com PHP 8.4).
- **Servidor web**: Servidor PHP embutido ou um servidor como Apache.
- **Navegador**: Chrome, Firefox ou outro navegador moderno (foi testado usando o navegador microsoft edge).
- **Sistema operacional**: Windows, Linux ou macOS.

## Instalação

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/jonjgc/CSVParserApp.git
   cd Teste-Soulmkt-Desenvolvimento

## Crie a pasta uploads:

Na raiz do projeto, crie uma pasta chamada uploads para armazenar temporariamente os arquivos CSV enviados.
No Windows:
   ```bash
   mkdir uploads
  ```
## Dê permissões de escrita à pasta:

No Windows, clique com o botão direito em uploads > Propriedades > Segurança > Garanta que "Todos" tenha "Controle total".

## Instale o jQuery localmente:

Se preferir evitar o CDN, baixe o jQuery em public/js/:
   ```bash
   curl -o public/js/jquery-3.6.0.min.js https://code.jquery.com/jquery-3.6.0.min.js
  ```

Atualize public/index.html:
   ```bash
   <script src="js/jquery-3.6.0.min.js"></script>
  ```
## Como Executar

Navegue até a pasta public:
   ```bash
  cd public
  ```

Inicie o servidor embutido do PHP:
   ```bash
  php -S localhost:8000
  ```

### Acesse a aplicação:

Abra um navegador e vá para http://localhost:8000.
Você verá um formulário para enviar arquivos CSV.

## Como usar

### Preparar um arquivo CSV:

Crie um arquivo CSV com as colunas nome, codigo e preco. Exemplo (produtos.csv):
   ```bash
  nome,codigo,preco
AeroTech Drone,2L4T8U2V1,R$100,00
AquaMist Plant Mister,8R4L7M2N5,-R$50,00
EmberGlow Fireplace Logs,H2M3W7X9,-R$49,95
  ```

O separador pode ser , ou ;.

### Enviar o CSV:

Na página http://localhost:8000:

Clique em Escolher arquivo e selecione seu CSV.
Escolha o separador (Vírgula (,) ou Ponto e vírgula (;)) no menu suspenso.
Clique em Enviar.

### Visualizar os resultados:

Após o upload, uma tabela será exibida com Colunas: Nome, Código, Preço, Ação.

Produtos vão ser ordenados por nome.

Linhas com preços negativos em vermelho claro.

Botões para copiar JSON para produtos cujo código contém números pares

Clique em Copiar JSON para copiar os dados do produto (nome, código, preço) para a área de transferência.

## Estrutura do projeto

   ```bash
Teste-Soulmkt-Desenvolvimento/
├── public/
│   ├── api.php              # Endpoint backend para processar o CSV
│   ├── index.html           # Página principal com formulário e tabela
│   ├── css/
│   │   └── styles.css       # Estilos da interface
│   ├── js/
│   │   └── app.js           # frontend (AJAX, tabela, copiar JSON)
├── src/
│   └── App/
│       └── CsvProcessor.php # Classe PHP para processar o CSV
├── uploads/                 # Pasta para arquivos CSV temporários
├── tests/                   # (Opcional) Pasta para testes
└── README.md                # Documentação
  ```


## Tecnologias Utilizadas

### Backend:

PHP 8.4 para processamento do CSV.
Classe CsvProcessor para leitura, validação e formatação de dados.

### Frontend:

HTML5 para estrutura.
CSS3 para estilização.
JavaScript com jQuery 3.6.0 para AJAX e manipulação do DOM.


Desenvolvido por Jônatas Camelo


















