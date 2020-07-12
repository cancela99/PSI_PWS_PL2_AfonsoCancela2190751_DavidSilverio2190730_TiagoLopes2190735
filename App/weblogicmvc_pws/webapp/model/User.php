<?php

class User extends \ActiveRecord\Model
{
    //Verificação se algum campo ficou em branco
    static $validates_presence_of = array(
        array('username', 'message' => 'O username é um campo obrigatório.'),
        array('primeiro_nome', 'message' => 'O nome é um campo obrigatório.'),
        array('apelido', 'message' => 'O apelido é um campo obrigatório.'),
        array('datanascimento', 'message' => 'A data de nascimento é um campo obrigatório.'),
        array('email', 'message' => 'O email é um campo obrigatório.'),
        array('password', 'message' => 'A password é um campo obrigatório.')
    );

    //Verificação se o username ou se o email já existem na BD
    /*static $validates_uniqueness_of = array(
        array('username', 'message' => 'O nome de utilizador em questão já se encontra em uso.'),
        array('email', 'message' => 'O e-mail em questão já se encontra em uso')
        array(array())
    );*/

    //Verificação da estrutura do email
    static $validates_format_of = array(
        array('email', 'with' => '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', 'message' => 'A estrutura do email não está correta.')
    );

}