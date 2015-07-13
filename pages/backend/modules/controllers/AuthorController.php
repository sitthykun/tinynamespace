<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 5/31/15
 * Time: 5:34 PM
 */

namespace pages\backend\modules\controllers;


use app\Controller;
use pages\backend\modules\models\AuthorModel;

class AuthorController extends Controller
{
    /**
     *
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function index()
    {
        // model
        $authorModel = new AuthorModel();
        // renders view page and passes params
        $this->renderView(__DIR__, 'home', ['hello', 'world']);
        // renders view page
        // $this->renderView(__DIR__, 'home');
    }
}