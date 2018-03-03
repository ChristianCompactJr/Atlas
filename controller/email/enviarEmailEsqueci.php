<?php
SessionController::VerificarCSRFToken();
    try {
        $dao = new UsuarioDAO();
        $usuario = $dao->EncontrarUsuarioComEmail($_POST['email']);
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { 
        	$protocolo = 'https'; 
        } 
        else
        {
        	$protocolo = 'http';
        }
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chave = '';
	
        for ($i = 0; $i < 60; $i++) {
            $chave .= $characters[rand(0, 60 - 1)];
        }
        $dao->AdicionarEsqueci($usuario->getId(), $chave);
        $link = $protocolo.'://'.$_SERVER['HTTP_HOST'].PROJECT_HTTP_ROOT."alterar-senha?u=".$usuario->getId()."&chave=".$chave;
        
        $mensagem = '<html>'
                . '<head>'
                . '</head>'
                . '<body>
                    <p>Olá</p>
                    <p>Você recebeu essa mensagem porque você requisitou a alteração de sua senha na plataforma Atlas.</p>
                    <p>Para acessar alterar sua senha, clique no link abaixo</p>
                    <a href = "'.$link.'">'.$link.'</a><br />
                    <p>Caso esteja enfrentando alguma dificuldade, contacte o suporte ou algum administrador</p>
                  </body>'
                . '</html>';
        
        EnviadorEmail::EnviarEmail('Atlas: Redefinir senha', $mensagem);
       JSONResponder::ResponderSucesso("Mensagem enviada com sucesso", true, true);
    } 
    catch (Exception $ex) {
        JSONResponder::ResponderFalha($e->getMessage(), true, true);
    }
     echo json_encode($resposta, JSON_FORCE_OBJECT);
     
?>

