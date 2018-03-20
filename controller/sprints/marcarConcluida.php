<?php
    try
    {
        $sprintdao = new SprintDAO();
        $projetodao = new ProjetoDAO();
        $sprint = $sprintdao->GetSprint($_POST['id-sprint']);
        $microdao = new TarefaMicroDAO();
        $tarefasprintdao = new TarefaSprintDAO();
        $projeto = $projetodao->GetProjeto($sprint->getProjeto());
        
        
        if(SessionController::GetUsuario()->getId() == $projeto->getScrumMaster() || SessionController::IsAdmin())
        {
            $sprintdao->MarcarConcluida($sprint->getId(), $_POST['retrospectiva']);
            foreach($_POST['resultado'] as $res)
            {
                $micro = $microdao->getTarefa($res['idmicro']);
                
                $projetodao->AddToBurndown($projeto->getId(), $micro->getId(), $res['estadoNovo']);
                
                
                $microdao->AtualizarEstado($micro->getId(), $res['estadoNovo']);
                
                $tarefasprintdao->SetHistorico($res['idtarefasprint'], $res['desempenho'], $micro->getEstado(), $res['estadoNovo']);
            }
            
            
        }
        
        JSONResponder::ResponderSucesso("Sprint concluída com sucesso", true, true);
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>