<?php
    include_once '../../util/Carregador.php';
    Carregador::CarregarPacotes();
    
    function verificarSeExisteIndex($caminho)
    {
        $extencoes = array('php', 'html', 'phtml');
        foreach($extencoes as $extencao)
        {
            if(is_file($caminho.'/index.'.$extencao))
            {
               return $caminho.'/index.'.$extencao;
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
       
        if($url[0] == '/' || $url[0] == "\\")
        {
            $url = substr($url, 1);
        }
        $larguraExtencao = -2 + -strlen($extencao);
        $ultimoChar = substr($url, $larguraExtencao);
        if($ultimoChar == '\\.php' || $ultimoChar == '/.php')
        {
          $url = substr($url, 0, $larguraExtencao);
        }
        //verifica se o arquivo existe fora na pasta "paginas"
        if(is_file('../../'.$url))
        {
            $caminho = '../../'.$url;
        }
        
        //Verifica se arquivo existe na pasta "paginas"
        else if(is_file(PROJECT_ROOT.'view/paginas/'.$url))
        {
            $caminho = $url;
        }
         //Verifica se arquivo existe na pasta "paginas" em uma subpasta
        else if(verificarSeExisteIndex($url) !== false)
        {
            $caminho = verificarSeExisteIndex($url);
        }
        else
        {
            echo "erro 404";
            return;
        }
        
        include_once $caminho;   

    }

?>