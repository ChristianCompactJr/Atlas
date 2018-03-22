<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Página Inicial - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>
        <style>
            .error {
  margin: 0 auto;
  text-align: center;
}

.error-code {
  bottom: 60%;
  color: #2d353c;
  font-size: 96px;
  line-height: 100px;
}

.error-desc {
  font-size: 12px;
  color: #647788;
}

.m-b-10 {
  margin-bottom: 10px!important;
}

.m-b-20 {
  margin-bottom: 20px!important;
}

.m-t-20 {
  margin-top: 20px!important;
}

        </style>
    </head>
    <body>
        
        <?php if(SessionController::TemSessao()) {Carregador::CarregarViewNavbar();} ?>  
        <div class="error">
            <div class="error-code m-b-10 m-t-20">Erro 404 <i class="fa fa-exclamation fa-sm"></i></div>
                <h3 class="font-bold">Nós não conseguimos encontrar a página.</h3>

            <div class="error-desc">
                Desculpe, mas a pagina que você esta procurando não foi encontrada ou não existe.<br />
                Tente atualizar a página ou clique no botão abaixo para voltar para a página inicial.
                <div>
                    <a class=" login-detail-panel-button btn" href="<?php echo UrlManager::GetPathToView(PROJECT_FRIENDLY_HOMEPAGE)?>">
                        <i class="fa fa-arrow-left"></i>
                            Voltar para a página inicial                       
                    </a>
                </div>
            </div>
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
    </body>
</html>
