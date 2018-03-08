<?php
    try
    {
        $sprintdao = new SprintDAO();
        $sprintdao->ExcluirSprint($_POST['id']);
        JSONResponder::ResponderSucesso("Sprint excluida com sucesso", true, true);
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>