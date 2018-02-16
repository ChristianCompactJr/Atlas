<?php

if(!SessionController::TemSessao())
{
    header("location: ../login");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastrar Usuários - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>    
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               
        
        
        <div class ="container">
            <h1>Visualizar Projetos</h1>     
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        
       
    </body>
</html>
