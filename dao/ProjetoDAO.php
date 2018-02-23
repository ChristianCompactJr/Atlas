<?php


class ProjetoDAO extends DAO {
   

    
    public function GetDevsProjeto($idprojeto)
    {
        $udao = new UsuarioDAO();
        
        $stmt = parent::getCon()->prepare("select idusuario from atlas_projeto_desenvolvedor where idprojeto = ? and ativo = true");
        $stmt->bindValue(1, $idprojeto);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        $retorno = array();
        foreach($resultado as $linha)
        {
            $usuario = $udao->GetUsuario($linha->idusuario);
            $retorno[] = $usuario;
        }
        return $retorno;
        
    }
    public function GetProjeto($idprojeto)
    {
        $idprojeto = parent::LimparString($idprojeto);
        $stmt = parent::getCon()->prepare("select * from atlas_projeto where id = ?");
       $stmt->bindValue(1, $idprojeto);
       $stmt->execute();
       $resultado = $stmt->fetch();
       if($resultado == false)
       {
           throw new Exception("O projeto não existe");
       }
       
       return new Projeto($resultado->id, $resultado->nome, $resultado->scrum_master, $resultado->data_inicio, $resultado->prazo, $resultado->cliente, $resultado->backlog, $resultado->observacoes, $resultado->estagio);
       
    }
    
    public function ApagarProjeto($idprojeto)
    {
        $idprojeto = parent::LimparString($idprojeto);
        $stmt = parent::getCon()->prepare("delete from atlas_projeto where id = ?");
       $stmt->bindValue(1, $idprojeto);
       $stmt->execute();
    }
   public function GetTotalTarefasMicro($idprojeto)
   {
       $stmt = parent::getCon()->prepare("select count(*) as total from atlas_projeto_tarefa_micro as micro where exists(select * from atlas_projeto_tarefa_macro as macro where micro.idmacro = macro.id and exists(select * from atlas_projeto as ap where ap.id = ? and macro.idprojeto = ap.id))");
       $stmt->bindValue(1, $idprojeto);
       $stmt->execute();
       return $stmt->fetch()->total;
   }
   
