<?php

class UsuarioDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }
    
    
    public function VerificarToken($token)
    {
        $stmt = parent::getCon()->prepare("select * from usuario where token = ? limit 1");
        $stmt->bindValue(1, $token);
        $stmt->execute();
        
        if($stmt->rowCount() == 0)
        {
            return false;
        }
        $resultado = $stmt->fetch();
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->token);
        
    }
    
    function AtualizarToken($usuario, $token)
    {
        $stmt = parent::getCon()->prepare("update usuario set token = ? where id = ?");
        $stmt->bindValue(1, $token);
        $stmt->bindValue(2, $usuario->getId());
        $stmt->execute();
    }
    
    public function Autenticar($email, $senha)    
    {
        $email = parent::LimparString($email);
        $stmt = parent::getCon()->prepare("select * from usuario where lower(email) = lower(?)");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch();
        if($stmt->rowCount() == 0 || !password_verify($senha, $resultado->senha))
        {
            throw new Exception("Email ou senha incorretos.");
        }
        
        
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->token);
    }
    
    public function Cadastrar($nome, $email, $senha, $foto, $administrador)
    {
        $nome = parent::LimparString($nome);
        $email = parent::LimparString($email);
        $senha = parent::LimparString($senha);
        $foto = parent::LimparString($foto);
        $administrador = parent::LimparString($administrador);
        
        $stmt = parent::getCon()->prepare("select * from usuario where lower(email) = lower(?) and ativo = true limit 1");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {
            throw new Exception("Email já sendo utilizado pelo usuário",$stmt->fetch()->nome);
        }
        
        $stmt = parent::getCon()->prepare("insert into usuario(nome, email, senha, foto, administrador) values (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, password_hash($senha, PASSWORD_BCRYPT));
        $stmt->bindValue(4, $foto);
        $stmt->bindValue(5, $administrador);
        $stmt->execute();
    }
    
    
}
