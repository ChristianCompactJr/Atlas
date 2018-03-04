<?php
    $dao = new ProjetoDAO();
    $projeto = $dao->GetProjeto($_POST['idprojeto']);
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $projeto->getScrumMaster())
    {
        if(!isset($_POST['dev']) || !isset($_POST['master']))
        {
            JSONResponder::ResponderAviso("O projeto deve ter um SCRUM Master e pelo menos um equipe SCRUM.", true, true);
        }
        else
        {
            try
            {
                
                
                if(SessionController::IsAdmin())
                {
                    $scrumMaster = $_POST['master'];
                }
                else
                {
                    $scrumMaster = SessionController::GetUsuario()->getId();
                }
                $dao->AtualizarProjeto($projeto->getId(), $_POST['nome'], $_POST['cliente'], $scrumMaster, $_POST['dev'], $_POST['inicio'], $_POST['prazo'], $_POST['obs'], $_POST['estagio']);
                JSONResponder::ResponderSucesso("Projeto editado com sucesso", true, true);
            }
            catch (Exception $e)
            {
                JSONResponder::ResponderFalha($e->getMessage(), true, true);
            }
        }       
       
     
    }

?>

