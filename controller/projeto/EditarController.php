<?php
    header('Content-Type: application/json');
    $dao = new ProjetoDAO();
    $projeto = $dao->GetProjeto($_POST['idprojeto']);
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $projeto->getScrumMaster())
    {
        if(!isset($_POST['dev']) || !isset($_POST['master']))
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => 'O Projeto deve ter um SCRUM Master e pelo mesmo um equipe SCRUM');
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
                
                $dao->AtualizarProjeto($projeto->getId(), $_POST['nome'], $_POST['cliente'], $scrumMaster, $_POST['dev'], $_POST['inicio'], $_POST['prazo'], $_POST['backlog'], $_POST['obs'], $_POST['estagio']);
                $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Projeto editado com sucesso');
            }
            catch (Exception $e)
            {
                $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
            }
        }
        
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }

?>

