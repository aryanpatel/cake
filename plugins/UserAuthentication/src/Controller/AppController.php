<?php
/**
 * AppController
 * 
 * @package UserAuthentication/Controller
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */
namespace UserAuthentication\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');
        $this->loadComponent('UserAuthentication.UsersAuth');
    }
}
