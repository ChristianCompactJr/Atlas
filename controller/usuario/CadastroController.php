<?php
    $dao = new UsuarioDAO();
    try {
        if($_POST['senha'] != $_POST['confsenha'])
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => 'A confirmação da senha é diferente da senha');
            echo json_encode($resposta, JSON_FORCE_OBJECT);
            return;
        }
        
        if(is_file($_FILES['foto']['tmp_name']))
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
        }
        else
        {
            $foto = '';
        }
        
        
        
        $administrador = false;
        if(isset($_POST['administrador']))
        {
            $administrador = true;
        }
        
        
        if($foto != '')
        {
             $dao->Cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'], 'uploads/fotos/'.$foto, $administrador);
             EnviadorArquivos::UploadArquivo($_FILES['foto']['tmp_name'], 'uploads/fotos/'.$foto);
        }
        else
        {
             $dao->Cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'], '', $administrador);
        }
        JSONResponder::ResponderSucesso("Usuário cadastrado com sucesso", true, true);
    } 
    catch (Exception $ex) {
        JSONResponder::ResponderFalha($ex->getMessage(), true, true);
    }

?>

