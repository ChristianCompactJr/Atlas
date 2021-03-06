<?php
    header('Content-Type: application/json');
    if(SessionController::TemSessao())
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
            
            $projetodao = new ProjetoDAO();
            
            $masters = $projetodao->GetProjetosUsuarioMaster($usuario->getId());
            $masterToArrayJSON = array();
            foreach($masters as $master)
            {
                $masterToArrayJSON[] = array('nomeprojeto' => $master->getNome(), 'estagio' => $master->getEstagio());
                
            }
           
            
            $devs = $projetodao->GetProjetosUsuarioDev($usuario->getId());
            $devsToArrayJSON = array();
            
            foreach($devs as $dev)
            {
                $devsToArrayJSON[] = array('nomeprojeto' => $dev->getNome(), 'estagio' => $dev->getEstagio());
            }
            
            $resposta[] = array('id' => $usuario->getId(), 'nome' => $usuario->getNome(), 'email' => $usuario->getEmail(), 'foto' => $usuario->getFoto(), 'administrador' => $usuario->getAdministrador(), 'ativo' => $usuario->getAtivo(), 'habilidades' => $habilidadesArray, 'master' => $masterToArrayJSON, 'devs' => $devsToArrayJSON);
            
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
    }

?>