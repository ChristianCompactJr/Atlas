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
    
    
    public function toArray()
    {
        return array('id' => $this->id, 'macro' => $this->macro, 'nome' => $this->nome, 'descricao' => $this->getDescricaoFormatted(), 'tempo' => $this->tempoPrevisto, 'links' => $this->getLinksUteisFormatted(), 'concluida' => $this->concluida);
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

    public function getDescricaoFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->descricao);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
    }
    
    function getTempoPrevisto() {
        return $this->tempoPrevisto;
    }

    function getLinkUteis() {
        return $this->linkUteis;
    }
    
    public function getLinksUteisFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->linkUteis);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
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
