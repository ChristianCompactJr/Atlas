<?php


if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

abstract class SessionController
{
    public static function CriarSessao(Usuario $usuario)
    {
        $_SESSION['usuario'] = $usuario;
    }
    
    public static function TemSessao()
    {

        if(isset($_SESSION['usuario']))
        {
            return true;
        }
        else if(isset($_COOKIE["token"]))
        {
            $dao = new UsuarioDAO();
            $usuario = $dao->VerificarToken($_COOKIE["token"]);
            if($usuario == false)
            {
                return false;
            }
            self::CriarSessao($usuario);
           
            return true;
       }
       
       return false;
        
    }
    
    public static function CriarToken()
    {
        $length = 40;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $dao = new UsuarioDAO();
        $token = '';
        do
        {
            $token = '';
            
            for ($i = 0; $i < $length; $i++) {
                $token .= $characters[rand(0, $charactersLength - 1)];
            }
            
        }while($dao->VerificarToken($token) != false);
        $dao->AtualizarToken($_SESSION['usuario'], $token);
        setcookie("token", $token, time()+864000, '/');
    }
    
    public static function RemoverToken()
    {
        setcookie("token",NULL,time()-10000, '/');
    }
    
    public static function GetUsuario()
    {
        return $_SESSION['usuario'];
    }
    
    
}

?>