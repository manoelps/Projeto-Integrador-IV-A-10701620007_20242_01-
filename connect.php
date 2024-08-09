<?php

// Definição dos parâmetros de conexão com o banco de dados
$host = "localhost";
$port = "5432";
$dbname = "contatos";
$user = "admin";
$password = "postgres";

// Montando a string de conexão com o banco de dados
$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Fazendo a tentativa de conexão com o banco de dados postgres
$connection = pg_connect($connection_string);

//Verifica se a conexão foi bem sucedida ou não
if (!$connection) {
    echo "Erro: " . pg_last_error($connection);
}
