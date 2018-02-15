<?php

     header('Content-Type: application/json');
     
     if(SessionController::IsAdmin() || (SessionController::TemSessao() && SessionController::GetUsuario()->getId() == $_POST['id']))
     {
         
         try
         {
            $dao = new HabilidadeDAO();
            foreach($_POST['info'] as $info)
            {
                $interesse = filter_var($info['interesse'], FILTER_VALIDATE_BOOLEAN);
                $dao->AtulizarHabilidadeUsuario($_POST['id'], $info['id'], $info['valor'], $interesse);
            }
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Habilidades Atualizadas com sucesso'); 
         }
         catch(Exception $e)
         {
             $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
         }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
         
     }

?>
