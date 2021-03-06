<?php

class HabilidadeUsuario {
  
    private $usuario;
    private $habilidade;
    private $valor;
    private $interesse;
    
    
    function __construct($usuario, $habilidade, $valor, $interesse) {
        $this->usuario = $usuario;
        $this->habilidade = $habilidade;
        $this->valor = $valor;
        $this->interesse = $interesse;
    }

    
    function getInteresse() {
        return $this->interesse;
    }

    function setInteresse($interesse) {
        $this->interesse = $interesse;
    }

    
    
    function getUsuario() {
        return $this->usuario;
    }

    function getHabilidade() {
        return $this->habilidade;
    }

    function getValor() {
        return $this->valor;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setHabilidade($habilidade) {
        $this->habilidade = $habilidade;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }


    
}
