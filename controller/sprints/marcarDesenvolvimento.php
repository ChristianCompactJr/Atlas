<?php
    try
    {
        $sprintdao = new SprintDAO();
        $sprintdao->MarcarDesenvolvimento($_POST['id']);
        JSONResponder::ResponderSucesso("Sprint marcada para desenvolvimento com sucesso", true, true);
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>