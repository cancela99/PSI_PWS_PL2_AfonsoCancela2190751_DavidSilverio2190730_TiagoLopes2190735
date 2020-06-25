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
                if($user->is_valid()){
                    $user->save();
                    Redirect::toRoute('stbox/login');
                }else{

                }
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
        $userData = Session::get('userData');
        //Verifica se o id do utilizador é o correto
        if($userData->id == $id){
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
            Redirect::toRoute('user/edit', $userData->id);
        }
    }

    //Função que vai buscar os dados à vista e atualiza os dados na base de dados
    public function update($id)
    {
        $userData = Session::get('userData');
        $user = User::find($id);

        //Verifica se o campo da password e da nova password estão em branco, caso estejam, altera os dados e avisa o utilizador
        if(Post::get('password') == "" && Post::get('newPassword') == ""){

            $post = Post::getAll();
            //Remove o campo newPassword do array
            \array_splice($post,5);

            $post['password'] = $userData->password;

            $user->update_attributes($post);

            if($user->is_valid()) {
                $user->save();

                Session::set('updated','Informações alteradas com sucesso');
                Redirect::toRoute('user/edit', $userData->id);
            }else {
                //Senão devolve a vista do perfil
                Redirect::flashToRoute('user/edit', ['user' => $user], $id);
            }

        }else{
            //Verifica se o campo da password ou se o da nova password estão em branco, se estiverem devolve a vista do perfil com uma aviso
            if(Post::get('password') == "" || Post::get('newPassword') == ""){
                Session::set('clearCamp','Impossível alterar palavra-passe. Campo vazio');
                Redirect::flashToRoute('user/edit', ['user' => $user], $id);
            }else{
                //Senão faz a alteração da password
                $post = Post::getAll();

                //Verifica se a password atual é a mesma que o utilizador escreveu
                if($user->password == hash('sha1',Post::get('password'),false)){
                    \array_splice($post,5);
                    $user->update_attributes($post);
                    $user->password = hash('sha1', Post::get('newPassword'), false);

                    //Verifica se o $user é válido, se for volta para a vista do perfil com um aviso
                    if($user->is_valid()){
                        $user->save();
                        Session::set('updated','Informações alteradas com sucesso');
                        Redirect::toRoute('user/edit', $userData->id);
                    } else {
                        //Senão volta para a vista de perfil
                        Redirect::flashToRoute('user/edit', ['user' => $user], $id);
                    }
                }else{
                    //Senão, devolve a vista com mensagem de erro
                    Session::set('wrongActualPass','Impossível alterar palavra-passe. Palavra-passe atual incorreta');
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
                        Session::destroy();
                        Session::set('bloqueado', 'Esta conta encontra-se bloqueada');
                        Redirect::toRoute('stbox/login');
                        break;
                    }else{
                        Session::set('userData', $user);

                        //Session::set('username', $username);
                        //Session::set('id', $user->id);
                        //Session::set('loggedIn', 'Já fez login');
                        //Session::set('admin', $user->admin);
                        //Session::set('password', $user->password);
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