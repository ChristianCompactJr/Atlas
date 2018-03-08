<?php

class SprintDAO extends DAO {
   
    public function CadastarSprint(int $projeto, string $nome, string $datainicio, string $prazo)
    {
        $projeto = parent::LimparString($projeto);
        $nome = parent::LimparString($nome);
        $datainicio = parent::LimparString($datainicio);
        $prazo = parent::LimparString($prazo);
        $agora = time();
        $agora = strtotime($agora);
        
        $datainicio = str_replace('/', '-', $datainicio);
        $prazo = str_replace('/', '-', $prazo);
        $prazotime = strtotime($prazo);
        $iniciotime = strtotime($datainicio);
        
        if($iniciotime > $prazotime)
        {
            throw new Exception("A data de inicio da sprint deve ser menor que o prazo");
        }
        
        
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_sprint(projeto, nome, data_inicio, prazo) values (?, ?, str_to_date(?, '%d-%m-%Y'), str_to_date(?, '%d-%m-%Y'))");
        $stmt->bindValue(1, $projeto);
        $stmt->bindValue(2, $nome);
        $stmt->bindValue(3, $datainicio);
        $stmt->bindValue(4, $prazo);
        $stmt->execute();
        $id = parent::getCon()->lastInsertId();                         
        return $id;  
    }
    
    public function GetSprintsProjeto($idprojeto)
    {
        $idprojeto = parent::LimparString($idprojeto);
        $stmt = parent::getCon()->prepare("select * from atlas_projeto_sprint where projeto = ?");
        $stmt->bindValue(1, $idprojeto);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        $retorno = array();
        foreach($resultado as $linha)
        {
            $retorno[] = new Sprint($linha->id, $linha->projeto,$linha->nome ,$linha->data_inicio, $linha->prazo, $linha->estagio);
        }
        return $retorno;
        
    }
    
    public function ExcluirSprint($idsprint)
    {
        $idsprint = parent::LimparString($idsprint);
        $stmt = parent::getCon()->prepare("delete from atlas_projeto_sprint where id = ?");
        $stmt->bindValue(1, $idsprint);
        $stmt->execute();
    }
    
    public function CadastrarSprintTarefa(int $sprint, int $micro, string $estagio = 'Desenvolvimento', array $responsaveis = array())
    {
        $sprint = parent::LimparString($sprint);
        $micro = parent::LimparString($micro);
        $estagio = parent::LimparString($estagio);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_sprint_tarefa(idsprint, idmicro, estagio) values (?, ?, ?)");
        $stmt->bindValue(1, $sprint);
        $stmt->bindValue(2, $micro);
        $stmt->bindValue(3, $estagio);
        $stmt->execute();
        $idtarefasprint = parent::getCon()->lastInsertId();
        
        foreach($responsaveis as $resp)
        {
            $this->CadastrarSprintTarefaResponsavel($idtarefasprint, $resp);
        }
        
    }
    
    public function CadastrarSprintTarefaResponsavel(int $idtarefa, int $idusuario)
    {
        $idtarefa = parent::LimparString($idtarefa);
        $idusuario = parent::LimparString($idusuario);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_sprint_tarefa_responsavel values (?, ?)");
        $stmt->bindValue(1, $idtarefa);
        $stmt->bindValue(2, $idusuario);
        $stmt->execute();
        
    }
    
    
    
    
    
}
