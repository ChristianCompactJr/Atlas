<?php
    try
    {
        $pdao = new ProjetoDAO();
        $projeto = $pdao->GetProjeto($_POST['projeto']);
        if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() != $projeto->getScrumMaster() && $projeto->getEstagio() == 'Desenvolvimento')
        {
            
             $sprintdao = new SprintDAO();
             $idsprint = $sprintdao->CadastarSprint($_POST['projeto'], $_POST['nome'], $_POST['inicio'], $_POST['prazo']);
             foreach($_POST['escolhidas'] as $escolhida)
             {
                if(isset($escolhida['responsaveis']))
                {
                    $sprintdao->CadastrarSprintTarefa($idsprint, $escolhida['id'], $escolhida['responsaveis']);
                }
                else
                {
                    $sprintdao->CadastrarSprintTarefa($idsprint, $escolhida['id']);
                }
               
             }
             JSONResponder::ResponderSucesso("Sprint cadastrada com sucesso");
        }
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderFalha($e->getMessage(), true, true);
    }
    
    


?>