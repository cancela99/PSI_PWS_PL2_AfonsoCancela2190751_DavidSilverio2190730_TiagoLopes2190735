<?php


class Match extends \ActiveRecord\Model
{
    static $validates_presence_of = array(
        array('pontuacao', 'message' => 'YooaaH it must be provided'),
        array('data')
    );
}