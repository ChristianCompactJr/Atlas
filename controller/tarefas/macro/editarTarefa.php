<?php
    $pdao = new ProjetoDAO();
    $macrodao = new TarefaMacroDAO();
    
    $macro = $macrodao->getTarefa($_POST['idmacro']);
    $projeto = $pdao->GetProjeto($macro->getProjeto());
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $macrodao->AtualizarTarefa($_POST['idmacro'], $_POST['nome'], $_POST['descricao']);
            JSONResponder::ResponderSucesso("Tarefa macro alterada com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
        
     
    }   

?>
