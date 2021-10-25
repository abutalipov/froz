<?php

use Model\Boosterpack_model;
use Model\Comment_model;
use Model\Login_model;
use Model\Post_model;
use Model\User_model;

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation_many(Post_model::get_all(), 'default');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_boosterpacks()
    {
        $posts =  Boosterpack_model::preparation_many(Boosterpack_model::get_all(), 'default');
        return $this->response_success(['boosterpacks' => $posts]);
    }

    public function login()
    {
        $login = $this->input->get_post('login');
        $password = $this->input->get_post('password');
        $userModel = Login_model::login($login,$password);
        if (!$userModel){
            return $this->response_error('Ошибка авторизации',[],401);
        }
        return $this->response_success(['user'=>User_model::preparation($userModel, 'default')]);
    }

    public function logout()
    {
        Login_model::logout();
        return $this->response_success();
    }

    public function comment()
    {
        $user = Login_model::authUser();
        if (!$user){
            return $this->response_error('Нет доступа',[],403);
        }
        $assign_id=$this->input->get_post('assign_id');//todo добавить проверку поста
        $reply_id=$this->input->get_post('reply_id');
        $text=$this->input->get_post('text');
        if (!intval($assign_id) or !trim($text)){
            return $this->response_error('Ошибка валидации данных',[],400);
        }
        $create = ['user_id'=>$user->get_id(),'assign_id'=>$assign_id,'text'=>$text,'likes'=>0];
        if($reply_id){//todo добавит проверку родителя
            $create['reply_id']=$reply_id;
        }
        $comment = Comment_model::create($create);

        return $this->response_success(['comment'=>Comment_model::preparation($comment, 'default')]);
    }

    public function like_comment(int $comment_id)
    {
        // TODO: task 3, лайк комментария
    }

    public function like_post(int $post_id)
    {
        // TODO: task 3, лайк поста
    }

    public function add_money()
    {
        // TODO: task 4, пополнение баланса

        $sum = (float)App::get_ci()->input->post('sum');

    }

    public function get_post(int $post_id) {
        // TODO получения поста по id
    }

    public function buy_boosterpack()
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        // TODO: task 5, покупка и открытие бустерпака
    }





    /**
     * @return object|string|void
     */
    public function get_boosterpack_info(int $bootserpack_info)
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }


        //TODO получить содержимое бустерпака
    }
}
