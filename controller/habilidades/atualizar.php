<?php
     SessionController::VerificarCSRFToken();
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
            JSONResponder::ResponderSucesso("Habilidades atualizadas com sucesso", true, true);
         }
         catch(Exception $e)
         {
             JSONResponder::ResponderFalha($e->getMessage(), true, true);
         }
         
     }

?>
