<?php

include_once 'Opcoes.php';

//Classe com a função de carregar outros arquivos 
abstract class Carregador {
    
    private static $pacotes = array('model', 'util', 'dao');
    
    public static function CarregarPacotes()
    {
        foreach(self::$pacotes as $pacote)
        {
            self::carregarPacote(PROJECT_ROOT.$pacote);
        }     
    }
    
    private static function carregarPacote($pacote)
    {
        $arquivos = glob($pacote.'/*', GLOB_BRACE);
        
        foreach($arquivos as $arquivo)
        {
            if(pathinfo($arquivo, PATHINFO_EXTENSION) == 'php')
            {
                include_once $arquivo;
            }
            else if(is_dir($arquivo))
            {
                self::carregarPacote($arquivo);
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
