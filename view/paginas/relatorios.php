<?php

if(!SessionController::IsAdmin())
{
    header("location: login");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Relatórios - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>    
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        <h1>Relatórios</h1>
        
        <?php Carregador::CarregarViewFooter(); ?>
    </body>
</html>
