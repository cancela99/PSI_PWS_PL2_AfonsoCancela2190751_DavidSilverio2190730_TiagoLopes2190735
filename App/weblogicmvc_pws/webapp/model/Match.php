<?php


class Match extends \ActiveRecord\Model
{
    static $validates_presence_of = array(
            array('pontuacao'),
            array('vencedor'),
            array('user_id'),
    );

    static $belongs_to = array(
        array('users')
    );
}