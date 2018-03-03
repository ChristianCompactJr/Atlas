<?php
SessionController::VerificarCSRFToken();
    if(SessionController::IsAdmin())
    {
        try 
        {
            $dao = new HabilidadeDAO;
            $dao->CriarHabilidade($_POST['nome']);
            JSONResponder::ResponderSucesso("Habilidade cadastrada com sucesso", true, true);
        } 
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }       
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);
?>
