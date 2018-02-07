<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LinkMenu]
 *
 * @author CompAct
 */
class LinkMenu
{
    private $nome;
    private $href;
    private $posicao;
    private $linkFilhos;
    private static $uri;
    
    public function __construct($nome, $href, array $filhos = array())
    {
        $this->nome = $nome;      
        $this->href = $href;
        $this->linkFilhos = $filhos;
        self::$uri = UserRootViewFinder::GetViewUrl();
    }
    

    public function ToHTML(array $separadores = array())
    {
        $htmlString = '<li class="';
        if(self::$uri == $this->href)
        {
            $htmlString .= 'active ';
        }
  
        
        if(count($this->linkFilhos) <= 0)
        {
           $htmlString .= '"><a href="'.UserRootViewFinder::GetBackSlashes().$this->href.'">'.$this->nome; 
           
           if(self::$uri == $this->href)
            {
                $htmlString .= '<span class="sr-only">(atual)</span>';
            }
           $htmlString .= '</a></li>';
        }
        else
        {
            $htmlString .= 'dropdown "> <a href="'.UserRootViewFinder::GetBackSlashes().$this->href.'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$this->nome.'<span class="caret"></span>';
            
            if(self::$uri == $this->href)
            {
                $htmlString .= '<span class="sr-only">(atual)</span>';
            }
            $htmlString .= '</a><ul class="dropdown-menu">';
            
            for ($i = 0; $i < count($this->linkFilhos); $i++)
            {
                
                $htmlString .= $this->linkFilhos[$i]->ToHTML();
                
                foreach($separadores as $separador)
                {
                    if($i + 2 == $separador)
                    {
                        $htmlString .= '<li role="separator" class="divider"></li>';
                        break;
                    }
                }
            }
             $htmlString .= '</ul></li>';
            
            
        }
        
        return $htmlString;
    }
    
    function AdicionarFilho(LinkMenu $filho)
    {
        $this->linkFilhos[] = $filho;
    }
    
    
    function getNome() {
        return $this->nome;
    }

    function getHref() {
        return $this->href;
    }


    function setNome($nome) {
        $this->nome = $nome;
    }

    function setHref($href) {
        $this->href = $href;
    }




}
