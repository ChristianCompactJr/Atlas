<?php

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $foto;
    private $administrador;
    
    
    
    function __construct($id, $nome, $email, $foto, $administrador) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->foto = $foto;
        $this->administrador = $administrador;
    }

    
    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function setAdministrador($administrador) {
        $this->administrador = $administrador;
    }

        
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getFoto() {
        return $this->foto;
    }

    function getAdministrador() {
        return $this->administrador;
    }


    
}