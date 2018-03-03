<?php
SessionController::VerificarCSRFToken();
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $_POST['id'])
    {
        try
        {
            $dao = new UsuarioDAO();
            $dao->AtualizarEmail($_POST['id'], $_POST['email']);
            
            if($_POST['id'] == SessionController::GetUsuario()->getId())
            {
                SessionController::GetUsuario()->setEmail($_POST['email']);
                
            }
            JSONResponder::ResponderSucesso("Email alterado com sucesso", true, true);
        }
        catch(Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }
    

