<?php
    header('Content-Type: application/json');
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
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Nome alterado com sucesso');
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

