<?php

class Habilidade {
    
    private $id;
    private $nome;
    
    
    function __construct($id, $nome) {
        $this->id = $id;
        $this->nome = $nome;
    }

    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }


    
}
