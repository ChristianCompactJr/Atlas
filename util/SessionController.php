<?php



abstract class SessionController
{
    public static function CriarSessao(Usuario $usuario)
    {
        $_SESSION['usuario'] = $usuario;
    }
    
    public static function TemSessao()
    {

        if(isset($_SESSION['usuario']) && $_SESSION['usuario']->getAtivo() == true)
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
        $dao = new UsuarioDAO();
        $token = '';
        do
        {
            $token = bin2hex(random_bytes(32));
            
        }while($dao->VerificarToken($token) != false);
        
        
        
        $dao->AtualizarToken($_SESSION['usuario'], $token);
        setcookie("token", $token, time()+2592000, '/');
    }
    
    public static function IsAdmin()
    {
        if(!isset($_SESSION['usuario']) || $_SESSION['usuario']->getAdministrador() == false)
        {
            return false;
        }
        return true;
    }


    public static function RemoverToken()
    {
        setcookie("token",NULL,time()-10000, '/');
    }
    
    public static function GetUsuario()
    {
        return $_SESSION['usuario'];
    }
    
    public static function CriarCSRFToken()
    {
        $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
    }
    public static function VerificarCSRFToken()
    {
        if (!isset($_POST['csrftoken']) || !hash_equals($_SESSION['csrftoken'], $_POST['csrftoken']))
        {
            JSONResponder::ResponderFalha("Você não tem permição para acessar essa funcionalidade", true, true);
        }
    }
    
    public static function GetCSRFToken()
    {
        return $_SESSION['csrftoken'];
    }
    
    
}


if(session_status() == PHP_SESSION_NONE)
{
    @session_start();
}

if(!isset($_POST['csrftoken']))
{
    SessionController::CriarCSRFToken();
}

if(!empty($_POST))
{
    SessionController::VerificarCSRFToken();
}

?>