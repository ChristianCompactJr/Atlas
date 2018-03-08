<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseDAO
 *
 * @author chris
 */
abstract class DAO {
    private static $con = null;
        
    public function __construct()
    {
        self::init();
        date_default_timezone_set('Brazil/East');
    }

    public final  function fecharConexao()
    {
        self::$con = null;
    }

    protected final function LimparString($str)
    {
        $str = trim($str);
        $str = strip_tags($str);
        return preg_replace('/^[\pZ\p{Cc}\x{feff}]+|[\pZ\p{Cc}\x{feff}]+$/ux', '', $str);
    }



    protected final function getCon()
    {
        self::init();

        return self::$con;
    }

    private static function init()
    {
        if(self::$con == null)
        {
            self::$con = ConexaoBD::CriarConexao();
        }
    }
}
