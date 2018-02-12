<?php
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
        if(self::$uri == '')
        {
            self::$uri = 'inicial';
        }
        else if(self::$uri[0] == '/')
        {
       	 self::$uri = substr(self::$uri, 1);
        }
    }
    

    public function ToHTML(array $separadores = array())
    {
        $htmlString = '<li class="';
        if($this->IsActive())
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
    
    public function IsActive()
    {
        if(self::$uri == $this->href)
        {
        
            return true;
        }
        foreach($this->linkFilhos as $filho)
        {
            if($filho->IsActive())
            {
                return true;
            }
        }
        return false;
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
