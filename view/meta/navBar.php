<?php

$usuario = SessionController::GetUsuario();

//$paginaInicial = new LinkMenu("Página Inicial", "inicial");


$usuarios = new LinkMenu("Usuários", "#");
$cadastrarUsuario = new LinkMenu("Cadastrar Usuários", "usuarios/cadastrar");
$visualizarUsuario = new LinkMenu("Visualizar Usuários", "usuarios/visualizar");

$usuarios->AdicionarFilho($cadastrarUsuario);
$usuarios->AdicionarFilho($visualizarUsuario);

if(SessionController::IsAdmin())
{
    $projetos = new LinkMenu("Projetos", "#");
    $cadastrarProjetos = new LinkMenu("Cadastrar Projeto", "projetos/cadastrar");
    $visualizarProjetos = new LinkMenu("Visualizar Projetos", "projetos/");
    $projetos->AdicionarFilho($cadastrarProjetos);
    $projetos->AdicionarFilho($visualizarProjetos);
}
else
{
    $projetos = new LinkMenu("Projetos", "projetos/");
}


$relatorios = new LinkMenu("Relatórios", 'relatorios');

$alterardados = new LinkMenu("Alterar Dados", 'alterar-dados');
$alterarhabilidades = new LinkMenu('Habilidades', 'habilidades');

$confhabilidades = new LinkMenu('Configurar Habilidades', 'configurar-habilidades');


$perfilPai = New LinkMenu('<img alt="" src="'.UrlManager::GetPathToView($usuario->getFoto()).'" class = "perfil-menu"><span id = "nome-menu">'.$usuario->getNome().'</span>', "#");
$perfilPai->AdicionarFilho($alterardados);
$perfilPai->AdicionarFilho($alterarhabilidades);

?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
          <a class="navbar-brand" href="<?php echo UrlManager::GetPathToView("projetos/") ?>">
               <img alt="Atlas" src="<?php echo UrlManager::GetPathToView("recursos/img/logo_limpo.png") ?>" class = "logo-menu">
          </a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
         
          <?php 
          //echo $paginaInicial->ToHTML(); 
          echo $projetos->ToHTML();
          if(SessionController::IsAdmin() == true)
          {
              echo $usuarios->ToHTML();
              echo $relatorios->ToHTML();
              echo $confhabilidades->ToHTML();
          }
          ?>
          
        </ul>
        <ul class="nav navbar-nav navbar-right"> 

          <?php
            echo $perfilPai->ToHTML();
          ?>
            <li><a href ="<?php echo UrlManager::GetPathToController("usuario/logoutController")?>">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
