<?php 

header("Content-Type: application/json; charset=UTF-8");

require_once 'conexao.php';
require_once 'Model/Autor.php';
require_once 'Controller/autorController.php';

$db = obterConexao();

$autorModel = new Autor($db);

$controller = new AutorController($autorModel);
$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);