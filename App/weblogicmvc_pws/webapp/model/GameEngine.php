<?php


class GameEngine
{
    public $tabuleiro;
    private $estadoJogo;

    public function iniciarJogo() {
        $this->tabuleiro = new Tabuleiro();
        $this->estadoJogo = 1;
    }

    public function getEstadoJogo() {
        return $this->estadoJogo;
    }

    public function updateEstadoJogo() {
        if($this->estadoJogo == 1) {
            $this->estadoJogo = 2;
        } else if($this->estadoJogo == 2) {
            $this->estadoJogo = 0;
        }
    }

}