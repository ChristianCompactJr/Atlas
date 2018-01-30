<?php

abstract class ConexaoBD {
    public static function CriarConexao()
    {
        $con = new PDO(PROJECT_SGBD.": host=".PROJECT_HOST.";dbname=".PROJECT_BASE.";charset=utf8", PROJECT_USER, PROJECT_PASSWORD);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $con;
    }
}
