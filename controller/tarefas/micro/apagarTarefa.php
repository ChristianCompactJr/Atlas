<?php

    header('Content-Type: application/json');
    $pdao = new ProjetoDAO();
    $macrodao = new TarefaMacroDAO();
    $microdao = new TarefaMicroDAO();
    
    $micro = $microdao->getTarefa($_POST['id']);
    $macro = $macrodao->getTarefa($micro->getMacro());
    $projeto = $pdao->GetProjeto($macro->getProjeto());
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $microdao->ApagarTarefa($_POST['id']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Tarefa micro apagada com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }   

?>
