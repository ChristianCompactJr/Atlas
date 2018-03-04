<?php

    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $_POST['id'])
    {
        try
        {
            $dao = new UsuarioDAO();
            $dao->AtualizarNome($_POST['id'], $_POST['nome']);
            
            if($_POST['id'] == SessionController::GetUsuario()->getId())
            {
                SessionController::GetUsuario()->setNome($_POST['nome']);
                
            }
            JSONResponder::ResponderSucesso("Nome alterado com sucesso", true, true);
        }
        catch(Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }
    

