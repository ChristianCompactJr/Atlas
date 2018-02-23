<?php

    header('Content-Type: application/json');
    
    if(SessionController::TemSessao())
    {
        $dao = new TarefaMacroDAO();
        
        $tarefas = $dao->getTarefasMacro($_POST['idprojeto']);
        
        $retorno = array();
        foreach($tarefas as $t)
        {
            $retorno[] = $t->toArray();
        }
        echo json_encode($retorno, JSON_FORCE_OBJECT);
    }

?>
