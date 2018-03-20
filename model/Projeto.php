<?php

class Projeto {
    
    private $id;
    private $nome;
    private $scrumMaster;
    private $inicio;
    private $prazo;
    private $cliente;
    private $observacoes;
    private $estagio;
    
    
    function __construct($id, $nome, $scrumMaster, $inicio, $prazo, $cliente, $observacoes, $estagio) {
        $this->id = $id;
        $this->nome = $nome;
        $this->scrumMaster = $scrumMaster;
        $this->prazo = $prazo;
        $this->inicio = $inicio;
        $this->cliente = $cliente;
        $this->observacoes = $observacoes;
        $this->estagio = $estagio;
    }
    
    function getEquipe()
    {
        $dao = new ProjetoDAO();
       
        return $dao->GetDevsProjeto($this->id);
    }
    
    
    public function podeConcluir()
    {
        $macrodao = new TarefaMacroDAO();
       $macros = $macrodao->getTarefasMacro($this->id);
       $microdao = New TarefaMicroDAO();
       foreach($macros as $mac)
       {
          $tmpMicros = $microdao->getTarefasMicro($mac->getId());
          
          foreach($tmpMicros as $tmpmic)
          {
              if($tmpmic->getEstado() != "Qualificada")
              {
                  return false;
              }
          }
       }
       
       return true;
    }
    
    public function getPontosBurndown()
    {
        $retorno = array('ideal' => array(), 'progresso' => array());
        
        $macrodao = new TarefaMacroDAO();
       $macros = $macrodao->getTarefasMacro($this->id);
       $microdao = New TarefaMicroDAO();
       $totalEstimativa = 0;
         foreach($macros as $mac)
       {
          $tmpMicros = $microdao->getTarefasMicro($mac->getId());
          
          foreach($tmpMicros as $tmpmic)
          {
              $totalEstimativa += $tmpmic->getEstimativa();
          }
       }
       
       $retorno['ideal'][] = array('valor' => $totalEstimativa, 'dia' => $this->inicio);
       $retorno['ideal'][] = array('valor' => 0, 'dia' => $this->prazo);
       
       $projetodao = new ProjetoDAO();
       
       $dias = $projetodao->GetDatasBurndown($this->id);
       $aux = $totalEstimativa;
       foreach($dias as $diaburn)
       {
           $valordia = $projetodao->GetValorDiaBurdown($this->id, $diaburn, true);
           $aux += $valordia;
           if($aux > $totalEstimativa)
           {
               $aux = $totalEstimativa;
           }
           
           $retorno['progresso'][] = array('valor' => $aux, 'dia' => $diaburn);
           
       }
      
       return $retorno;
        
    }
    
    
    function getPorcentual()
    {
          
        
       $macrodao = new TarefaMacroDAO();
       $macros = $macrodao->getTarefasMacro($this->id);
       $microdao = New TarefaMicroDAO();
       $micros = array();
       foreach($macros as $mac)
       {
          $tmpMicros = $microdao->getTarefasMicro($mac->getId());
          
          foreach($tmpMicros as $tmpmic)
          {
              $micros[] = $tmpmic;
          }
       }
       
       if(count($micros) == 0)
       {
           return 0;
       }
       
       
       $totalEstimativa = 0;
       $estimativaCompleta = 0;
       
       foreach($micros as $tarmic)
       {
           $totalEstimativa += $tarmic->getEstimativa();
           if($tarmic->getEstado() == "Instável")
           {
               $estimativaCompleta += $tarmic->getEstimativa() / 2;
           }
           else if ($tarmic->getEstado() == "Qualificada")
           {
               $estimativaCompleta += $tarmic->getEstimativa();
           }
       }    
       
        return  ceil((100 * $estimativaCompleta) / $totalEstimativa); 
            
    }
    
    function getFarol()
    {
        
        if($this->estagio == 'Entrege')
        {
            return 'entrege';
        }
        
        date_default_timezone_set('Brazil/East');
        
        
        
        $agora = time();
       $inicio = date('Y-m-d', strtotime($this->inicio));
       $prazo = date('Y-m-d', strtotime($this->prazo));
       
       if(strtotime($prazo) < $agora)
       {
           return 'vermelho';
       }
       
       $diferenca = strtotime($prazo) - strtotime($inicio);
       $diferenca = round($diferenca / (60 * 60 * 24));
   
       $andamentoAgora = ($agora - strtotime($inicio));
      
       $andamentoAgora = round( $andamentoAgora / (60 * 60 * 24), 0);
       $andamentoPrevisto = ceil((100 / $diferenca) * $andamentoAgora);
       
       
       $porcentagem = $this->getPorcentual();
       
       
       $metrica = $porcentagem - $andamentoPrevisto;
 
       if($metrica >= 15)
       {
           return "verde";
       }
       else if($metrica <= -15)
       {
           return "vermelho";
       }
       else
       {
           return "amarelo";
       }
       
    }
    
    function getInicioFormatted()
    {
        return date("d/m/Y", strtotime($this->inicio));
    }
    function getPrazoFormatted()
    {
         return date("d/m/Y", strtotime($this->prazo));
    }

    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getScrumMaster() {
        return $this->scrumMaster;
    }

    function getInicio() {
        return $this->inicio;
    }

    function getPrazo() {
        return $this->prazo;
    }

    function getCliente() {
        return $this->cliente;
    }
    
    

    function getObservacoes() {
        return $this->observacoes;
    }
    
    function GetObservacoesFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->observacoes);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
    }

    function getEstagio() {
        return $this->estagio;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setScrumMaster($scrumMaster) {
        $this->scrumMaster = $scrumMaster;
    }

    function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    function setPrazo($prazo) {
        $this->prazo = $prazo;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setBacklog($backlog) {
        $this->backlog = $backlog;
    }

    function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    function setEstagio($estagio) {
        $this->estagio = $estagio;
    }


    
}
