<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController{

    //Função que mostra os utilizadores no backOffice
    public function backoffice(){
        //Verifica se o utilizador fez login
        if(isset($_SESSION['loggedIn'])){
            //Verifica se o utilizador que fez login é admin
            if($_SESSION['admin'] == 1){
                $users = User::all();
                return View::make('stbox.backoffice', ['users' => $users]);
            }else{
                //Senão for admin devolve uma vista com um erro
                $_SESSION['notAdmin'] = "É necessário ser admin para entrar aqui";
                return View::make('stbox.errorNotLoggedIn');
            }
        }else{
            //Senão fez login devolve uma vista com um erro
            $_SESSION['notLoggedIn'] = "Faça login com uma conta de admin";
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
        //$db = new mysqli('localhost', 'root', '', 'shuthebox');
        $searchedUser = Post::get('username');
        $matchedUsers = [];

        //Verifica se o campo de pesquisa tem algo escrito, se não tiver mostra todos os utilizadores que não são admins
        if($searchedUser == ''){
            return View::make('stbox.backoffice', ['users' => $users]);
        }else{
            foreach ($users as $user){
                if(preg_match("/$searchedUser/i", $user->username) && $user->admin == 0){
                    array_push($matchedUsers, $user);
                }else{
                    //echo 'false';
                    //break;
                }
            }
            return View::make('stbox.backoffice', ['users' => $matchedUsers]);
            Tracy\Debugger::barDump($matchedUsers);
        }

    }
}

//$query = "SELECT * FROM users WHERE username LIKE '%".$searchedUser."%' AND admin = 0";

//$queryResult = mysqli_query($db,$query);

//Enquanto houver dados da query ele coloca-os num array
/*while ($row[] = mysqli_fetch_object($queryResult)){
    $i = 0;
    $row[$i];
    $i++;
}*/

/*
$_SESSION['userSearched'] = $row;

//Se a query encontrar algum utilizador ele mostra esse utilizador na vista
if(mysqli_num_rows($queryResult) > 0){
    $_SESSION['resultados'] = mysqli_num_rows($queryResult);
    return View::make('stbox.backoffice');
}else{
    //Senão mostra todos os utilizadores e devolve uma mensagem de aviso
    $_SESSION['userSearched'] = null;
    $_SESSION['notFound'] = 'Username não encontrado';
    return View::make('stbox.backoffice', ['users' => $users]);
}*/