<?php
class Autor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT * FROM autor";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarAutor($id) {
        $query = "SELECT * FROM autor WHERE id_autor = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($dados) {
        $query = "INSERT INTO autor (nome, nacionalidade, biografia) 
                  VALUES (:nome, :nacionalidade, :biografia)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':nacionalidade', $dados['nacionalidade']);
        $stmt->bindParam(':biografia', $dados['biografia']);
        return $stmt->execute();
    }

    public function atualizar($id, $dados) {
        $query = "UPDATE autor SET nome = :nome, nacionalidade = :nacionalidade, biografia = :biografia WHERE id_autor = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':nacionalidade', $dados['nacionalidade']);
        $stmt->bindParam(':biografia', $dados['biografia']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function atualizarParcial($id, $dados) {
        $campos = [];
        foreach ($dados as $chave => $valor) {
            $campos[] = "$chave = :$chave";
        }
        $query = "UPDATE autor SET " . implode(', ', $campos) . " WHERE id_autor = :id";
        
        $stmt = $this->conn->prepare($query);
        foreach ($dados as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM autor WHERE id_autor = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}