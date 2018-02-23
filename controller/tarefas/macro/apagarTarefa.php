<?php

    header('Content-Type: application/json');
    $pdao = new ProjetoDAO();
    $macrodao = new TarefaMacroDAO();
    
    $macro = $macrodao->getTarefa($_POST['id']);
    $projeto = $pdao->GetProjeto($macro->getProjeto());
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $macrodao->ApagarTarefa($_POST['id']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Tarefa macro apagada com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }   

?>
