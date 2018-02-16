<?php

    
    $_POST['inicio'] = str_replace('/', '-', $_POST['inicio']);
    $_POST['prazo'] = str_replace('/', '-', $_POST['prazo']);
    
    print_r($_POST);
    
    $inicio = $_POST['inicio'];
$inicio = str_replace('/', '-', $inicio);

 $prazo = $_POST['prazo'];
$prazo = str_replace('/', '-', $prazo);
     if(strtotime($inicio) > strtotime($prazo))
     {
         echo "O inicio é maior que o prazo";
     }
     else
     {
         echo "O prazo é o maior que o inicio";
     }
     
     $dao = new ProjetoDAO();
     $dao->CriarProjeto($_POST['nome'], $_POST['cliente'], $_POST['master'], $_POST['dev'], $inicio, $prazo, $_POST['backlog'], $_POST['obs'], $_POST['estagio']);
     //$diferenca = round(abs(strtotime($_POST['agora']) - strtotime($resultadoTentativas->data_hora)) / 60, 0);

?>

