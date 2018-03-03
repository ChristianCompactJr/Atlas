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
    
    function getPorcentual()
    {
        
        $dao = new ProjetoDAO();
        $total = $dao->GetTotalTarefasMicro($this->id);
        $concluidas = $dao->GetTotalTarefasMicroConcluidas($this->id);

        
        if($total != 0)
        {
             $porcentual = ceil((100 * $concluidas) / $total);
             return $porcentual; 
        }
        else
        {
            return 0;
        }
       
             
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
   
       $andamentoPrevisto = ($agora - strtotime($inicio));
       $andamentoPrevisto = round( $andamentoPrevisto / (60 * 60 * 24), 0);
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