   public function AtualizarProjeto($id, $nome, $cliente, $master, $equipe, $inicio, $prazo, $backlog, $obs, $estagio)
   {
       $id = parent::LimparString($id);
        $inicio = parent::LimparString($inicio);
        $prazo = parent::LimparString($prazo);
        
        $inicio = str_replace('/', '-', $inicio);

        $prazo = str_replace('/', '-', $prazo);
         if(strtotime($inicio) > strtotime($prazo))
         {
            throw new Exception("A data de inicio do projeto deve ser mais nova que o prazo do projeto");
         }
         
          $nome = parent::LimparString($nome);
        $cliente = parent::LimparString($cliente);
        $master = parent::LimparString($master);
        
        for($i = 0; $i < count($equipe); $i++)
        {
            $equipe[$i] = parent::LimparString($equipe[$i]);
        }
        
        $backlog = parent::LimparString($backlog);
        $obs = parent::LimparString($obs);
        $estagio = parent::LimparString($estagio);
        
        $stmt = parent::getCon()->prepare("update atlas_projeto set nome = ?, scrum_master = ?, data_inicio = ?, prazo = ?, cliente = ?, backlog = ?, observacoes = ?, estagio = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $master);
        $stmt->bindValue(3, $inicio);
        $stmt->bindValue(4, $prazo);
        $stmt->bindValue(5, $cliente);
        $stmt->bindValue(6, $backlog);
        $stmt->bindValue(7, $obs);
        $stmt->bindValue(8, $estagio);
        $stmt->bindValue(9, $id);
        $stmt->execute();
        
        $stmt = parent::getCon()->prepare("select * from atlas_projeto_desenvolvedor where idprojeto = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        
        foreach($resultado as $desenvolvedor)
        {
            $esta = false;
            foreach($equipe as $atualizado)
            {
                if($atualizado == $desenvolvedor->idusuario)
                {
                    $esta = true;
                    break;
                }
            }
            if($esta == false)
            {
                $stmt = parent::getCon()->prepare("update atlas_projeto_desenvolvedor set ativo = false where idprojeto = ? and idusuario = ?");
                $stmt->bindValue(1, $id);
                 $stmt->bindValue(2, $desenvolvedor->idusuario);
                $stmt->execute();
            }
            
        }
        
        foreach($equipe as $atualizado)
        {
            $esta = false;
            foreach($resultado as $desenvolvedor)
            {
                if($atualizado == $desenvolvedor->idusuario)
                {
                    $esta = true;
                    if($desenvolvedor->ativo == false)
                    {
                        $stmt = parent::getCon()->prepare("update atlas_projeto_desenvolvedor set ativo = true where idprojeto = ? and idusuario = ?");
                        $stmt->bindValue(1, $id);
                        $stmt->bindValue(2, $desenvolvedor->idusuario);
                        $stmt->execute();
                    }
                    
                    
                    break;
                }
            }
            if($esta == false)
            {
                $stmt = parent::getCon()->prepare("insert into atlas_projeto_desenvolvedor values(?, ?, true)");
                $stmt->bindValue(1, $id);
                 $stmt->bindValue(2, $atualizado);
                $stmt->execute();
            }
            
        }
        
   }
   
   public function GetTotalTarefasMicroConcluidas($idprojeto)
   {
       $stmt = parent::getCon()->prepare("select count(*) as total from atlas_projeto_tarefa_micro as micro where micro.concluida = true and exists(select * from atlas_projeto_tarefa_macro as macro where micro.idmacro = macro.id and exists(select * from atlas_projeto as ap where ap.id = ? and macro.idprojeto = ap.id))");
       $stmt->bindValue(1, $idprojeto);
       $stmt->execute();
       return $stmt->fetch()->total;
   }
   
    public function GetProjetos()
    {
        $stmt = parent::getCon()->prepare("select * from atlas_projeto");
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        $retorno = array();
        
        foreach($resultados as $resultado)
        {
            $retorno[] = new Projeto($resultado->id, $resultado->nome, $resultado->scrum_master, $resultado->data_inicio, $resultado->prazo, $resultado->cliente, $resultado->backlog, $resultado->observacoes, $resultado->estagio);
        }
        return $retorno;
    }
    
    
    public function CriarProjeto($nome, $cliente, $master, $equipe, $inicio, $prazo, $backlog, $obs, $estagio)
    {
        $inicio = parent::LimparString($inicio);
        $prazo = parent::LimparString($prazo);
        
        $inicio = str_replace('/', '-', $inicio);

        $prazo = str_replace('/', '-', $prazo);
         if(strtotime($inicio) > strtotime($prazo))
         {
            throw new Exception("A data de inicio do projeto deve ser mais nova que o prazo do projeto");
         }
        
        
        $nome = parent::LimparString($nome);
        $cliente = parent::LimparString($cliente);
        $master = parent::LimparString($master);
        
        for($i = 0; $i < count($equipe); $i++)
        {
            $equipe[$i] = parent::LimparString($equipe[$i]);
        }
        
        $backlog = parent::LimparString($backlog);
        $obs = parent::LimparString($obs);
        $estagio = parent::LimparString($estagio);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto(nome ,scrum_master, data_inicio, prazo, cliente, backlog, observacoes, estagio) values (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $master);
        $stmt->bindValue(3, $inicio);
        $stmt->bindValue(4, $prazo);
        $stmt->bindValue(5, $cliente);
        $stmt->bindValue(6, $backlog);
        $stmt->bindValue(7, $obs);
        $stmt->bindValue(8, $estagio);
        $stmt->execute();
        
        $stmt = parent::getCon()->prepare("select id from atlas_projeto where id = LAST_INSERT_ID() limit 1");
        $stmt->execute();
        
        $id = $stmt->fetch()->id;
        
        foreach($equipe as $dev)
        {
            $stmt = parent::getCon()->prepare("insert into atlas_projeto_desenvolvedor values (?, ?, true)");
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $dev);
            $stmt->execute();
        }
    }
    
    
    public function GetProjetosUsuarioMaster($idusuario)
    {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->GetUsuario($idusuario);
        $stmt = parent::getCon()->prepare("select * from atlas_projeto where scrum_master = ?");
        $stmt->bindValue(1, $idusuario);
        $stmt->execute();
        $retorno = array();
        $projetousuario = $stmt->fetchAll();
        if($projetousuario != false)
        {
            foreach($projetousuario as $pj)
            {
                $retorno[] = new Projeto($pj->id, $pj->nome, $usuario, $pj->data_inicio, $pj->prazo, $pj->cliente, $pj->backlog, $pj->observacoes, $pj->estagio);
            }
        }
        

        
        return $retorno;
        
    }
    
    
    public function GetProjetosUsuarioDev($idusuario)
    {
         $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->GetUsuario($idusuario);
        
        $stmt = parent::getCon()->prepare("select * from atlas_projeto as ap where exists(select * from atlas_projeto_desenvolvedor as apd where apd.idusuario = ? and apd.idprojeto = ap.id and apd.ativo = true)");
        $stmt->bindValue(1, $idusuario);
        $stmt->execute();
        
        $projetousuario = $stmt->fetchAll();
        
        $retorno = array();
        foreach($projetousuario as $pj)
        {
            $retorno[] = new Projeto($pj->id, $pj->nome, $usuario, $pj->data_inicio, $pj->prazo, $pj->cliente, $pj->backlog, $pj->observacoes, $pj->estagio);
        }
        
        return $retorno;
        
        
    }
    
    
}
