<?php


class TarefaMacroDAO extends DAO {
    
    public function AdicionarTarefa($idprojeto, $nome, $descricao)
    {
        $idprojeto = parent::LimparString($idprojeto);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        
        $stmt = parent::getCon()->prepare("insert into atlas_projeto_tarefa_macro(idprojeto, nome, descricao) values (?, ?, ?)");
        $stmt->bindValue(1, $idprojeto);
        $stmt->bindValue(2, $nome);
        $stmt->bindValue(3, $descricao);
        $stmt->execute();
    }
    
    public function getTarefasMacro($idprojeto)
    {
         $stmt = parent::getCon()->prepare("select * from atlas_projeto_tarefa_macro where idprojeto = ?");
        $stmt->bindValue(1, $idprojeto);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll();
        $retorno = array();
        foreach($resultado as $tarefa)
        {
            $retorno[] = new TarefaMacro($tarefa->id, $tarefa->idprojeto, $tarefa->nome, $tarefa->descricao);
        }
        return $retorno;
        
    }
    
    public function getTarefa($id)
    {
         $stmt = parent::getCon()->prepare("select * from atlas_projeto_tarefa_macro where id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $tarefa = $stmt->fetch();

        return new TarefaMacro($tarefa->id, $tarefa->idprojeto, $tarefa->nome, $tarefa->descricao);
        
    }
    
    public function ApagarTarefa($idMacro)
    {
         $stmt = parent::getCon()->prepare("delete from atlas_projeto_tarefa_macro where id = ?");
        $stmt->bindValue(1, $idMacro);
        $stmt->execute();
        
    }
    
    public function AtualizarTarefa($id, $nome, $descricao)
    {
        $id = parent::LimparString($id);
        $nome = parent::LimparString($nome);
        $descricao = parent::LimparString($descricao);
        
        $stmt = parent::getCon()->prepare("update atlas_projeto_tarefa_macro set nome = ?, descricao = ? where id = ?");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $descricao);
        $stmt->bindValue(3, $id);
        $stmt->execute();
    }
    
}
