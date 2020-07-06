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

    //Valida se os dados são válidos, cria um novo utilizador e insere-o na base de dados
    public function store()
    {
        $user = new User();
        $users = User::all();
        $flag = 0;

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
            Redirect::FlashtoRoute('stbox/register', ['user' => $user]);
        }else if ($flag == 2){
            Session::set('signInError', 'Impossível registar. Esse email já foi utilizado');
            Redirect::FlashtoRoute('stbox/register', ['user' => $user]);
        }else{
            if ($user->is_valid()){
                $user->save();
                Redirect::toRoute('stbox/login');
            } else {
                Redirect::flashToRoute('stbox/register', ['user' => $user]);
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
        if(Session::has('userData')){
            $userData = Session::get('userData');
            //Verifica se o id do utilizador é o correto
            if($userData->id == $id){
                $user = User::find($id);
                //Senão a variável $user não estiver a null, a função devolve a vista de perfil a informação do utilizador
                return View::make('stbox.profile', ['userInfo' => $user]);
            }else{
                //Senão for o id correto, devolve a vista com o id correto
                Redirect::toRoute('user/edit', $userData->id);
            }
        }else{
            Session::set('notLoggedIn','É necessário realizar login');
            View::make('stbox.errorNotLoggedIn');
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

        $username = Post::get('username');
        $password = Post::get('password');
        $passwordHashed = hash('sha1', $password,false);

        if($username == "" || $password == ""){
            Session::set('blankField', 'Campo em branco');
            Redirect::toRoute('stbox/login');
        }else{
            $user = User::find_by_username_and_password($username, $passwordHashed);
            if($user == null){
                Session::set('loginErrors', 'Credenciais Incorretas');
                Redirect::FlashtoRoute('stbox/login', ['userError' => $username]);
            }else{
                if($user->bloqueado == 1){
                    Session::set('bloqueado', 'Esta conta encontra-se bloqueada');
                    Redirect::FlashtoRoute('stbox/login', ['userError' => $username]);
                }else{
                    Session::set('userData', $user);
                    Redirect::toRoute('stbox/');
                }
            }
        }
    }

    //Função que faz logout
    public function logOut(){
        Session::destroy();
        Redirect::toRoute('stbox/login');
    }

}