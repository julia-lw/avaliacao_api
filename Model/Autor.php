<?php
class Autor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
    $query = "SELECT * FROM autor a
              INNER JOIN livros l ON a.livros_id = l.livros_id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function listarAutor($id) {
    $query = "SELECT a.autor_id, a.nome, a.nacionalidade, a.biografia, l.livros_id, l.nome, l.genero, l.quantidade_paginas  
              FROM autor a 
              INNER JOIN livros l ON a.livros_id = l.livros_id 
              WHERE a.autor_id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function cadastrar($dados) {
    $query = "INSERT INTO autor (livros_id, nome_autor, nacionalidade_autor, biografia_autor) VALUES (:livros_id, :nome_autor, :nacionalidade_autor, :biografia_autor)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':livros_id', $dados['livros_id']);
    $stmt->bindParam(':nome_autor', $dados['nome_autor']);
    $stmt->bindParam(':nacionalidade_autor', $dados['nacionalidade_autor']);
    $stmt->bindParam(':biografia_autor', $dados['biografia_autor']);
    return $stmt->execute();
}

    public function atualizar($id, $dados) {
        $query = "UPDATE autor SET tipo = :nome_autor, nacionalidade_autor = :nacionalidade_autor, biografia_autor WHERE autor_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_autor', $dados['nome_autor']);
        $stmt->bindParam(':nacionalidade_autor', $dados['nacionalidade_autor']);
        $stmt->bindParam(':biografia_autor', $dados['biografia_autor']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function atualizarParcial($id, $dados) {
        $campos = [];
        foreach ($dados as $chave => $valor) {
            $campos[] = "$chave = :$chave";
        }
        $query = "UPDATE autor SET " . implode(', ', $campos) . " WHERE autor_id = :id";
        
        $stmt = $this->conn->prepare($query);
        foreach ($dados as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM autor WHERE autor_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}