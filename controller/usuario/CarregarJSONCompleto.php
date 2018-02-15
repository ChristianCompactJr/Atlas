<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin())
    {
        $usuarioDAO = new UsuarioDAO();
        $habilidadeDAO = new HabilidadeDAO();
        
        $usuarios = $usuarioDAO->GetUsuarios();
        $resposta = array();
        foreach($usuarios as $usuario)
        {
            if($usuario->getAtivo() == false)
            {
                continue;
            }
            $habilidadesUsuario = $habilidadeDAO->GetHabilidadesUsuario($usuario->getId());    
            $habilidadesArray = array();
            foreach($habilidadesUsuario as $habilidade)
            {
                $habilidadesArray[] = array('idhabilidade' => $habilidade->getHabilidade()->getId(), 'nomehabilidade' => $habilidade->getHabilidade()->getNome(), 'valor' => $habilidade->getValor(), 'interesse' => $habilidade->getInteresse());
            }
            $habilidadesSorted = array();
            
            foreach($habilidadesArray as $key => $row)
            {
                $habilidadesSorted[$key] = $row['valor'];
            }
            array_multisort($habilidadesSorted, SORT_DESC, $habilidadesArray);
            
            $resposta[] = array('id' => $usuario->getId(), 'nome' => $usuario->getNome(), 'email' => $usuario->getEmail(), 'foto' => $usuario->getFoto(), 'administrador' => $usuario->getAdministrador(), 'ativo' => $usuario->getAtivo(), 'habilidades' => $habilidadesArray);
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
    }

?>