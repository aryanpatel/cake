<?php

/**
 * UserValidationTrait
 * 
 * @package UserAuthentication/Controller/Trait
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Controller\Traits;

use UserAuthentication\Exception\TokenExpiredException;
use UserAuthentication\Exception\UserAlreadyActiveException;
use UserAuthentication\Exception\UserNotFoundException;
use Cake\Core\Configure;
use Cake\Network\Response;
use Exception;

trait UserValidationTrait {

    /**
     * Validates email
     *
     * @param string $type 'email' or 'password' to validate the user
     * @param string $token token
     * @return Response
     * 
     *@since 1.0.0
     *@author Krutarth Patel <krutarth@sourcefragment.com>
     */
    public function validate($type = NULL, $token = NULL) {
        try {
            switch ($type) {
                case 'email' :
                    try {
                        $result = $this->getUsersTable()->validate($token, 'activeUser');
                        if ($result) {
                            $this->Flash->success(__d('UserAuthentication', 'User account validated successfully'));
                        } else {
                            $this->Flash->error(__d('UserAuthentication', 'User Account could not be validated'));
                        }
                    } catch (UserAlreadyActiveException $ex) {
                        $this->Flash->error(__d('UserAuthentication', 'User already active'));
                    }
                    break;
                case 'password' :
                    $result = $this->getUserTable()->validate($token);
                    if (!empty($result)) {
                        $this->Flash->success(__d('UserAuthentication', 'Reset password token was validated successfully'));
                        $this->request->session()->write(
                                Configure::read('User.Key.Session.resetPasswordUserId')
                        );
                        return $this->redirect(['action' => 'changePassword']);
                    } else {
                        $this->Flash->error(__d('UserAuthentication', 'Reset password token could not be validated'));
                    }
                    break;
                default :
                    $this->Flash->error(__d('UserAuthentication', 'Invalid validation type'));
            }
        } catch (UserNotFoundException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'Invalid token or user account already validated'));
        } catch (TokenExpiredException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'Token already expired'));
        }

        return $this->redirect(['action' => 'login']);
    }
    
    /**
     * Resend Token Validation
     * 
     * @return mixed
     * 
     * @since 1.0.0
     * @author krutarth Patel<krutarth@sourcefragment.com>
     */

    public function resendTokenValidation() {
        $this->set('user', $this->getUserTabel()->newEntity());
        $this->set('_serialize', ['user']);
        if (!$this->request->is('post')) {
            return;
        }
        $reference = $this->request->getData('reference');
        try {
            if ($this->getUserTable()->resetToken($reference, [
                        'expiration' => Configure::read('Users.Token.expiration'),
                        'checkActive' => TRUE,
                        'sendEmail' => TRUE,
                        'emailTemplate' => 'UserAuthentication.validation'
                    ])) {
                $this->Flash->success(__d('UserAuthentication', 'Token has been resset successfully. Please check you email'));
            } else {
                $this->Flash->error(__d('UserAuthentication', 'Token could not be reset'));
            }
            return $this->redirect(['action' => 'login']);
        } catch (UserNotFoundException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'User {0} was not found', $reference));
        } catch (UserAlreadyActiveException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'User {0} is already active'));
        } catch (Exception $ex) {
            $this->Flash->error(__d('UserAuthentication', 'Token could not be reset'));
        }
    }

}
