<?php

    header('Content-Type: application/json');
    $pdao = new ProjetoDAO();
    $macrodao = new TarefaMacroDAO();
    $microdao = new TarefaMicroDAO();
    
    $micro = $microdao->getTarefa($_POST['idmicro']);
    $macro = $macrodao->getTarefa($micro->getMacro());
    $projeto = $pdao->GetProjeto($macro->getProjeto());
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            if(isset($_POST['concluida']))
            {
                $concluida = true;
            }
            else
            {
                $concluida = false;
            }
                
            $microdao->AtualizarTarefa($_POST['idmicro'], $_POST['nome'], $_POST['descricao'], $_POST['tempo'], $_POST['links'], $concluida);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Tarefa micro alterada com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }   

?>
