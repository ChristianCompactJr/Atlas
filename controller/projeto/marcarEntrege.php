<?php

    try
    {
        $projetodao = new ProjetoDAO();
        $projeto = $projetodao->GetProjeto($_POST['id']);
        
        if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() != $projeto->getMaster())
        {
            $projetodao->SetEntrege($projeto->getId());
            
            JSONResponder::ResponderSucesso("Projeto entrege com sucesso", true, true);
        }
        
        
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>