<?php


class Match extends \ActiveRecord\Model
{
    //Verificação se falta algum desses campos ao inserir na BD
    static $validates_presence_of = array(
            array('pontuacao'),
            array('vencedor'),
            array('user_id'),
    );

    //Faz a junção entre as partidas e os users para termos acesso ao username através das partidas
    static $belongs_to = array(
        array('users')
    );
}