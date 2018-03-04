<?php
    if(SessionController::IsAdmin())
    {
        try 
        {
            $dao = new HabilidadeDAO;
            $dao->RemoverHabilidade($_POST['id']);
            JSONResponder::ResponderSucesso("Habilidade apagada com sucesso", true, true);
        } 
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }       
    }
?>
