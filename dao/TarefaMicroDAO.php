<?php


class TarefaMicroDAO extends DAO {
    
    public function AdicionarTarefa($idmacro, $nome, $descricao, $observacoes, $links, $prioridade, $estimativa, $estado)
    {
        $idmacro = parent::LimparString($idmacro);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        $observacoes = parent::LimparString($observacoes);
        $prioridade = parent::LimparString($prioridade);
        $estimativa = parent::LimparString($estimativa);
        $estado = parent::LimparString($estado);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_tarefa_micro(idmacro, nome, descricao,observacoes, link_uteis, prioridade, estimativa, estado) values (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $idmacro);
        $stmt->bindValue(2, $nome);
        $stmt->bindValue(3, $descricao);
        $stmt->bindValue(4, $observacoes);
        $stmt->bindValue(5, $links);
        $stmt->bindValue(6, $prioridade);
        $stmt->bindValue(7, $estimativa);
        $stmt->bindValue(8, $estado);
        $stmt->execute();
    }
    public function AtualizarTarefa($idmicro, $nome, $descricao, $observacoes, $links, $prioridade, $estimativa, $estado)
    {
        $idmicro = parent::LimparString($idmicro);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        $prioridade = parent::LimparString($prioridade);
        $observacoes = parent::LimparString($observacoes);
        $estimativa = parent::LimparString($estimativa);
        $estado = parent::LimparString($estado);
        
        $stmt = parent::getCon()->prepare("update atlas_projeto_tarefa_micro set nome = ?, descricao = ?, observacoes = ?, link_uteis = ?, prioridade = ?, estimativa = ?, estado = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $descricao);
        $stmt->bindValue(3, $observacoes);
         $stmt->bindValue(4, $links);
        $stmt->bindValue(5, $prioridade);
        $stmt->bindValue(6, $estimativa);
        $stmt->bindValue(7, $estado);
        $stmt->bindValue(8, $idmicro);
        $stmt->execute();
    }
    public function getTarefasMicro($idMacro)
    {
         $stmt = parent::getCon()->prepare("select * from atlas_projeto_tarefa_micro where idmacro = ?");
        $stmt->bindValue(1, $idMacro);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        $retorno = array();
        foreach($resultado as $tarefa)
        {
            $retorno[] = new TarefaMicro($tarefa->id, $tarefa->idmacro, $tarefa->nome, $tarefa->descricao, $tarefa->observacoes, $tarefa->link_uteis, $tarefa->prioridade, $tarefa->estimativa, $tarefa->estado);
        }
        return $retorno;
        
    }
    
    public function ApagarTarefa($idMicro)
    {
         $stmt = parent::getCon()->prepare("delete from atlas_projeto_tarefa_micro where id = ?");
        $stmt->bindValue(1, $idMicro);
        $stmt->execute();
        
    }
    
    public function getTarefa($id)
    {
         $stmt = parent::getCon()->prepare("select * from atlas_projeto_tarefa_micro where id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $tarefa = $stmt->fetch();
        
         $retorno = new TarefaMicro($tarefa->id, $tarefa->idmacro, $tarefa->nome, $tarefa->descricao, $tarefa->observacoes, $tarefa->link_uteis, $tarefa->prioridade, $tarefa->estimativa, $tarefa->estado);
        return $retorno;
    }
    
}
