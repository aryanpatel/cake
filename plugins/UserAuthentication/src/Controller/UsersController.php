<?php
/**
 * UsersController
 * 
 * @package UserAuthentication/Controller
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */
namespace UserAuthentication\Controller;

use UserAuthentication\Controller\AppController;
use UserAuthentication\Model\Table\UsersTable;
use UserAuthentication\Controller\Traits\LoginTrait;
use Cake\Core\Configure;
use Cake\ORM\Table;

class UsersController Extends AppController{
    use LoginTrait;
}