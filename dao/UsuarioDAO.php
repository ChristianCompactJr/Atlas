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
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador);
        
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
        date_default_timezone_set('Brazil/East');
        $agora = date('d-m-Y h:i:s', time());
        $maximas_tentativas = 15;
        $minutos_para_mt = 30;
        
        $stmt = parent::getCon()->prepare("select * from usuario_tentativa where ip = ?");
        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->execute();
        $resultadoTentativas = $stmt->fetch();
        if($resultadoTentativas)
        {
            
            
            $diferenca = round(abs(strtotime($agora) - strtotime($resultadoTentativas->data_hora)) / 60, 0);
            if($resultadoTentativas->tentativas >= $maximas_tentativas)
            {
                if($diferenca <= $minutos_para_mt)
                {
                    $calc = $minutos_para_mt - $diferenca;
                    throw new Exception("Muitas tentativas em pouco tempo. Por favor, aguarde ".$calc." minuto(s)");
                }
                else
                {
                    echo $diferenca;
                    $stmt = parent::getCon()->prepare("update usuario_tentativa set tentativas = 0 where ip = ?");
                    $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
                    $stmt->execute();
                }
            }
        }
        

        $email = parent::LimparString($email);
        $stmt = parent::getCon()->prepare("select * from usuario where lower(email) = lower(?)");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch();
        if($stmt->rowCount() == 0 || !password_verify($senha, $resultado->senha))
        {
            if($resultadoTentativas)
            {
                $linha = $stmt->fetch(PDO::FETCH_NUM);
                $stmt = parent::getCon()->prepare("update usuario_tentativa set tentativas = tentativas + 1, data_hora = ? where ip = ?");
                $stmt->bindValue(1, $agora);
                $stmt->bindValue(2, $resultadoTentativas->ip);
                $stmt->execute();
            }
            else
            {
                $stmt = parent::getCon()->prepare("insert into usuario_tentativa (ip, tentativas, data_hora) values (?, 1, ?)");
                $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
                 $stmt->bindValue(2, $agora);
                $stmt->execute();
            }
            
            throw new Exception("Email ou senha incorretos.");
        }
        else
        {
            $stmt = parent::getCon()->prepare("delete from usuario_tentativa where ip = ?");
            $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador);
        }
        
        
        
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
