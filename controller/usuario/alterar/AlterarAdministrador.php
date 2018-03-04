<?php
    if(SessionController::IsAdmin())
    {
        try
        {
            $administrador = filter_var($_POST['administrador'], FILTER_VALIDATE_BOOLEAN);
            $dao = new UsuarioDAO();
            $dao->AtualizarAdministrador($_POST['id'], $administrador);
            
            if($_POST['id'] == SessionController::GetUsuario()->getId())
            {
                SessionController::GetUsuario()->setAdministrador($administrador);
            }
            JSONResponder::ResponderSucesso("Administrador alterado com sucesso", true, true);
        }
        catch(Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }
    

