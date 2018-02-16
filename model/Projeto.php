<?php

class Projeto {
    
    private $id;
    private $nome;
    private $scrumMaster;
    private $inicio;
    private $prazo;
    private $cliente;
    private $backlog;
    private $observacoes;
    private $estagio;
    
    
    function __construct($id, $nome, $scrumMaster, $inicio, $prazo, $cliente, $backlog, $observacoes, $estagio) {
        $this->id = $id;
        $this->nome = $nome;
        $this->scrumMaster = $scrumMaster;
        $this->inicio = $inicio;
        $this->prazo = $prazo;
        $this->cliente = $cliente;
        $this->backlog = $backlog;
        $this->observacoes = $observacoes;
        $this->estagio = $estagio;
    }
    
   
    
    
    

    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getScrumMaster() {
        return $this->scrumMaster;
    }

    function getInicio() {
        return $this->inicio;
    }

    function getPrazo() {
        return $this->prazo;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getBacklog() {
        return $this->backlog;
    }

    function getObservacoes() {
        return $this->observacoes;
    }

    function getEstagio() {
        return $this->estagio;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setScrumMaster($scrumMaster) {
        $this->scrumMaster = $scrumMaster;
    }

    function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    function setPrazo($prazo) {
        $this->prazo = $prazo;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setBacklog($backlog) {
        $this->backlog = $backlog;
    }

    function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    function setEstagio($estagio) {
        $this->estagio = $estagio;
    }


    
}
