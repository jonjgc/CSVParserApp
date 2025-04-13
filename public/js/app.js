$(document).ready(function () {
    $('#uploadButton').click(function () {
        // Validar se o arquivo foi selecionado
        let fileInput = $('#csv_file')[0];
        if (!fileInput.files.length) {
            alert('Por favor, selecione um arquivo CSV.');
            return;
        }

        // Criar FormData para enviar arquivo e separador
        let formData = new FormData();
        formData.append('csv_file', fileInput.files[0]);
        formData.append('separator', $('#separator').val());

        // Enviar requisição AJAX
        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json', // Forçar resposta como JSON
            success: function (response) {
                console.log('Resposta do servidor:', response);
                if (response.status === 'success') {
                    displayProducts(response.data);
                } else {
                    alert('Erro: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erro AJAX:', status, error);
                console.error('Resposta bruta:', xhr.responseText);
                alert('Erro ao enviar o arquivo. Verifique o servidor.');
            }
        });
    });

    // Função para montar a tabela de produtos
    function displayProducts(products) {
        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Preço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
        `;

        products.forEach(product => {
            let rowClass = product.is_negative ? 'negative' : '';
            let button = product.has_even_number
                ? `<button class="copy-btn" data-json='${JSON.stringify({
                      nome: product.nome,
                      codigo: product.codigo,
                      preco: product.preco
                  })}'>Copiar JSON</button>`
                : '';

            html += `
                <tr class="${rowClass}">
                    <td>${product.nome}</td>
                    <td>${product.codigo}</td>
                    <td>R$${product.preco.toFixed(2)}</td>
                    <td>${button}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        $('#result').html(html);

        // Adicionar evento de clique aos botões de copiar
        $('.copy-btn').click(function () {
            let json = $(this).data('json');
            navigator.clipboard.writeText(JSON.stringify(json))
                .then(() => {
                    alert('JSON copiado para a área de transferência!');
                })
                .catch(err => {
                    alert('Erro ao copiar JSON: ' + err);
                });
        });
    }
});