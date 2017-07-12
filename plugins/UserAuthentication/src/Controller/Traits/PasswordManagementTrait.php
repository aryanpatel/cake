<?php

/**
 * PasswordManagementTrait
 * 
 * @package UserAuthentication/Controller/Trait
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Controller\Traits;

use UserAuthentication\Exception\UserNotActiveException;
use UserAuthentication\Exception\UserNotFoundException;
use UserAuthentication\Exception\WrongPasswordException;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Exception;

/**
 * Covers the password management: reset, change
 *
 * @property \Cake\Http\ServerRequest $request
 */
trait PasswordManagementTrait {

    use UserValidationTrait;

    /**
     * Change password
     *
     * @return mixed
     */
    public function changePassword() {
        $user = $this->getUserTable()->newEntity();
        $id = $this->Auth->user('id');
        if (!empty($id)) {
            $user->id = $this->Auth->user('id');
            $validatePassword = TRUE;
            $redirect = Configure::read('Users.Profile.route');
        } else {
            $user->id = $this->request->session()->read(Configure::read('Users.Key.Sesssion.resetPasswordUserId'));
            $validatePassword = FALSE;
            if (!$user->id) {
                $this->Flash->error(__d('UserAuthentication', 'User was not found'));
                $this->redirect($this->Auth->config('loginAction'));

                return;
            }
            $this->redirect($this->Auth->config('loginAction'));
        }
        $this->set('validatePassword', $validatePassword);
        if ($this->request->is('post')) {
            try {
                $validator = $this->getUserTable()->validatePasswordConfirm(new Validator());
                if (!empty($id)) {
                    $validator = $this->getUserTable()->validateCurrentPassword($validator);
                }
                $user = $this->getUserTable()->patchEntity(
                        $user, $this->request->getData(), ['validate' => $validator]
                );
                if ($user->error) {
                    $this->Flash->error(__d('UserAuthentication', 'Password could not changed'));
                } else {
                    $user = $this->getUserTable()->chagePassword($user);
                    if ($user) {
                        $this->Flash->success(__d('UserAuthenticaton', 'Password has been changed successfully'));
                        return $this->redirect($redirect);
                    } else {
                        $this->Flash->error(__d('UserAuthentication', 'Password could not changed'));
                    }
                }
            } catch (UserNotFoundException $ex) {
                $this->Flash->error(__d('UserAuthentication', 'User was not found'));
            } catch (WrongPasswordException $ex) {
                $this->Flash->error(__d('UserAuthentication', '{0}', $ex->getMessage()));
            } catch (Exception $ex) {
                $this->Flash->error(__d('UserAuthenticatoin', 'Password could not be changed'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Reset password
     * 
     * @param null $token token data
     * @return void
     */
    public function resetPassword($token = NULL) {
        $this->validate('password', $token);
    }

    /**
     * Reset password
     *
     * @return void|\Cake\Network\Response
     */
    public function requestResetPassword() {
        $this->set('user', $this->getUserTable->newEntity());
        $this->set('_serialize', ['user']);
        if (!$this->request->is('post')) {
            return;
        }
        $reference = $this->request->getData('reference');
        try {
            $resetUser = $this->getUserTable->resetToken($reference, [
                'expiration' => Configure::read('User.Token.expire'),
                'checkActive' => FALSE,
                'sendEmail' => TRUE,
                'ensureActive' => Configure::read('Users.Registration.ensureActive')
            ]);
            if ($resetUsere) {
                $this->Flash->success(__d('UserAuthentication', 'Please check your email to continue with password reset process'));
            } else {
                $this->Flash->error(__d('UserAuthentication', 'Password reset token could not be generated. Please try again'));
            }
            return $this->redirect(['action' => 'login']);
        } catch (UserNotFoundException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'User {0} was not found', $reference));
        } catch (UserNotActiveException $ex) {
            $this->Flash->error(__d('UserAuthentication', 'User is not active'));
        } catch (Exception $ex) {
            $this->Flash->error(__d('UserAuthentication', 'Token could not be reset'));
        }
    }

}
