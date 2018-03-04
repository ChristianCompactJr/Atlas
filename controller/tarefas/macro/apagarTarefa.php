<?php
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
            JSONResponder::ResponderSucesso("Tarefa macro apagada com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
        
     
    }   

?>
