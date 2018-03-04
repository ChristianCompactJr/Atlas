<?php
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
            JSONResponder::ResponderSucesso("Tarefa micro apagada com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
        
     
    }   

?>
