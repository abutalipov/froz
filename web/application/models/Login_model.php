<?php

namespace Model;

use App;
use Exception;
use System\Core\CI_Model;

class Login_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

    }

    public static function logout()
    {
        App::get_ci()->session->unset_userdata('id');
    }

    public static function authUser(): ?User_model
    {
        $session_id = User_model::get_session_id();
        if (intval($session_id))
            return new User_model($session_id);
        return null;
    }
    /**
     * @return false|User_model|null
     * @throws Exception
     */
        public static function login($login,$password)
    {
        $userModel = User_model::find_user_by_email($login);
        if(!$userModel){
            return null;
        }
        if($userModel->get_password() !== $password){
            return false;
        }
        self::start_session($userModel->get_id());
        return $userModel;
    }

    public static function start_session(int $user_id)
    {
        // если перенедан пользователь
        if (empty($user_id))
        {
            throw new Exception('No id provided!');
        }

        App::get_ci()->session->set_userdata('id', $user_id);
    }
}
