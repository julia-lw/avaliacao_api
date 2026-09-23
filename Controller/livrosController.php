<?php

class LivrosController {
    private $livrosModel;
    private function autorExiste($id_autor) {
        return $this->livrosModel->autorExiste($id_autor);
    }

    public function __construct($livrosModel) {
        $this->livrosModel = $livrosModel;
    }

    public function processarRequisicao($metodo) {
        $id = isset($_GET['id_livros']) ? (int)$_GET['id_livros'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        $dados = json_decode(file_get_contents("php://input"), true);

        switch ($metodo) {
            case 'GET':
                if ($id) {
                    $livro = $this->livrosModel->buscarPorId($id);
                    if ($livro) {
                        http_response_code(200);
                        echo json_encode($livro, JSON_UNESCAPED_UNICODE);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensagem" => "Livro não encontrado"]);
                    }
                } else {
                    http_response_code(200);
                    echo json_encode($this->livrosModel->listar(), JSON_UNESCAPED_UNICODE);
                }
                break;

            case 'POST':
                if (!empty($dados['nome_livros']) && !empty($dados['Autor_id_autor'])) {
                    if ($this->livrosModel->cadastrar($dados)) {
                        http_response_code(201);
                        echo json_encode(["mensagem" => "Livro cadastrado com sucesso!"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["erro" => "Erro ao cadastrar livro."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "Dados incompletos"]);
                }
                break;

                case 'PUT':
                if ($id && !empty($dados['nome_livros'])) {
                    if (!$this->livrosModel->buscarPorId($id)) {
                        http_response_code(404);
                        echo json_encode(["mensagem" => "Livro não encontrado"]);
                        return;
                    }

                    if (isset($dados['Autor_id_autor'])) {
                        $autorExiste = $this->autorExiste($dados['Autor_id_autor']);
                        if (!$autorExiste) {
                            http_response_code(400);
                            echo json_encode(["mensagem" => "O autor informado não existe"]);
                            return;
                        }
                    }

                    if ($this->livrosModel->atualizar($id, $dados)) {
                        http_response_code(200);
                        echo json_encode(["mensagem" => "Livro atualizado com sucesso!"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["erro" => "Erro ao atualizar livro."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID e dados completos são obrigatórios"]);
                }
                break;

            case 'PATCH':
                if ($id && !empty($dados['nome_livros'])) {
                    if (!$this->livrosModel->buscarPorId($id)) {
                        http_response_code(404);
                        echo json_encode(["mensagem" => "Livro não encontrado"]);
                        return;
                    }

                    if (isset($dados['Autor_id_autor'])) {
                        $autorExiste = $this->autorExiste($dados['Autor_id_autor']);
                        if (!$autorExiste) {
                            http_response_code(400);
                            echo json_encode(["mensagem" => "O autor informado não existe"]);
                            return;
                        }
                    }

                    if ($this->livrosModel->atualizar($id, $dados)) {
                        http_response_code(200);
                        echo json_encode(["mensagem" => "Livro atualizado parcialmente com sucesso!"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["erro" => "Erro ao atualizar livro parcialmente."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID e dados completos são obrigatórios"]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    if (!$this->livrosModel->buscarPorId($id)) {
                        http_response_code(404);
                        echo json_encode(["mensagem" => "Livro não encontrado"]);
                    } elseif ($this->livrosModel->deletar($id)) {
                        http_response_code(200);
                        echo json_encode(["mensagem" => "Livro removido com sucesso!"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["erro" => "Erro ao remover livro."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "ID é obrigatório para exclusão"]);
                }
                break;
            }
        }
    }