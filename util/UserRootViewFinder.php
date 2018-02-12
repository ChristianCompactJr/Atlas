<?php

abstract class UserRootViewFinder {
    
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
