<?php

class HabilidadeDAO extends DAO {
    
    public function GetHabilidade($id)
    {
        $id = parent::LimparString($id);
        $stmt = parent::getCon()->prepare("select * from atlas_habilidades where id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $resultado = $stmt->fetch();
        return new Habilidade($resultado->id, $resultado->nome);
    }
    
    public function GetHabilidades()
    {
        $stmt = parent::getCon()->prepare("select * from atlas_habilidades order by nome asc");
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        $habilidades = array();
        foreach($resultado as $habilidade)
        {
            $habilidades[] = new Habilidade($habilidade->id, $habilidade->nome);
        }
        return $habilidades;
    }
    
    public function CriarHabilidade($nome)
    {
        $nome = parent::LimparString($nome);
        $stmt = parent::getCon()->prepare("select * from atlas_habilidades where lower(nome) = lower(?) limit 1");
        $stmt->bindValue(1, $nome);
        $stmt->execute();
        
        if($stmt->rowCount() > 0)
        {
           throw new Exception("Não foi possível criar. Habilidade já existe.");
        }
        
        $stmt = parent::getCon()->prepare("insert into atlas_habilidades(nome) values (?)");
        $stmt->bindValue(1, $nome);
        $stmt->execute();
    }
    
    public function GetHabilidadesUsuario($id)
    {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->GetUsuario($id);
        $id = parent::LimparString($id);
        $habilidadesBD = $this->GetHabilidades();
        
        $stmt = parent::getCon()->prepare("select * from atlas_habilidade_usuario where idusuario = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $habilidadesArmazenadas = $stmt->fetchAll();
        
        
        foreach($habilidadesBD as $hbd)
        {
            $esta = false;
            foreach($habilidadesArmazenadas  as $husuario)
            {
                if($hbd->getId() == $husuario->idhabilidade)
                {
                    $esta = true;
                   
                }
            }
            
            if($esta == false)
            {
                
                $stmt = parent::getCon()->prepare("insert into atlas_habilidade_usuario values (?, ?, ?, ?)");
                $stmt->bindValue(1, $hbd->getId());
                $stmt->bindValue(2, $id);
                $stmt->bindValue(3, 0);
                 $stmt->bindValue(4, false);
                $stmt->execute();
                
            }
        }
        
        
        $stmt = parent::getCon()->prepare("select * from atlas_habilidade_usuario where idusuario = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $atualizadas = $stmt->fetchAll();
        $retorno = array();
        
        foreach($atualizadas  as $att)
        {
            $retorno[] = new HabilidadeUsuario($usuario, $this->GetHabilidade($att->idhabilidade), $att->valor, $att->interesse);
        }
       return $retorno;
    }
    
    
    public function AtulizarHabilidadeUsuario($idusuario, $idhabilidade, $valor, $interesse)
    {
        $idusuario = parent::LimparString($idusuario);
        $idhabilidade = parent::LimparString($idhabilidade);
        $valor = parent::LimparString($valor);
        
        $stmt = parent::getCon()->prepare("update atlas_habilidade_usuario set valor = ?, interesse = ? where idusuario = ? and idhabilidade = ?");
        $stmt->bindValue(1, $valor);
        $stmt->bindValue(2, $interesse);
        $stmt->bindValue(3, $idusuario);
         $stmt->bindValue(4, $idhabilidade);
        $stmt->execute();
    }
    
    public function AtualizarNome($id, $nome)
    {
        $id = parent::LimparString($id);
        $nome = parent::LimparString($nome);
        $stmt = parent::getCon()->prepare("update atlas_habilidades set nome = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }
    
    public function RemoverHabilidade($id)
    {
         $id = parent::LimparString($id);
         
         $stmt = parent::getCon()->prepare("delete from atlas_habilidade_usuario where idhabilidade = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
         
        $stmt = parent::getCon()->prepare("delete from atlas_habilidades where id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
    
}
