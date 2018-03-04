<?php
    if(SessionController::IsAdmin())
    {
        try 
        {
            $dao = new HabilidadeDAO;
            $dao->AtualizarNome($_POST['id'], $_POST['nome']);
            JSONResponder::ResponderSucesso("Habilidade alterada com sucesso", true, true);
        } 
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }       
    }
?>
