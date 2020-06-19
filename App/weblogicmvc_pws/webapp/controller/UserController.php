<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;

class UserController extends BaseController implements ResourceControllerInterface
{

    /**
     * @inheritDoc
     */
    public function index()
    {
        //$users = User::all();
        //View::make('stbox.backoffice', ['users' => $users]);
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

    //Cria um novo utilizador e insere-o na base de dados
    public function store()
    {
        $users = User::all();
        $user = new User();
        $flag = 0;

        //Verifica se os campos estão em branco, caso estejam, devolve uma mensagem de erro
        if(Post::get('username') == "" || Post::get('primeiro_nome') == "" || Post::get('apelido') == "" || Post::get('dataNascimento') == "" || Post::get('email') == "" || Post::get('password') == ""){
            Session::set('signInError', 'Impossível registar. Campos vazios');;
            Redirect::toRoute('stbox/register');
        }else{
            $user->username = Post::get('username');
            $user->primeiro_nome = Post::get('primeiro_nome');
            $user->apelido = Post::get('apelido');
            $user->datanascimento = Post::get('dataNascimento');
            $user->email =  Post::get('email');
            $password = Post::get('password');
            //Cria uma hash a partir da password inserida
            $user->password = hash('sha1', $password,false);

            //Verifica se o username e se o email que o utilizador escreveu já existe na base de dados, caso exista altera o valor da flag
            foreach ($users as $registeredUser){
                if($registeredUser->username == $user->username){
                    $flag = 1;
                }
                if($registeredUser->email == $user->email){
                    $flag = 2;
                }
            }

            //Verifica se o valor da flag foi alterado, caso tenha sido alterado, devolve a vista com uma mensagem de erro
            if($flag == 1){
                Session::set('signInError', 'Impossível registar. Esse nome de utilizador já foi utilizado');
                Redirect::toRoute('stbox/register');
            }else if ($flag == 2){
                Session::set('signInError', 'Impossível registar. Esse email já foi utilizado');
                Redirect::toRoute('stbox/register');
            }else{
                $user->save();
                Redirect::toRoute('stbox/login');
            }
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    //Função que devolve os dados do user para a vista de editar o perfil
    public function edit($id)
    {
        //Verifica se o id do utilizador é o correto
        if($_SESSION['id'] == $id){
            $user = User::find($id);

            //Verifica se $user está a null
            if (is_null($user)) {
                // redirect to standard error page
            } else {
                //Senão a variável $user não estiver a null, a função devolve a vista de perfil a informação do utilizador
                View::make('stbox.profile', ['user' => $user]);
            }
        }else{
            //Senão for o id correto, devolve a vista com o id correto
            Redirect::toRoute('user/edit', $_SESSION['id']);
        }

    }

    //Função que vai buscar os dados à vista e atualiza os dados na base de dados
    public function update($id)
    {
        $user = User::find($id);

        //Verifica se o campo da password e da nova password estão em branco, caso estejam, altera os dados e avisa o utilizador
        if($_POST['password'] == "" && $_POST['newPassword'] == ""){

            $post = Post::getAll();
            //Remove o campo newPassword do array
            \array_splice($post,5);

            $post['password'] = $_SESSION['password'];

            $user->update_attributes($post);

            if($user->is_valid()) {
                $user->save();

                $_SESSION['updated'] = 'Informações alteradas com sucesso';
                Redirect::toRoute('user/edit', $_SESSION['id']);
            }else {
                //Senão devolve a vista do perfil
                Redirect::flashToRoute('user/edit', ['user' => $user], $id);
            }

        }else{
            //Verifica se o campo da password ou se o da nova password estão em branco, se estiverem devolve a vista do perfil com uma aviso
            if($_POST['password'] == "" || $_POST['newPassword'] == ""){
                $_SESSION['clearCamp'] = "Impossível alterar palavra-passe. Campo vazio";
                Redirect::flashToRoute('user/edit', ['user' => $user], $id);
            }else{
                //Senão faz a alteração da password
                $post = Post::getAll();
                \array_splice($post,5);
                $user->update_attributes($post);

                $user->password = hash('sha1', $_POST['newPassword'], false);
                $_SESSION['password'] = $user->password;
                //Verifica se o $user é válido, se for volta para a vista do perfil com um aviso
                if($user->is_valid()){
                    $user->save();
                    $_SESSION['updated'] = 'Informações alteradas com sucesso';
                    Redirect::toRoute('user/edit', $_SESSION['id']);
                } else {
                    //Senão volta para a vista de perfil
                    Redirect::flashToRoute('user/edit', ['user' => $user], $id);
                }
            }
        }
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    //Função que faz login no site
    public function login(){

        //$db = mysqli_connect('localhost', 'root', '', 'shuthebox');
//
        //
        //$username = $_POST['username'];
        //$password =  $_POST['password'];
        ////Faz o hash da password para verificar na base de dados
        //$passwordHashed = hash('sha1', $password, false);
//
        //$query = "SELECT id, username, password, admin, bloqueado FROM users WHERE username = '$username' AND password = '$passwordHashed'";
//
        //$loginResult = mysqli_query($db,$query);
//
        //$id = mysqli_fetch_object($loginResult);
//
        ////Verifica se a query encontra algum resultado
        //if(mysqli_num_rows($loginResult) == 1){
        //    //Verifica se o utilizador que está a fazer login tem a conta bloqueada, se tiver avisa
        //    if($id->bloqueado == 1){
        //        $_SESSION['bloqueado'] = 'Esta conta encontra-se bloqueada';
        //        Redirect::toRoute('stbox/login');
        //        }else{
        //        //Senão estiver bloqueada faz login no site
        //            $_SESSION['username'] = $username;
        //            $_SESSION['id'] = $id->id;
        //            $_SESSION['loggedIn'] = 'Já fez login';
        //            $_SESSION['admin'] = $id->admin;
        //            $_SESSION['password'] = $id->password;
        //            Redirect::toRoute('stbox/');
        //        }
        //}else{
        //    //Se a query não encontrar nenhum resultado, volta para a vista de login e avisa
        //    $_SESSION['loginErrors'] = 'Credenciais Incorretas';
        //    Redirect::toRoute('stbox/login');
        //}


        $users = User::all();
        $username = Post::get('username');
        $password = Post::get('password');
        $passwordHashed = hash('sha1',$password,false);

        if($username == "" || $password == ""){
            Session::set('blankField', 'Campo em branco');
            Redirect::toRoute('stbox/login');
        }else{
            foreach ($users as $user){
                if($user->username == $username && $user->password == $passwordHashed) {
                    if($user->bloqueado == 1){
                        Session::set('loginErrors', null);
                        Session::set('bloqueado', 'Esta conta encontra-se bloqueada');
                        Redirect::toRoute('stbox/login');
                        break;
                    }else{
                        Session::set('userData', $user);

                        Session::set('username', $username);
                        Session::set('id', $user->id);
                        Session::set('loggedIn', 'Já fez login');
                        Session::set('admin', $user->admin);
                        Session::set('password', $user->password);
                        \Tracy\Debugger::barDump(Session::get('userData'));
                        Redirect::toRoute('stbox/');
                        break;
                    }
                }else{
                    Session::set('loginErrors', 'Credenciais Incorretas');
                    Redirect::toRoute('stbox/login');
                }
            }
        }
    }

    //Função que faz logout
    public function logOut(){
        session_destroy();
        Redirect::toRoute('stbox/');
    }

}