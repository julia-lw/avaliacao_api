<?php

class AutorController {
    private $autorModel;

    public function __construct($autorModel) {
        $this->autorModel = $autorModel;
    }

    public function processarRequisicao($metodo) {
        $id = isset($_GET['autor_id']) ? (int)$_GET['autor_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);

        switch ($metodo) {
            case 'GET':
                if ($id) {
                    $this->listarAutor($id);
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
        echo json_encode($this->autorModel->listar());
    }

    public function listarAutor($id) {
        $autor = $this->autorModel->listarAutor($id);

        if ($autor) {
            http_response_code(200);
            echo json_encode($autor);
        } else {
            http_response_code(404);
            echo json_encode(["mensagem" => "Autor não encontrado"]);
        }
    }

    public function cadastrar() {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados['nome']) || empty($dados['nacionalidade']) || empty($dados['biografia'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Dados incompletos"]);
            return;
        }

        if ($this->autorModel->cadastrar($dados)) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Autor Cadastrado"]);
        } else {
            http_response_code(500);
            echo json_encode(['mensagem' => "Erro ao cadastrar"]);
        }
    }

    public function atualizarCompleto($id) {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados['nome']) || empty($dados['nacionalidade']) || empty($dados['biografia'])) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Dados incompletos para atualização completa (PUT)"]);
            return;
        }

        if ($this->autorModel->atualizar($id, $dados)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Autor atualizado com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao atualizar autor"]);
        }
    }

    public function atualizarParcial($id) {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (empty($dados)) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Nenhum dado fornecido para atualização"]);
            return;
        }

        if ($this->autorModel->atualizarParcial($id, $dados)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Autor atualizado parcialmente com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao atualizar parcialmente o autor"]);
        }
    }

    public function deletar($id) {
        if ($this->autorModel->deletar($id)) {
            http_response_code(200);
            echo json_encode(["mensagem" => "Autor removido com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensagem" => "Erro ao remover o autor"]);
        }
    }
}