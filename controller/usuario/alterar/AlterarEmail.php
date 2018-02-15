<?php
    header('Content-Type: application/json');
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
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Email alterado com sucesso');
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

