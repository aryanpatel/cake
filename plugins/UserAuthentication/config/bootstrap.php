<?php
/**
 * @package UserAuthentication/config
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

Configure::load('UserAuthentication.users');

collection((array) Configure::read('Users.config'))->each(function($file){
    Configure::load($file);
});

TableRegistry::config('Usres',['className' => Configure::read('Users.table')]);
TableRegistry::config('UserAuthentication.Users', ['className' => Configure::read('Users.table')]);

if (Configure::check('Users.auth')) {
    Configure::write('Auth.authenticate.all.userModel', Configure::read('Users.table'));
}