<?php

/**
 * LoginTrait
 * 
 * @package UserAuthentication\Controller\Traits
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Controller\Traits;
use UserAuthentication\Controller\Component\UsersAuthComponent;
use UserAuthentication\Exception\AccountNotActiveException;
use UserAuthentication\Exception\MissingEmailException;
use UserAuthentication\Exception\UserNotActiveException;
use UserAuthentication\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Hash;
/**
 *
 * @author pc18
 */
trait LoginTrait {
    use CustomUsersTableTrait;
    
    public function login(){
        if( $this->request->is('post') ) {
            pr($this->request());exit;
        }
    }
}
