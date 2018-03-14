<?php

class TarefaSprintDAO extends DAO{
    
  
    public function GetTarefasSprint($sprint)
    {
        $sprint = parent::LimparString($sprint);
        
        $stmt = parent::getCon()->prepare("select * from atlas_projeto_sprint_tarefa where idsprint = ?");
        $stmt->bindValue(1, $sprint);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        $retorno = array();
        foreach($resultado as $tarefa)
        {
            $usuarios = $this->GetResponsaveis($tarefa->id);
            $retorno[] = new TarefaSprint($tarefa->id, $tarefa->idsprint, $tarefa->idmicro, $usuarios, $tarefa->desempenho, $tarefa->historico_atual, $tarefa->historico_novo);
        }
        return $retorno;
        
    }
    
    public function SetDesempenho(int $tarefaSprint, string $desempenho)
    {
        $stmt = parent::getCon()->prepare("update atlas_projeto_sprint_tarefa set desempenho = ? where id = ?");
        $stmt->bindValue(1, $desempenho);
        $stmt->bindValue(2, $tarefaSprint);
        $stmt->execute();    
    }
    
    public function SetHistorico(int $tarefaSprint, string $desempenho, string $estadoAtual, string $estadoNovo)
    {
        $stmt = parent::getCon()->prepare("update atlas_projeto_sprint_tarefa set desempenho = ?, historico_atual = ?, historico_novo = ? where id = ?");
        $stmt->bindValue(1, $desempenho);
        $stmt->bindValue(2, $estadoAtual);
        $stmt->bindValue(3, $estadoNovo);
        $stmt->bindValue(4, $tarefaSprint);
        $stmt->execute();    
    }
    
    
    
    public function GetResponsaveis($tarefaSprint)
    {
         $tarefaSprint = parent::LimparString($tarefaSprint);
        
        $stmt = parent::getCon()->prepare("select * from atlas_projeto_sprint_tarefa_responsavel where idtarefa = ?");
        $stmt->bindValue(1, $tarefaSprint);
        $stmt->execute();
        
        $linhas = $stmt->fetchAll();
        $retorno = array();
        foreach($linhas as $resultado)
        {
            $usuariodao = new UsuarioDAO();
            $usuario = $usuariodao->GetUsuario($resultado->idusuario);
            
            $retorno[] = $usuario;
        }
        
        return $retorno;
    }
    
    
    
}
