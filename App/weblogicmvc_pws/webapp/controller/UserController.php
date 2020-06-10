<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;

class UserController extends BaseController implements ResourceControllerInterface
{

    /**
     * @inheritDoc
     */
    public function index()
    {
        $users = User::all();
        View::make('stbox.backoffice', ['users' => $users]);
    }

    /**
     * @inheritDoc
     */
    public function create()
    {
        View::make('stbox.register');
    }

    /**
     * @inheritDoc
     */

    /**CRIA UM NOVO UTILIZADOR NA BASE DE DADOS**/
    public function store()
    {
        $user = new User();

        $user->username = Post::get('username');
        $user->primeiro_nome = Post::get('primeiro_nome');
        $user->apelido = Post::get('apelido');
        $user->datanascimento = Post::get('dataNascimento');
        $user->email =  Post::get('email');

        $password = Post::get('password');
        $user->password = hash('sha1',$password,false);
        $user->save();
        Redirect::toRoute('stbox/login');
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }


    public function edit($id)
    {


        if($_SESSION['id'] == $id){
            $user = User::find($id);


            if (is_null($user)) {
                // redirect to standard error page
            } else {
                View::make('stbox.profile', ['user' => $user]);
            }
        }else{
            //URL::toRoute('user/edit', $_SESSION['id']);
            Redirect::toRoute('user/edit', $_SESSION['id']);
        }

    }

    public function update($id)
    {
        $user = User::find($id);


        if($_POST['password'] == ""){
            $_SESSION['errorProfile'] = 'É obrigatório colocar password.';
            Redirect::toRoute('user/edit', $_SESSION['id']);
        }else{
            $user->update_attributes(Post::getAll());
            $user->password = hash('sha1', $_POST['password'], false);
            if($user->is_valid()){
                $user->save();
                Redirect::toRoute('user/edit', $_SESSION['id']);
            } else {
                Redirect::flashToRoute('user/edit', ['user' => $user], $id);
            }
        }
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function login(){

        $db = mysqli_connect('localhost', 'root', '', 'shuthebox');

        $username = $_POST['username'];
        $password =  $_POST['password'];
        $passwordHashed = hash('sha1', $password, false);

        $query = "SELECT id, username, password, admin, bloqueado FROM users WHERE username = '$username' AND password = '$passwordHashed'";

        $loginResult = mysqli_query($db,$query);

        $id = mysqli_fetch_object($loginResult);


        if(mysqli_num_rows($loginResult) == 1){
            if($id->bloqueado == 1){
                $_SESSION['bloqueado'] = 'Esta conta encontra-se bloqueada';
                Redirect::toRoute('stbox/login');
                }else{
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $id->id;
                    $_SESSION['loggedIn'] = 'Já fez login';
                    $_SESSION['admin'] = $id->admin;
                    Redirect::toRoute('stbox/');
                }
        }else{
            $_SESSION['loginErrors'] = 'Credenciais Incorretas';
            Redirect::toRoute('stbox/login');
        }
    }

    public function logOut(){
        session_destroy();
        Redirect::toRoute('stbox/');
    }


    
}