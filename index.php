<?php
/**
 * Created by PhpStorm.
 * User: MasakoKh or Sitthykun LY
 * Date: 4/5/15
 * Time: 1:01 PM
 */

// load class loader
require_once 'app/loader/ClassesLoader.php';

// execute app
\app\loader\ClassesLoader::load()->execute();

// load controller
