<?php

class HabilidadeUsuario {
  
    private $usuario;
    private $habilidade;
    private $valor;
    
    function __construct($usuario, $habilidade, $valor) {
        $this->usuario = $usuario;
        $this->habilidade = $habilidade;
        $this->valor = $valor;
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
