<?php
    try
    {
        $pdao = new ProjetoDAO();
        $projeto = $pdao->GetProjeto($_POST['projeto']);
        if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() != $projeto->getScrumMaster())
        {
            
             $sprintdao = new SprintDAO();
             $idsprint = $sprintdao->CadastarSprint($_POST['projeto'], $_POST['nome'], $_POST['inicio'], $_POST['prazo']);
             foreach($_POST['escolhidas'] as $escolhida)
             {
                if(isset($escolhida['responsaveis']))
                {
                    $sprintdao->CadastrarSprintTarefa($idsprint, $escolhida['id'], 'Desenvolvimento', $escolhida['responsaveis']);
                }
                else
                {
                    $sprintdao->CadastrarSprintTarefa($idsprint, $escolhida['id'], 'Desenvolvimento');
                }
               
             }
             JSONResponder::ResponderSucesso("Sprint cadastrada com sucesso");
        }
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>