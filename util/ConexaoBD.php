<?php

abstract class ConexaoBD {
    public static function CriarConexao()
    {
        $con = new PDO(PROJECT_SETTINGS['sgbd'].": host=".PROJECT_SETTINGS['ip'].";dbname=".PROJECT_SETTINGS['base'].";charset=utf8", PROJECT_SETTINGS['usuario'], PROJECT_SETTINGS['senha']);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $con;
    }
}
