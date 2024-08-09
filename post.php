<?php

// Feito a inclusão do arquivo de conexão com o banco de dados
include 'connect.php';

// Verifica se o formulário foi realmente enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Recebendo os dados do formulario e removendo espaços vazios no inicio e fim da string recebida
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $mensagem = trim($_POST['mensagem']);

    // Uso do bloco try catch para tratar erros
    try {
        // Chama a função salvarFormulario e passa os parametros de conexao e dados recebidos pelo formulario
        salvarFormulario($connection, $nome, $email, $telefone, $mensagem);

        // Chama a função responsavel por exibir mensagem, para exibir a mensagem de sucesso
        exibirMensagem("Mensagem enviada com sucesso!");
    } catch (Exception $e) {
        // Chama a função responsavel por exibir mensagem, para exibir a mensagem de erro
        exibirMensagem("Erro: " . $e->getMessage());
    }
}

// Função para exibir mensagens
function exibirMensagem($mensagem)
{
    // Exibe a mensagem de erro se houver e redireciona o usuario para a pagina inicial do fomulario
    if (!empty($mensagem)) {
        echo "<script>alert('$mensagem'); window.location.href = 'index.html';</script>";
    }
}

// Função para preparar e executar o INSERT
function salvarFormulario($connection, $nome, $email, $telefone, $mensagem)
{
    // Removendo todos os parênteses e espaços em branco da formatação do telefone, para armazenar somente dígitos no banco
    $telefone = preg_replace('/\D/', '', $telefone);

    // Prepara o INSERT SQL usando prepared statements
    $query = "INSERT INTO registros (nome, email, telefone, mensagem) VALUES ($1, $2, $3, $4)";

    // Executa a consulta com os valores do formulário
    $result = pg_query_params($connection, $query, array($nome, $email, $telefone, $mensagem));

    // Verifica se a inserção foi bem-sucedida
    if (!$result) {
        // Tratar erro de inserção
        throw new Exception("Erro ao inserir registro: " . pg_last_error($connection));
    }

    // Libera o resultado
    pg_free_result($result);

    // Fecha a conexão com o banco de dados PostgreSQL
    pg_close($connection);

    return $result;
}
