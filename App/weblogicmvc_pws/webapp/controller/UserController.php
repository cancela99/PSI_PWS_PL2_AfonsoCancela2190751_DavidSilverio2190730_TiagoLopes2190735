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

    /**CRIAR E GRAVAR UM NOVO UTILIZADOR NA BASE DE DADOS**/
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
}