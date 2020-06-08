<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
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
        Redirect::toRoute('stbox/');
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
    }

    public function update($id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function login(){

        $db = mysqli_connect('localhost', 'root', '', 'shuthebox');

        //echo "<script>console.log('Estou no login');</script>";
        $username = $_POST['username'];
        $password =  $_POST['password'];
        $passwordHashed = hash('sha1', $password, false);

        $query = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$passwordHashed'";

        $loginResult = mysqli_query($db,$query);

        if(mysqli_num_rows($loginResult) == 1){
            $_SESSION['username'] = $username;
            $_SESSION['loggedIn'] = 'Já fez login';
            Redirect::toRoute('stbox/');
        }else{
            /*DEVOLVER PÁGINA DE LOGIN COM O USERNAME INSERIDO NA TEXTBOX E AVISO DE CREDENCIAIS INCORRETAS */
            Redirect::toRoute('stbox/login');
        }
    }

    public function logOut(){
        session_destroy();
        Redirect::toRoute('stbox/');
    }
    
}