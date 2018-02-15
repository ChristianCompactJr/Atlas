<?php
    header('Content-Type: application/json');
    $dao = new UsuarioDAO();
    
    $usuarios = $dao->GetUsuariosFiltro($_POST['inicio'], $_POST['limite'], $_POST['like']);
    
    $retorno = array();
    $usuariosJSON = array();
    foreach($usuarios as $usuario)
    {
        $usuariosJSON[] = array('id' => $usuario->getId(), 'nome' => $usuario->getNome(), 'email' => $usuario->getEmail(), 'foto' => $usuario->getFoto(), 'administrador' => $usuario->getAdministrador(), 'ativo' => $usuario->getAtivo());
    }
    $retorno['usuarios'] = $usuariosJSON;
    $retorno['total'] = $dao->GetTotalUsuarios();
    
    
    echo json_encode($retorno, JSON_FORCE_OBJECT);

