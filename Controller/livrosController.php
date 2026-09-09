<?php

class LivrosController {
    private $livrosModel;

    public function __construct($livrosModel) {
        $this->livrosModel = $livrosModel;
    }

    public function processarRequisicao($metodo) {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        switch ($metodo) {
            case 'GET':
                if ($id) {
                    $this->listarLivros($id);
                } else {
                    $this->listarTodos();
                }
                break;

            case 'POST':
                $this->cadastrar();
                break;

            case 'PUT':
                if ($id) {
                    $this->atualizarCompleto($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID é obrigatório para atualização completa"]);
                }
                break;

            case 'PATCH':
                if ($id) {
                    $this->atualizarParcial($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID é obrigatório para atualização parcial"]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $this->deletar($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID é obrigatório para exclusão"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['mensagem' => 'Método não permitido']);
                break;
        }
    }

    public function listarTodos() {
        http_response_code(200);
        echo json_encode($this->livrosModel->listar());
    }

    public function listarLivros($id) {
        $livros = $this->livrosModel->listarLivrosporId($id);

        if ($livros) {
            http_response_code(200);
            echo json_encode($livros);
        } else {
            http_response_code(404);
            echo json_encode(["mensagem" => "Livro não encontrado"]);
        }
    }

    public function cadastrar() {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados['nome']) || empty($dados['genero']) || empty($dados['quantidade_paginas'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Dados incompletos"]);
            return;
        }

        if ($this->livrosModel->cadastrar($dados)) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Livro cadastrado"]);
        } else {
            http_response_code(500);
            echo json_encode(['mensagem' => "Erro ao cadastrar"]);
        }
    }

    public function atualizarCompleto($id) {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados['nome']) || empty($dados['genero']) || empty($dados['quantidade_paginas'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Dados incompletos para atualização completa (PUT)"]);
            return;
        }

        if ($this->livrosModel->atualizar($id, $dados)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Livro atualizado com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao atualizar livro"]);
        }
    }

    public function atualizarParcial($id) {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados)) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Nenhum dado fornecido para atualização"]);
            return;
        }

        if ($this->livrosModel->atualizarParcial($id, $dados)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Livro atualizado parcialmente com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao atualizar parcialmente o livro"]);
        }
    }

    public function deletar($id) {
        if ($this->livrosModel->deletar($id)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Livro removido com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao remover o livro"]);
        }
    }
}