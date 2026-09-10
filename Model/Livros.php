<?php
class Livros {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT l.id_livros, l.nome_livros, l.genero_livros, l.quantidade_paginas_livros, a.id_autor, a.nome, a.nacionalidade, a.biografia 
        FROM livros l
        INNER JOIN autor a ON l.Autor_id_autor = a.id_autor";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $livros = [];
        foreach ($resultados as $linha) {
            $livros[] = [
                "id_livros" => $linha['id_livros'],
                "nome_livros" => $linha['nome_livros'],
                "genero_livros" => $linha['genero_livros'],
                "quantidade_paginas" => $linha['quantidade_paginas_livros'],
                "autor" => [
                    "id_autor" => $linha['id_autor'],
                    "nome" => $linha['nome'],
                    "nacionalidade" => $linha['nacionalidade']
                    "biografia" => $linha['biografia']
                ]
            ];
        }
        return $livros;
    }

    public function cadastrar($dados) {
        $query = "INSERT INTO livros (nome_livros, genero_livros, quantidade_paginas_livros) VALUES (:nome_livros, :genero_livros, :quantidade_paginas_livros)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_livros', $dados['nome_livros']);
        $stmt->bindParam(':genero_livros', $dados['genero_livros']);
        $stmt->bindParam(':quantidade_paginas_livros', $dados['quantidade_paginas_livros']);

        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE livros SET nome = :nome, genero = :genero, quantidade_paginas = :quantidade_paginas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_livros', $dados['nome_livros']);
        $stmt->bindParam(':genero_livros', $dados['genero_livros']);
        $stmt->bindParam(':quantidade_paginas_livros', $dados['quantidade_paginas_livros']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function atualizarParcial($id, $dados) {
        $campos = [];
        foreach ($dados as $chave => $valor) {
            $campos[] = "$chave = :$chave";
        }
        $query = "UPDATE livros SET " . implode(', ', $campos) . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        foreach ($dados as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM livros WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}