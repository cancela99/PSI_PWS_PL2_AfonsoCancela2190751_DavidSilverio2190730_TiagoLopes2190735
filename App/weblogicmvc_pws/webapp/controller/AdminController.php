<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController{


    public function backoffice(){
        if(isset($_SESSION['loggedIn'])){
            if($_SESSION['admin'] == 1){
                $users = User::all();
                return View::make('stbox.backoffice', ['users' => $users]);
            }else{
                $_SESSION['notAdmin'] = "É necessário ser admin para entrar aqui";
                return View::make('stbox.errorNotLoggedIn');
            }
        }else{
            $_SESSION['notLoggedIn'] = "Faça login com uma conta de admin";
            return View::make('stbox.errorNotLoggedIn');
        }

    }

    public function blockUser($id){

        $user = User::find($id);

        $user->bloqueado = 1;
        $user->databloqueado = date("Y/m/d");
        $user->save();

        $this->backoffice();
    }


    public function unblockUser($id){

        $user = User::find($id);

        $user->bloqueado = 0;
        $user->databloqueado = null;
        $user->save();

        $this->backoffice();
    }

    public function searchUser(){
        $users = User::all();
        $db = new mysqli('localhost', 'root', '', 'shuthebox');
        $searchedUser = Post::get('username');

        if(Post::get('username') == ''){
            return View::make('stbox.backoffice', ['users' => $users]);
        }

        $query = "SELECT * FROM users WHERE username LIKE '%".$searchedUser."%' AND admin = 0";

        $queryResult = mysqli_query($db,$query);


        while ($row[] = mysqli_fetch_object($queryResult)){
            $i = 0;
            $row[$i];
            $i++;
        }
        //$id = mysqli_fetch_object($queryResult);

        $_SESSION['userSearched'] = $row;

        if(mysqli_num_rows($queryResult) > 0){
            $_SESSION['resultados'] = mysqli_num_rows($queryResult);
            return View::make('stbox.backoffice');
        }else{

            $_SESSION['userSearched'] = null;
            $_SESSION['notFound'] = 'Username não encontrado';
            return View::make('stbox.backoffice', ['users' => $users]);
        }
    }
}