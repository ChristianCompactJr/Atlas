<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $_POST['id'] && is_file($_FILES['foto']['tmp_name']))
    {
        try
        {
            
            if($_FILES['foto']['size'] > EnviadorArquivos::GetMaxUploadSize())
            {
                $resposta = array('tipo' => 'erro', 'mensagem' => 'O tamanho da foto é muito grande');
                echo json_encode($resposta, JSON_FORCE_OBJECT);
                return;
            }
            $extencao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            
            if(!in_array(strtolower($extencao), EnviadorArquivos::$formatosImagemSuportados))
            {
                $resposta = array('tipo' => 'erro', 'mensagem' => 'Envie a foto em pormato jpg, jpeg, png ou gif');
                echo json_encode($resposta, JSON_FORCE_OBJECT);
                return;
            }
            $foto = EnviadorArquivos::CriarNomeArquivo('fotos',$extencao ,'perfil_');
            
            $dao = new UsuarioDAO();
           // $usuario = $dao->GetUsuario($_POST['id']);
            
           // EnviadorArquivos::ApagarArquivo('fotos/'.$usuario->getFoto());
            EnviadorArquivos::UploadArquivo($_FILES['foto']['tmp_name'], 'uploads/fotos/'.$foto);
            
            $dao->AtualizarFoto($_POST['id'], 'uploads/fotos/'.$foto);
            
            if($_POST['id'] == SessionController::GetUsuario()->getId())
            {
                SessionController::GetUsuario()->setFoto('uploads/fotos/'.$foto);    
            }
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Foto de perfil alterada com sucesso', 'novafoto' => 'uploads/fotos/'.$foto );
        }
        catch(Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    

