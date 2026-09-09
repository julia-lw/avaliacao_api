<?php 

header("Content-Type: application/json; charset=UTF-8");

require_once 'conexao.php';
require_once 'Model/Livros.php';
require_once 'Controller/livrosController.php';

$db = obterConexao();

$livrosModel = new Livros($db);

$controller = new LivrosController($livrosModel);
$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);