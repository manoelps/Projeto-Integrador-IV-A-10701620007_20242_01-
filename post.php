<?php

// Feito a inclusão do arquivo de conexão com o banco de dados
include 'connect.php';

// Verifica se o formulário foi realmente enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Recebendo os dados do formulario e removendo espaços vazios no inicio e fim da string recebida
    $name = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telefone']);
    $message = trim($_POST['mensagem']);

    // Uso do bloco try catch para tratar erros
    try {
        // Chama a função salvarFormulario e passa os parametros de conexao e dados recebidos pelo formulario
        saveForm($connection, $name, $email, $telephone, $message);

        // Chama a função responsavel por exibir mensagem, para exibir a mensagem de sucesso
        displayMessage("Mensagem enviada com sucesso!");
    } catch (Exception $e) {
        // Chama a função responsavel por exibir mensagem, para exibir a mensagem de erro
        displayMessage("Erro: " . $e->getMessage());
    }
}

// Função para exibir mensagens
function displayMessage($message)
{
    // Exibe a mensagem de erro se houver e redireciona o usuario para a pagina inicial do fomulario
    if (!empty($message)) {
        echo "<script>alert('$message'); window.location.href = 'index.html';</script>";
    }
}

// Função para preparar e executar o INSERT
function saveForm($connection, $name, $email, $telephone, $message)
{
    // Removendo todos os parênteses e espaços em branco da formatação do telefone, para armazenar somente dígitos no banco
    $telephone = preg_replace('/\D/', '', $telephone);

    // Prepara o INSERT SQL usando prepared statements
    $query = "INSERT INTO registros (nome, email, telefone, mensagem) VALUES ($1, $2, $3, $4)";

    // Executa a consulta com os valores do formulário
    $result = pg_query_params($connection, $query, array($name, $email, $telephone, $message));

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
