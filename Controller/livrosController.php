<?php
class LivrosController {
    private $livrosModel;

    public function __construct($livrosModel) {
        $this->livrosModel = $livrosModel;
    }

    public function processarRequisicao($metodo) {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $dados = json_decode(file_get_contents("php://input"), true);

        switch ($metodo) {
            case 'GET':
                if ($id) {
                    echo json_encode($this->livrosModel->listarPorId($id));
                } else {
                    echo json_encode($this->livrosModel->listar());
                }
                break;

            case 'POST':
                if ($this->livrosModel->cadastrar($dados)) {
                    http_response_code(201);
                    echo json_encode(["mensagem" => "Livro cadastrado com sucesso!"]);
                }
                break;

            case 'PUT':
                if ($id && $this->livrosModel->atualizar($id, $dados)) {
                    echo json_encode(["mensagem" => "Livro atualizado com sucesso!"]);
                } else {
                    http_response_code(400);
                    echo json_encode(["erro" => "ID não informado ou livro não encontrado."]);
                }
                break;

            case 'DELETE':
                if ($id && $this->livrosModel->deletar($id)) {
                    echo json_encode(["mensagem" => "Livro deletado com sucesso!"]);
                } else {
                    http_response_code(400);
                    echo json_encode(["erro" => "ID não informado."]);
                }
                break;
        }
    }
}