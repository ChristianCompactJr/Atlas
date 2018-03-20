<?php


class ProjetoDAO extends DAO {
   

    
    public function AddToBurndown($projeto, $idmicro, $novoestado)
    {
        $microdao = new TarefaMicroDAO();
        $micro = $microdao->getTarefa($idmicro);
        $estado = $micro->getEstado();
        $estimativa = $micro->getEstimativa();
        
         $dia = date('Y-m-d', time());
        $stmt = parent::getCon()->prepare("select * from atlas_projeto_burndown where idprojeto = ? and idmicro = ? and dia = ? ");
        $stmt->bindValue(1, $projeto);
        $stmt->bindValue(2, $idmicro);
        $stmt->bindValue(3, $dia);
        $stmt->execute();
        
        if($stmt->rowCount() <= 0)
        {
            $stmt = parent::getCon()->prepare("insert into atlas_projeto_burndown (idprojeto, idmicro, dia, estado_original_dia) values (?, ?, ?, ?)");
            $stmt->bindValue(1, $projeto);
            $stmt->bindValue(2, $idmicro);
            $stmt->bindValue(3, $dia);
            $stmt->bindValue(4, $estado);
            $stmt->execute();
            
            $stmt = parent::getCon()->prepare("select * from atlas_projeto_burndown where idprojeto = ? and idmicro = ? and dia = ? ");
            $stmt->bindValue(1, $projeto);
            $stmt->bindValue(2, $idmicro);
            $stmt->bindValue(3, $dia);
            $stmt->execute();
        }
        
        $estado = $stmt->fetch()->estado_original_dia;
        
        if($estado == 'Incompleta')
        {
            $pseudovalor = $estimativa;
        }
        else if($estado == 'Instável')
        {
            $pseudovalor = ($estimativa / 2);
        }
        else if($estado == 'Qualificada')
        {
            $pseudovalor = 0;
        }
        else
        {
            return;
        }
        
        if($novoestado == 'Incompleta')
        {
            $pseudovalor2 = $estimativa;
        }
        else if($novoestado == 'Instável')
        {
            $pseudovalor2 = ($estimativa / 2);
        }
        else if($novoestado == 'Qualificada')
        {
            $pseudovalor2 = 0;
        }
        else
        {
            return;
        }
        
        $valor = -($pseudovalor) + $pseudovalor2;  
          
        $stmt = parent::getCon()->prepare("update atlas_projeto_burndown set valor = ? where idprojeto = ? and idmicro = ? and dia = ? ");
        $stmt->bindValue(2, $projeto);
        $stmt->bindValue(3, $idmicro);
        $stmt->bindValue(4, $dia);
        $stmt->bindValue(1, $valor);
        $stmt->execute();
        
    }
    
    public function GetDatasBurndown($projeto)
    {
        $stmt = parent::getCon()->prepare("select distinct dia from atlas_projeto_burndown where idprojeto = ?");
        $stmt->bindValue(1, $projeto);
        $stmt->execute();
        
        $retorno = array();
        
        while($resultado = $stmt->fetch())
        {
            $retorno[] = $resultado->dia;
        }
        
        return $retorno;
    }
    
    public function GetValorDiaBurdown($projeto, $dia, $total = false)
    { 
        $stmt = parent::getCon()->prepare("select valor from atlas_projeto_burndown where idprojeto = ? and dia = ?");
        $stmt->bindValue(1, $projeto);
        $stmt->bindValue(2, $dia);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        
        if($total == true)
        {
            $total = 0;
            foreach($resultado as $r)
            {
                $total += $r->valor;
            }
            return $total;
        }
        else
        {
            $retorno = array();
            foreach($resultado as $r)
            {
                $retorno[] = $r->valor;
            }
            return $retorno;
        }
        
    }
    
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
       
       return new Projeto($resultado->id, $resultado->nome, $resultado->scrum_master, $resultado->data_inicio, $resultado->prazo, $resultado->cliente, $resultado->observacoes, $resultado->estagio);
       
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
   
   public function AtualizarProjeto($id, $nome, $cliente, $master, $equipe, $inicio, $prazo, $obs, $estagio)
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
        
        $obs = parent::LimparString($obs);
        $estagio = parent::LimparString($estagio);
        
        $stmt = parent::getCon()->prepare("update atlas_projeto set nome = ?, scrum_master = ?, data_inicio = STR_TO_DATE(?, '%d-%m-%Y'), prazo = STR_TO_DATE(?, '%d-%m-%Y'), cliente = ?, observacoes = ?, estagio = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $master);
        $stmt->bindValue(3, $inicio);
        $stmt->bindValue(4, $prazo);
        $stmt->bindValue(5, $cliente);
        $stmt->bindValue(6, $obs);
        $stmt->bindValue(7, $estagio);
        $stmt->bindValue(8, $id);
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
       $stmt = parent::getCon()->prepare("select count(*) as total from atlas_projeto_tarefa_micro as micro where micro.estado = 'Concluída' and exists(select * from atlas_projeto_tarefa_macro as macro where micro.idmacro = macro.id and exists(select * from atlas_projeto as ap where ap.id = ? and macro.idprojeto = ap.id))");
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
            $retorno[] = new Projeto($resultado->id, $resultado->nome, $resultado->scrum_master, $resultado->data_inicio, $resultado->prazo, $resultado->cliente, $resultado->observacoes, $resultado->estagio);
        }
        return $retorno;
    }
    
    
    public function CriarProjeto($nome, $cliente, $master, $equipe, $inicio, $prazo, $obs, $estagio)
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
        
        $obs = parent::LimparString($obs);
        $estagio = parent::LimparString($estagio);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto(nome ,scrum_master, data_inicio, prazo, cliente, observacoes, estagio) values (?, ?, STR_TO_DATE(?, '%d-%m-%Y'), STR_TO_DATE(?, '%d-%m-%Y'), ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $master);
        $stmt->bindValue(3, $inicio);
        $stmt->bindValue(4, $prazo);
        $stmt->bindValue(5, $cliente);
        $stmt->bindValue(6, $obs);
        $stmt->bindValue(7, $estagio);
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
                $retorno[] = new Projeto($pj->id, $pj->nome, $usuario, $pj->data_inicio, $pj->prazo, $pj->cliente, $pj->observacoes, $pj->estagio);
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
            $retorno[] = new Projeto($pj->id, $pj->nome, $usuario, $pj->data_inicio, $pj->prazo, $pj->cliente, $pj->observacoes, $pj->estagio);
        }
        
        return $retorno;
        
        
    }
    
    
    public function SetEntrege($idprojeto)
    {
        $stmt = parent::getCon()->prepare("update atlas_projeto set estagio = 'Entrege' where id = ?");
        $stmt->bindValue(1, $idprojeto);
        $stmt->execute();
            
    }
    public function SetDesenvolvimento($idprojeto)
    {
        $stmt = parent::getCon()->prepare("update atlas_projeto set estagio = 'Desenvolvimento' where id = ?");
        $stmt->bindValue(1, $idprojeto);
        $stmt->execute();
            
    }
    
    
}
