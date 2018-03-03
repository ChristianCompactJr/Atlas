<?php
class UsuarioDAO extends DAO {
    

    public function GetUsuario($id)
    {
        $id = parent::LimparString($id);
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where id = ? limit 1");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() == 0)
        {
            return false;
        }
        $resultado = $stmt->fetch();
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
    }
    
    public function GetUsuarios()
    {
        $stmt = parent::getCon()->prepare("select * from atlas_usuario order by nome asc");
        $stmt->execute();
        
        $linhas = $stmt->fetchAll();
        $usuarios = array();
        
        foreach($linhas as $resultado)
        {
             $usuarios[] = new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
        }
        return $usuarios;
    }
    
    public function GetTotalUsuarios()
    {
        $stmt = parent::getCon()->prepare("select count(*) as total from atlas_usuario");
        $stmt->execute();
        return $stmt->fetch()->total;
    }
    
    public function ApagarUsuario($id)
    {
        $stmt = parent::getCon()->prepare("select foto from atlas_usuario where id = ?");
         $stmt->bindValue(1, $id);
        $stmt->execute();
        
        EnviadorArquivos::ApagarArquivo($stmt->fetch()->foto);

        $stmt = parent::getCon()->prepare("delete from atlas_usuario where id = ?");
         $stmt->bindValue(1, $id);
        $stmt->execute();
    }
    
    public function GetUsuariosFiltro($inicio, $limite, $like)
    {   

        $stmt = parent::getCon()->prepare("select * from atlas_usuario where nome like ? order by nome asc limit ? offset ? ");
        $stmt->bindValue(1, "%".$like."%");
        $stmt->bindValue(2, $limite);
        $stmt->bindValue(3, $inicio);
        $stmt->execute();

        $linhas = $stmt->fetchAll();
        $usuarios = array();
        
        foreach($linhas as $resultado)
        {
             $usuarios[] = new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
        }
        return $usuarios;
    }
    
    public function VerificarToken($token)
    {
        
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where token = ? limit 1");
        $stmt->bindValue(1, $token);
        $stmt->execute();
        
        if($stmt->rowCount() == 0)
        {
            return false;
        }
        $resultado = $stmt->fetch();
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
        
    }
    
    public function EncontrarUsuarioComEmail($email)
    {
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where lower(email) = lower(?) limit 1");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        
        if($stmt->rowCount() == 0)
        {
            throw new Exception("Usuário com este email não encontrado");
        }
        $resultado = $stmt->fetch();
        return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
    }
    
    public function AdicionarEsqueci($id, $chave)
    {
        
        $agora = date('d-m-Y h:i:s', time());

         $stmt = parent::getCon()->prepare("delete from atlas_usuario_esqueci_senha where idusuario = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $stmt = parent::getCon()->prepare("insert into atlas_usuario_esqueci_senha values (?, ?, STR_TO_DATE(?, '%d-%m-%Y %h:%i:%s'))");
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $chave);
        $stmt->bindValue(3, $agora);
        $stmt->execute();

    }
    
    public function AtualizarSenhaEsqueci ($idusuario, $senha, $chave)
    {
        if($this->ValidarEsqueciSenha($idusuario, $chave))
        {
            $id = parent::LimparString($idusuario);   
            $stmt = parent::getCon()->prepare("update atlas_usuario set senha = ? where id = ?");
            $stmt->bindValue(1, password_hash($senha, PASSWORD_BCRYPT));
            $stmt->bindValue(2, $idusuario);
            $stmt->execute();
            
            $stmt = parent::getCon()->prepare("delete from atlas_usuario_esqueci_senha where idusuario = ?");
            $stmt->bindValue(1, $idusuario);
            $stmt->execute();
            
            $stmt = parent::getCon()->prepare("delete from atlas_usuario_tentativa where ip = ?");
            $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            
            
        }
        else {
            throw new Exception("Houve um erro ao alterar a senha");
        }
        
        
    }
    
