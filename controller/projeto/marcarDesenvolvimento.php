<?php

    try
    {
        $projetodao = new ProjetoDAO();
        $projeto = $projetodao->GetProjeto($_POST['id']);
        
        if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() != $projeto->getMaster())
        {
            $projetodao->SetDesenvolvimento($projeto->getId());
            
            JSONResponder::ResponderSucesso("Projeto em desenvolvimento com sucesso", true, true);
        }
        
        
    }
    
    catch(Exception $e)
    {
        JSONResponder::ResponderSucesso($e->getMessage(), true, true);
    }
    
    


?>