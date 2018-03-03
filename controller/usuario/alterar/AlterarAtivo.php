<?php
SessionController::VerificarCSRFToken();
    if(SessionController::IsAdmin())
    {
        try
        {
            $ativo = filter_var($_POST['ativo'], FILTER_VALIDATE_BOOLEAN);
            $dao = new UsuarioDAO();
            $dao->AtualizarAtivo($_POST['id'], $ativo);
            if($_POST['id'] == SessionController::GetUsuario()->getId())
            {
                SessionController::GetUsuario()->setAtivo($ativo);
            }
            JSONResponder::ResponderSucesso("Ativo alterado com sucesso", true, true);
        }
        catch(Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }
    

