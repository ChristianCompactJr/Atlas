<?php

class TarefaMacro{

    private $id;
    private $projeto;
    private $nome;
    private $descricao;
    
    
    function __construct($id, $projeto, $nome, $descricao) {
        $this->id = $id;
        $this->projeto = $projeto;
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    
    function getId() {
        return $this->id;
    }

    function getProjeto() {
        return $this->projeto;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }
    
    public function getDescricaoFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->descricao);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProjeto($projeto) {
        $this->projeto = $projeto;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }


    
}
