<?php

class Sprint {
    
    private $id;
    private $nome;
    private $projeto;
    private $data_inicio;
    private $prazo;
    private $estagio;
    
    
    function __construct($id, $projeto, $nome, $data_inicio, $prazo, $estagio) {
        $this->id = $id;
        $this->nome = $nome;
        $this->projeto = $projeto;
        $this->data_inicio = $data_inicio;
        $this->prazo = $prazo;
        $this->estagio = $estagio;
    }   
    
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

        
    function getEstagio() {
        return $this->estagio;
    }

    function setEstagio($estagio) {
        $this->estagio = $estagio;
    }

        
         
    
    function getId() {
        return $this->id;
    }

    function getProjeto() {
        return $this->projeto;
    }

    function getData_Inicio() {
        return $this->data_inicio;
    }
    
    function getData_InicioFormatted()
    {
        return date("d/m/Y", strtotime($this->data_inicio));
    }
    function getPrazoFormatted()
    {
         return date("d/m/Y", strtotime($this->prazo));
    }

    function getPrazo() {
        return $this->prazo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProjeto($projeto) {
        $this->projeto = $projeto;
    }

    function setData_inicio($data_inicio) {
        $this->data_inicio = $data_inicio;
    }

    function setPrazo($prazo) {
        $this->prazo = $prazo;
    }


    
    
    
}
