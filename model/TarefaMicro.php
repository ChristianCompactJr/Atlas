<?php

class TarefaMicro {
    private $id;
    private $macro;
    private $nome;
    private $descricao;
    private $tempoPrevisto;
    private $linkUteis;
    private $concluida;
    
    function __construct($id, $macro, $nome, $descricao, $tempoPrevisto, $linkUteis, $concluida) {
        $this->id = $id;
        $this->macro = $macro;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->tempoPrevisto = $tempoPrevisto;
        $this->linkUteis = $linkUteis;
        $this->concluida = $concluida;
    }
    
    function getId() {
        return $this->id;
    }

    function getMacro() {
        return $this->macro;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getTempoPrevisto() {
        return $this->tempoPrevisto;
    }

    function getLinkUteis() {
        return $this->linkUteis;
    }

    function getConcluida() {
        return $this->concluida;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setMacro($macro) {
        $this->macro = $macro;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTempoPrevisto($tempoPrevisto) {
        $this->tempoPrevisto = $tempoPrevisto;
    }

    function setLinkUteis($linkUteis) {
        $this->linkUteis = $linkUteis;
    }

    function setConcluida($concluida) {
        $this->concluida = $concluida;
    }



    
}