    public function ValidarEsqueciSenha($id, $chave)
    {
        $id = parent::LimparString($id);
        
        $stmt = parent::getCon()->prepare("select idusuario, chave, DATE_FORMAT(data_hora, '%d-%m-%Y %h:%i:%s') AS dh from atlas_usuario_esqueci_senha where idusuario = ? limit 1");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $resultado = $stmt->fetch();
        if($resultado != false)
        {
            $agora = date('d-m-Y h:i:s', time());
            
            $diferenca = round(abs(strtotime($agora) - strtotime($resultado->dh)) / 60, 0);
             if($resultado->chave != $chave &&  $diferenca > 1440)
             {
             
                 throw new Exception("Este link não é mais válido. Tente enviar outra solicitação.");
             }
             return true;
        }
        else
        {
            throw new Exception("Solicitação não pode ser concluida. Contacte o suporte ou um adminstrador se você acredita de isso ser um erro.");
        }
       
        
    }
    
    public function AtualizarToken($usuario, $token)
    {
        $stmt = parent::getCon()->prepare("update atlas_usuario set token = ? where id = ?");
        $stmt->bindValue(1, $token);
        $stmt->bindValue(2, $usuario->getId());
        $stmt->execute();
    }
    
    public function Autenticar($email, $senha)    
    {
        $agora = date('d-m-Y h:i:s', time());
        $maximas_tentativas = 15;
        $minutos_para_mt = 30;
        
        $stmt = parent::getCon()->prepare("select ip, tentativas, DATE_FORMAT(data_hora, '%d-%m-%Y %h:%i:%s') AS dh from atlas_usuario_tentativa where ip = ?");
        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->execute();
        $resultadoTentativas = $stmt->fetch();
        if($resultadoTentativas)
        {
            $diferenca = round(abs(strtotime($agora) - strtotime($resultadoTentativas->dh)) / 60, 0);
            if($resultadoTentativas->tentativas >= $maximas_tentativas)
            {
                if($diferenca <= $minutos_para_mt)
                {
                    $calc = $minutos_para_mt - $diferenca;
                    throw new Exception("Muitas tentativas em pouco tempo. Por favor, aguarde ".$calc." minuto(s)");
                }
                else
                {
                    $stmt = parent::getCon()->prepare("update atlas_usuario_tentativa set tentativas = 0 where ip = ?");
                    $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
                    $stmt->execute();
                }
            }
        }
        

        $email = parent::LimparString($email);
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where lower(email) = lower(?) and ativo = true");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch();
        if($stmt->rowCount() == 0 || !password_verify($senha, $resultado->senha))
        {
            if($resultadoTentativas)
            {
                $linha = $stmt->fetch(PDO::FETCH_NUM);
                $stmt = parent::getCon()->prepare("update atlas_usuario_tentativa set tentativas = tentativas + 1, data_hora = STR_TO_DATE(?, '%d-%m-%Y %h:%i:%s') where ip = ?");
                $stmt->bindValue(1, $agora);
                $stmt->bindValue(2, $resultadoTentativas->ip);
                $stmt->execute();
            }
            else
            {
                $stmt = parent::getCon()->prepare("insert into atlas_usuario_tentativa (ip, tentativas, data_hora) values (?, 1, STR_TO_DATE(?, '%d-%m-%Y %h:%i:%s'))");
                $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
                 $stmt->bindValue(2, $agora);
                $stmt->execute();
            }
            
            throw new Exception("Email ou senha incorretos.");
        }
        else
        {
            $stmt = parent::getCon()->prepare("delete from atlas_usuario_tentativa where ip = ?");
            $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            return new Usuario($resultado->id, $resultado->nome, $resultado->email, $resultado->foto, $resultado->administrador, $resultado->ativo);
        }
        
        
        
    }
    
    public function Cadastrar($nome, $email, $senha, $foto, $administrador)
    {
        $nome = parent::LimparString($nome);
        $email = parent::LimparString($email);
        $senha = parent::LimparString($senha);
        $foto = parent::LimparString($foto);
        $administrador = parent::LimparString($administrador);
        
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where lower(email) = lower(?) and ativo = true limit 1");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {
            throw new Exception("Email já sendo utilizado pelo usuário ".$stmt->fetch()->nome);
        }
        
