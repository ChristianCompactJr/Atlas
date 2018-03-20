<?php
    try
    {
       $sprintdao = new SprintDAO();
        $projetodao = new ProjetoDAO();
        $sprint = $sprintdao->GetSprint($_POST['id']);
        $tarefasprintdao = new TarefaSprintDAO();
        $projeto = $projetodao->GetProjeto($sprint->getProjeto());
        
        if(SessionController::GetUsuario()->getId() == $projeto->getScrumMaster() || SessionController::IsAdmin())
        {
            $sprintdao->MarcarDesenvolvimento($_POST['id']);
            JSONResponder::ResponderSucesso("Sprint marcada para desenvolvimento com sucesso", true, true);
        }
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>