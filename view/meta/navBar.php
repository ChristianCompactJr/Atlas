<?php

$usuario = SessionController::GetUsuario();

$paginaInicial = new LinkMenu("Página Inicial", "inicial");


$usuarios = new LinkMenu("Usuários", "#");
$cadastrarUsuario = new LinkMenu("Cadastrar Usuários", "usuarios/cadastrar");
$visualizarUsuario = new LinkMenu("Visualizar Usuários", "usuarios/visualizar");

$usuarios->AdicionarFilho($cadastrarUsuario);
$usuarios->AdicionarFilho($visualizarUsuario);


$projeto = new LinkMenu("Projetos", "projetos");

$relatorios = new LinkMenu("Relatórios", 'relatorios');

$voltas = UserRootViewFinder::GetBackSlashes();

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
          <a class="navbar-brand" href="<?php echo $voltas;?>inicial">
               <img alt="Atlas" src="<?php echo $voltas;?>recursos/img/logo_limpo.png" class = "logo-menu">
          </a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
         
          <?php 
          echo $paginaInicial->ToHTML(); 
          echo $projeto->ToHTML();
          if(SessionController::IsAdmin() == true)
          {
              echo $usuarios->ToHTML();
              echo $relatorios->ToHTML();
          }
          ?>
          
        </ul>
        <ul class="nav navbar-nav navbar-right"> 

          <li class="dropdown">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img alt="" src="<?php echo $voltas.$usuario->getFoto(); ?>" class = "perfil-menu"><?php echo $usuario->getNome(); ?> <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo $voltas;?>alterar-dados">Alterar Dados</a></li>
            </ul>
          </li>
          <li><a href ="<?php echo $voltas;?>controller/usuario/logoutController.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
