<?php
    header('Content-Type: application/json');
    $dao = new UsuarioDAO();
    try {
        if($_POST['senha'] != $_POST['confsenha'])
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
            echo json_encode($resposta, JSON_FORCE_OBJECT);
            return;
        }
        
        $administrador = false;
        if(isset($_POST['administrador']))
        {
            $administrador = true;
        }
        $dao->Cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'], '', $administrador);
        
        
        $resposta = array('tipo' => 'sucesso', 'mensagem' => '');
    } 
    catch (Exception $ex) {
        $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);

?>

