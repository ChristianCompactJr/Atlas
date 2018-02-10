    <?php
    header('Content-Type: application/json');
    try
    {
        $dao = new UsuarioDAO();  
        $dao->ApagarUsuario($_POST['id']);
        $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Usuário apagado com sucesso.');
    }
    catch(Exception $e)
    {
        $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);