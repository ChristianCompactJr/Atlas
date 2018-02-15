<?php
    header('Content-Type: application/json');
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
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'administrador alterado com sucesso');
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

