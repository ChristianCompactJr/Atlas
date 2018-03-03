<?php

abstract class UrlManager {
    

    public static function SetHeaderLocation($pagina)
    {
        
        
        header("location: ".self::GetUrlToView($pagina));  
    }
    
    public static function GetPathToController($controller)
    {
        if($controller[0] == '/' || $controller [0] == '\\')
        {
             $controller = substr($controller, 1);
        }
        $ext = pathinfo($controller, PATHINFO_EXTENSION);
        if($ext == '')
        {
            $controller.= '.php';
        }
        $voltas = self::GetBackSlashes();
        return $voltas."controller/".$controller;
    }
    
    public static function GetPathToView($pagina)
    {
        if($pagina[0] == '/' || $pagina [0] == '\\')
        {
             $pagina = substr($pagina, 1);
        }
         
                
        $voltas = self::GetBackSlashes();
        return $voltas.$pagina;
    }
    
    
    public static function GetBackSlashes()
    {
    	if(PROJECT_HTTP_ROOT != '/')
    	{
    		$uri = str_replace(PROJECT_HTTP_ROOT, '', $_SERVER['REQUEST_URI']);
    	}
    	else
    	{
    		$uri = $_SERVER['REQUEST_URI'];
    	}
        
        $characteresURI = str_split($uri);
        $href = "";
        foreach($characteresURI as $char)
        {
            if($char == "/" || $char == "\\")
            {
                $href = "../".$href;
            }
        }
        return $href;
    }
    
    public static function GetViewUrl()
    {
        if(PROJECT_HTTP_ROOT != '/')
    	{
            $uri = str_replace(PROJECT_HTTP_ROOT, '', $_SERVER['REQUEST_URI']);
    	}
    	else
    	{
            $uri = $_SERVER['REQUEST_URI'];
    	}
    	return $uri;
    }
    
    
}
