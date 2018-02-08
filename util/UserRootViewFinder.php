<?php

abstract class UserRootViewFinder {
    
    public static function GetBackSlashes()
    {
        $uri = str_replace(PROJECT_HTTP_ROOT, '', $_SERVER['REQUEST_URI']);
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
        return str_replace(PROJECT_HTTP_ROOT, '', $_SERVER['REQUEST_URI']);
    }
    
    
}
