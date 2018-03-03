<?php

abstract class JSONResponder {
    
    public static function ResponderSucesso($mensagem, $setHeader = false, $pararAplicacao = false, $adicionais = array())
    {
        if($setHeader == true)
        {
            header('Content-Type: application/json');
        }
        $resposta = array('tipo' => 'success', 'mensagem' => $mensagem);
        foreach($adicionais as $key=>$value)
        {
            $resposta[$key] = $value;
        }
        echo json_encode($resposta, JSON_FORCE_OBJECT);
        if($pararAplicacao == true)
        {
            die();
        }
    }
    
    public static function ResponderAviso($mensagem, $setHeader = false, $pararAplicacao = false, $adicionais = array())
    {
        if($setHeader == true)
        {
            header('Content-Type: application/json');
        }
        
        $resposta = array('tipo' => 'warning', 'mensagem' => $mensagem);
        foreach($adicionais as $key=>$value)
        {
            $resposta[$key] = $value;
        }
        echo json_encode($resposta, JSON_FORCE_OBJECT);
        if($pararAplicacao == true)
        {
            die();
        }
    }
    
    public static function ResponderFalha($mensagem, $setHeader = false, $pararAplicacao = false, $adicionais = array())
    {
        if($setHeader == true)
        {
            header('Content-Type: application/json');
        }
        
        $resposta = array('tipo' => 'danger', 'mensagem' => $mensagem);
        foreach($adicionais as $key=>$value)
        {
            $resposta[$key] = $value;
        }
        echo json_encode($resposta, JSON_FORCE_OBJECT);
        if($pararAplicacao == true)
        {
            die();
        }
    }
}