        $stmt = parent::getCon()->prepare("insert into atlas_usuario(nome, email, senha, foto, administrador) values (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, password_hash($senha, PASSWORD_BCRYPT));
        $stmt->bindValue(4, $foto);
        $stmt->bindValue(5, $administrador);
        $stmt->execute();
        $id = parent::getCon()->lastInsertId();
        return $id;
    }
    
    // --Funções de atualização--
    
    public function AtualizarNome($id, $nome)
    {
        $id = parent::LimparString($id);
        $nome = parent::LimparString($nome);
        
        $stmt = parent::getCon()->prepare("update atlas_usuario set nome = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }
    
    public function AtualizarEmail($id, $email)
    {
        $id = parent::LimparString($id);
        $email = parent::LimparString($email);
        
        $stmt = parent::getCon()->prepare("select * from atlas_usuario where lower(email) = lower(?) and ativo = true limit 1");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {
            throw new Exception("Email já sendo utilizado pelo usuário ".$stmt->fetch()->nome);
        }
        
        
        $stmt = parent::getCon()->prepare("update atlas_usuario set email = ? where id = ?");
        $stmt->bindValue(1, $email);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }
    
    public function AtualizarSenha($id, $senha)
    {
        $id = parent::LimparString($id);
        
        $stmt = parent::getCon()->prepare("update atlas_usuario set senha = ? where id = ?");
        $stmt->bindValue(1, password_hash($senha, PASSWORD_BCRYPT));
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }

    public function AtualizarFoto($id, $foto)
    {
        $id = parent::LimparString($id);
        $foto = parent::LimparString($foto);
        
        $stmt = parent::getCon()->prepare("select foto from atlas_usuario where id = ?");
         $stmt->bindValue(1, $id);
        $stmt->execute();
        
        EnviadorArquivos::ApagarArquivo($stmt->fetch()->foto);
        
        
        $stmt = parent::getCon()->prepare("update atlas_usuario set foto = ? where id = ?");
        $stmt->bindValue(1, $foto);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }
    
    public function AtualizarAdministrador($id, $administrador)
    {
         $id = parent::LimparString($id);
        
        if($administrador == false && $this->GetUsuario($id)->getAdministrador() == true)
        {
            $stmt = parent::getCon()->prepare("select count(*) as total from atlas_usuario where administrador = 1 and id  != ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $total = $stmt->fetch()->total;
            if($total <= 0)
            {
                throw new Exception("Alteração interrompida. O sistema deve ter pelo menos 1 administrador.");
            }
        }


        
        $stmt = parent::getCon()->prepare("update atlas_usuario set administrador = ? , ativo = true where id = ?");
        $stmt->bindValue(1, $administrador);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    } 
    public function AtualizarAtivo($id, $ativo)
    {
        $id = parent::LimparString($id);
        
        if($ativo == false)
        {
            try
            {
                $this->AtualizarAdministrador($id, false);          
            }
            catch(Exception $e)
            {
                throw new Exception($e->getMessage());
            }
            
        }
        if($ativo == true)
        {
            $usuario = $this->GetUsuario($id);
            $stmt = parent::getCon()->prepare("select nome from atlas_usuario where lower(email) = lower(?) and ativo = 1 and id != ?");
            $stmt->bindValue(1, $usuario->getEmail());
            $stmt->bindValue(2, $id);
            $stmt->execute();
            
            $resultado = $stmt->fetch();
            
            if($resultado != false)
            {
                throw new Exception("Não foi possivel ativar este usuário pois seu email está sendo usado por ".$resultado->nome);
            }
        }
        
        $stmt = parent::getCon()->prepare("update atlas_usuario set ativo = ? where id = ?");
        $stmt->bindValue(1, $ativo);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }
    
}
