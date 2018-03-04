<?php
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $_POST['id'])
    {
        try
        {
            if($_POST['senha'] !== $_POST['confsenha'])
            {
                JSONResponder::ResponderFalha("A senha e a confirmação de senha não conferem.", true, true);
            }
            
            $dao = new UsuarioDAO();
            $dao->AtualizarSenha($_POST['id'], $_POST['senha']);
            
            JSONResponder::ResponderSucesso("Senha alterada com sucesso", true, true);
        }
        catch(Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }
    

