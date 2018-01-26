
<?php
include_once '../../util/Carregador.php';
Carregador::CarregarPacotes();
if(isset($_GET['url']))
{
    $url = $_GET['url'];
    $extencao = pathinfo($url, PATHINFO_EXTENSION);
    if($url[0] == '/' || $url[0] == "\\")
    {
        $url = substr($url, 1);
    }
    
    
    if(is_file('../../'.$url) && file_exists('../../'.$url))
    {
        $caminho = '../../'.$url;
    }
    else if(is_file($url) && file_exists($url))
    {
        $caminho =  $url;
    }
    else
    {
        echo "erro 404";
        return;
    }
    include_once $caminho;   

}

?>