<?php
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
                
            $microdao->AtualizarTarefa($_POST['idmicro'], $_POST['nome'], $_POST['descricao'],$_POST['observacoes'], $_POST['links'], $_POST['prioridade'], $_POST['estimativa'], $_POST['estado']);
            JSONResponder::ResponderSucesso("Tarefa micro alterada com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
        
     
    }   

?>
