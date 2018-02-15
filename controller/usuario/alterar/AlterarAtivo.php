<?php
    header('Content-Type: application/json');
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
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Ativo alterado com sucesso');
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

