<?php
    if(SessionController::IsAdmin())
    {
        try
        {
            $dao = new ProjetoDAO();
            $dao->ApagarProjeto($_POST['idprojeto']);
            JSONResponder::ResponderSucesso("Projeto removido com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
        
     
    }

?>

