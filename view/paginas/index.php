<?php

    $_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
    include_once '../../util/Carregador.php';
    Carregador::CarregarPacotes();
     $extencoes = PROJECT_ACCEPTED_VIEW_EXT;
    function verificarSeExisteIndex($caminho)
    {
       global $extencoes;
        foreach($extencoes as $extencao)
        {
            if(is_file($caminho.'index.'.$extencao))
            {
               return $caminho.'index.'.$extencao;
            }
        }
        return false;
    }
    
    function verificarSeExisteArquivo($urlCaminho)
    {
        global $extencoes;
        foreach($extencoes as $extencao)
        {
            if(is_file($urlCaminho.".".$extencao))
            {
               return $urlCaminho.".".$extencao;
            }
        }
        return false;
    }
    
    if(isset($_GET['url']))
    {
        $url = $_GET['url'];
        $path = pathinfo(strtolower($url));
        $dirFile = $path['dirname'].'/'.$path['filename'];
        if($dirFile == 'index')
        {
            include_once 'login.php';
            return;
        }
        $extencao = $path['extension'];
       
        $larguraExtencao = -1 + -strlen($extencao);
        $ultimoChar = substr($url, $larguraExtencao);
        
          $url = substr($url, 0, $larguraExtencao);
           
           
        $caminho1 = verificarSeExisteArquivo('../../'.$url);
        $buffer1 ='';
        if($caminho1 == false)
        {
            $ultimo = substr($url, -1);
            if($ultimo != '/' || $ultimo != '\\')
            {
                $buffer1.='/';
            }
            
            $caminho1 = verificarSeExisteIndex('../../'.$url.$buffer1);
        }
        $caminho2 = verificarSeExisteArquivo(PROJECT_ROOT.'view/paginas/'.$url);
         $buffer2 ='';
        if($caminho2 == false)
        {
            
           
            $ultimo = substr($url, -1);
            if($ultimo != '/' || $ultimo != '\\')
            {
                $buffer2.='/';
            }
            $caminho2 = verificarSeExisteIndex(PROJECT_ROOT.'view/paginas/'.$url.$buffer2);
        }   
        if($caminho1 != false && $caminho2 != false)
        {
            
            if(empty($_POST))
            {
                include_once $caminho2;   
            }
            else
            {
                include_once $caminho1;
            }
        }
        else if($caminho1 == false && $caminho2 == false)
        {
            if(empty($_POST))
            {
                include_once'../meta/404.php';
            }
            else
            {
                 echo "erro 404";
            }
            
            return;
            
        }
        else
        {
            if($caminho1 != false)
            {
                 include_once $caminho1; 
            }
            else if($caminho2 != false)
            {
                 include_once $caminho2; 
            }
        }
        

    }
    else
    {
        include_once PROJECT_HOMEPAGE;
    }

?>