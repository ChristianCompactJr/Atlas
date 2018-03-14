<?php
    try
    {
        $sprintdao = new SprintDAO();
        $sprintdao->MarcarRevisao($_POST['id']);
        JSONResponder::ResponderSucesso("Sprint marcada para revisão com sucesso", true, true);
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>