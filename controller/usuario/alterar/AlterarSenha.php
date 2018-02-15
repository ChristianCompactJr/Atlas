<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $_POST['id'])
    {
        try
        {
            if($_POST['senha'] !== $_POST['confsenha'])
            {
                $resposta = array('tipo' => 'sucesso', 'mensagem' => 'A senha e a confirmação de senha não conferem.');
                echo json_encode($resposta, JSON_FORCE_OBJECT);
                return;
            }
            
            $dao = new UsuarioDAO();
            $dao->AtualizarSenha($_POST['id'], $_POST['senha']);
            
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Senha alterada com sucesso');
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

