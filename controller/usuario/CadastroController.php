<?php
    header('Content-Type: application/json');
    $dao = new UsuarioDAO();
    try {
       /* $administrador = false;
        if(isset($_POST['administrador']))
        {
            $administrador = true;
        }
        $dao->Cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['foto'], $administrador);*/
        
        $dao->Cadastrar('Christian Lemos', $_POST['email'], $_POST['senha'], '', true);
        
        $resposta = array('tipo' => 'sucesso', 'mensagem' => '');
    } 
    catch (Exception $ex) {
        $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);

?>

