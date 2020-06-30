<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\View;

class MatchController extends BaseController implements ResourceControllerInterface
{
    /**
     * @inheritDoc
     */
    public function index()
    {
        $matches = Match::all();
        View::make('stbox.matches', ['matches' => $matches]);
        //\Tracy\Debugger::barDump($matches);
    }

    /**
     * @inheritDoc
     */
    public function create()
    {
        // TODO: Implement create() method.
    }

    /**
     * @inheritDoc
     */
    public function store()
    {
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        $match = Match::find($id);

        \Tracy\Debugger::barDump($match);

        if (is_null($match)){

        }else{
            View::make('stbox.matches', ['match' => $match]);
        }
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