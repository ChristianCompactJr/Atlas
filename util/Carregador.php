<?php


define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'].'/atlas/');

class Carregador {
    
    private static $pacotes = array('model', 'util');
    
    public static function CarregarClasses()
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
    
}
