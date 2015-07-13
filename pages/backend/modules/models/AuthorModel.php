<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 6/13/15
 * Time: 2:26 AM
 */

namespace pages\backend\modules\models;


use app\Model;

class AuthorModel extends Model
{
    /**
     * @return array
     */
    public function getLogin()
    {
        $this->connectedContent();
        $data = $this->getFetch('SELECT * FROM pro_users');
        $this->closeConnection();

        // return ['my model', 'my login data'];
        return $data;
    }
}