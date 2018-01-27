<?php

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $foto;
    private $administrador;
    private $token;
    
    
    
    function __construct($id, $nome, $email, $foto, $administrador, $token) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->foto = $foto;
        $this->administrador = $administrador;
        $this->token = $token;
    }

    
    
    function getToken() {
        return $this->token;
    }

    function setToken($token) {
        $this->token = $token;
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
