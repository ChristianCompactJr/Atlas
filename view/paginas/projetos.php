<?php

if(!SessionController::TemSessao())
{
    header("location: login");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Projetos - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>    
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        <h1>Relatórios</h1>
        
        <?php Carregador::CarregarViewFooter(); ?>
    </body>
</html>
