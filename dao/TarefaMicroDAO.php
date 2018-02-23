<?php


class TarefaMicroDAO extends DAO {
    
    public function AdicionarTarefa($idmacro, $nome, $descricao,$tempo, $links, $concluida=false)
    {
        $idmacro = parent::LimparString($idmacro);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        $tempo = parent::LimparString($tempo);
        $links = parent::LimparString($links);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_tarefa_micro(idmacro, nome, descricao, tempo_previsto, link_uteis, concluida) values (?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $idmacro);
        $stmt->bindValue(2, $nome);
        $stmt->bindValue(3, $descricao);
        $stmt->bindValue(4, $tempo);
        $stmt->bindValue(5, $links);
        $stmt->bindValue(6, $concluida);
        $stmt->execute();
    }
    public function AtualizarTarefa($idmicro, $nome, $descricao,$tempo, $links, $concluida=false)
    {
        $idmicro = parent::LimparString($idmicro);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        $tempo = parent::LimparString($tempo);
        $links = parent::LimparString($links);
        
        $stmt = parent::getCon()->prepare("update atlas_projeto_tarefa_micro set nome = ?, descricao = ?, tempo_previsto = ?, link_uteis = ?, concluida = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $descricao);
        $stmt->bindValue(3, $tempo);
        $stmt->bindValue(4, $links);
        $stmt->bindValue(5, $concluida);
        $stmt->bindValue(6, $idmicro);
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
            $retorno[] = new TarefaMicro($tarefa->id, $tarefa->idmacro, $tarefa->nome, $tarefa->descricao, $tarefa->tempo_previsto, $tarefa->link_uteis, $tarefa->concluida);
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
        
         $retorno = new TarefaMicro($tarefa->id, $tarefa->idmacro, $tarefa->nome, $tarefa->descricao, $tarefa->tempo_previsto, $tarefa->link_uteis, $tarefa->concluida);
        return $retorno;
    }
    
}
