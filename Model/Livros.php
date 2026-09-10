<?php
class Livros {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT l.id_livros, l.nome_livros, l.genero_livros, l.quantidade_paginas_livros, a.id_autor, a.nome AS autor_nome, a.nacionalidade, a.biografia 
        FROM livros l
        INNER JOIN autor a ON l.Autor_id_autor = a.id_autor";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $livros = [];
        foreach ($resultados as $linha) {
            $livros[] = [
                "id_livros" => (int)$linha['id_livros'],
                "nome_livros" => $linha['nome_livros'],
                "genero_livros" => $linha['genero_livros'],
                "quantidade_paginas_livros" => (int)$linha['quantidade_paginas_livros'],
                "autor" => [
                    "id_autor" => (int)$linha['id_autor'],
                    "nome" => $linha['autor_nome'],
                    "nacionalidade" => $linha['nacionalidade'],
                    "biografia" => $linha['biografia']
                ]
            ];
        }
        return $livros;
    }

    public function cadastrar($dados) {
        $query = "INSERT INTO livros (nome_livros, genero_livros, quantidade_paginas_livros, Autor_id_autor) 
                  VALUES (:nome, :genero, :paginas, :autor_id)";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $dados['nome_livros']);
        $stmt->bindParam(':genero', $dados['genero_livros']);
        $stmt->bindParam(':paginas', $dados['quantidade_paginas_livros']);
        $stmt->bindParam(':autor_id', $dados['Autor_id_autor']);

        return $stmt->execute();
    }
}