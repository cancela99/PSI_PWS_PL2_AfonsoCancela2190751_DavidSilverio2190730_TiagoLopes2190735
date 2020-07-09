<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Data;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;


class AdminController extends BaseController{

    //Função que mostra os utilizadores no backOffice

    public function backoffice(){

        //Verifica se o utilizador fez login
        if(Session::has('userData')){
            $userData = Session::get('userData');
            //Verifica se o utilizador que fez login é admin
            if($userData->admin == 1){
                $users = User::all();
                return View::make('stbox.backoffice', ['users' => $users]);
            }else{
                //Senão for admin devolve uma vista com um erro
                Session::set('notAdmin','É necessário ser admin para entrar aqui');
                return View::make('stbox.errorNotLoggedIn');
            }
        }else{
            //Senão fez login devolve uma vista com um erro
            Session::set('notLoggedIn','Faça login com uma conta de admin');
            return View::make('stbox.errorNotLoggedIn');
        }
    }

    //Função que bloqueia os utilizadores
    public function blockUser($id){

        $user = User::find($id);

        //Altera o campo "bloqueado" na BD para bloquear o utilizador
        $user->bloqueado = 1;
        //Formata a data
        $user->databloqueado = date("Y/m/d");
        $user->save();
        //Chama-se a função para devolver a vista com as alterções
        $this->backoffice();
    }

    //Função que desbloqueia os utilizadores
    public function unblockUser($id){

        $user = User::find($id);

        //Altera o campo "bloqueado" na BD para desbloquear o utilizador
        $user->bloqueado = 0;
        //Formata a data
        $user->databloqueado = null;
        $user->save();
        //Chama-se a função para devolver a vista com as alterções
        $this->backoffice();
    }

    //Função que permite procurar um utilizador no backOffice
    public function searchUser(){

        $users = User::all();
        $searchedUser = Post::get('username');
        $userFinder = User::find('all', array('conditions' => "username LIKE '". $searchedUser ."%'"));

        if(Post::get('username') == ''){
            return View::make('stbox.backoffice', ['users' => $users]);
        }

        if($userFinder != null){
            return View::make('stbox.backoffice', ['users' => $userFinder]);
            Session::set('userSearched', 'Utilizadores encontrados');
        } else {
            $erro = 'Utilizador não encontrado';
            return View::make('stbox.backoffice',['users' => $users, 'backofficeError' => $erro]);
        }
    }

    public function clearSearch(){
        $users = User::all();
        return View::make('stbox.backoffice', ['users' => $users]);
    }

}