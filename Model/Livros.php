<?php
class Livros {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT * FROM livros";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarLivrosPorId($id) {
    $queryLivros = "SELECT livro_id AS id, nome_livros, genero_livros, quantidade_paginas_livros FROM livros WHERE livros_id = :id";
    $stmtLivros = $this->conn->prepare($queryLivros);
    $stmtLivros->bindParam(':id', $id);
    $stmtLivros->execute();
    $livros = $stmtLivros->fetch(PDO::FETCH_ASSOC);

    if (!$livros) {
        return false;
    }

    $queryAutor = "SELECT autor_id AS id, livros_id, nome_autor, nacionalidade_autor FROM autor WHERE livros_id = :id";
    $stmtAutor = $this->conn->prepare($queryAutor);
    $stmtAutor->bindParam(':id', $id);
    $stmtAutor->execute();
    $autor = $stmtAutor->fetchAll(PDO::FETCH_ASSOC);

    $livros['autor'] = $autor;

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