<?php

//Diretório root do projeto
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'].'/atlas/');



//Classe com a função de carregar outros arquivos 
class Carregador {
    
    private static $pacotes = array('model', 'util', 'controller');
    
    public static function CarregarPacotes()
    {
        foreach(self::$pacotes as $pacote)
        {
            
            $arquivos = glob(PROJECT_ROOT.$pacote.'/*{php}', GLOB_BRACE);
            foreach($arquivos as $arquivo)
            {
                include_once $arquivo;
            }
        }     
    }
    
    
    public static function CarregarViewHeadMeta()
    {
        include_once PROJECT_ROOT.'/view/meta/headMeta.php';
    }
    
    public static function CarregarViewFooter()
    {
        include_once PROJECT_ROOT.'/view/meta/footer.php';
    }
    
}
