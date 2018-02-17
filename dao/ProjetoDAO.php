<?php


class ProjetoDAO extends DAO {
   
   
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
            $stmt = parent::getCon()->prepare("insert into atlas_projeto_desenvolvedor values (?, ?)");
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
        
        $stmt = parent::getCon()->prepare("select * from atlas_projeto as ap where exists(select * from atlas_projeto_desenvolvedor as apd where apd.idusuario = ? and apd.idprojeto = ap.id)");
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
