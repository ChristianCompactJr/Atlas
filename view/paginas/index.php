<?php
    include_once '../../util/Carregador.php';
    Carregador::CarregarPacotes();
     $extencoes = array('php', 'html', 'phtml');
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
        
        if(strtolower(pathinfo($url, PATHINFO_FILENAME)) == 'index')
        {
            include_once 'login.php';
            return;
        }
        $extencao = pathinfo($url, PATHINFO_EXTENSION);
       
        $larguraExtencao = -1 + -strlen($extencao);
        $ultimoChar = substr($url, $larguraExtencao);
        
          $url = substr($url, 0, $larguraExtencao);
           
           
        $caminho = verificarSeExisteArquivo('../../'.$url);
        if($caminho === false)
        {
            
             $caminho = verificarSeExisteArquivo(PROJECT_ROOT.'view/paginas/'.$url);
             if($caminho == false)
             {
                  $ultimo = substr($url, -1);
                if($ultimo != '/' || $ultimo != '\\')
                {
                    $url.='/';
                }
                $caminho = verificarSeExisteIndex($url);
                if($caminho === false)
                {
                    echo "erro 404";
                    return;
                }
             }
        }
        include_once $caminho; 
        

    }
    else
    {
        include_once 'login.php';
    }

?>