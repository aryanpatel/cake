<?php

/**
 * UserAuth Component
 * 
 * @package UserAuthentication/Controller/Component
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Controller\Component;

use UserAuthentication\Exception\BadConfigurationException;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\Routing\Exception\MissingRouteException;
use Cake\Routing\Router;
use Cake\Utility\Hash;

class UserAuthComponent extends Component {
    
    const EVENT_IS_AUTHORIZED = 'Users.Component.UsersAuth.isAuthorized';
    const EVENT_BEFORE_LOGIN = 'Users.Component.UsersAuth.beforeLogin';
    const EVENT_AFTER_LOGIN = 'Users.Component.UsersAuth.afterLogin';
    const EVENT_FAILED_SOCIAL_LOGIN = 'Users.Component.UsersAuth.failedSocialLogin';
    const EVENT_AFTER_COOKIE_LOGIN = 'Users.Component.UsersAuth.afterCookieLogin';
    const EVENT_BEFORE_REGISTER = 'Users.Component.UsersAuth.beforeRegister';
    const EVENT_AFTER_REGISTER = 'Users.Component.UsersAuth.afterRegister';
    const EVENT_BEFORE_LOGOUT = 'Users.Component.UsersAuth.beforeLogout';
    const EVENT_AFTER_LOGOUT = 'Users.Component.UsersAuth.afterLogout';
    const EVENT_BEFORE_SOCIAL_LOGIN_USER_CREATE = 'Users.Component.UsersAuth.beforeSocialLoginUserCreate';
    
    public $components = array();
    
    /**
     * Initialize method, setup Auth if not already done passing the $config provided and
     * setup the default table to Users.Users if not provided
     *
     * @param array $config config options
     * @return void
     */
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->_initAuth();
        if(Configure::read('Users.RememberMe.active')) {
            $this->_loadRememberMe();
        }
        $this->_attachPermissionChecker();
    }

    public function startup($controller) {
        
    }

    public function beforeRender($controller) {
        
    }

    public function shutDown($controller) {
        
    }

    public function beforeRedirect($controller, $url, $status = null, $exit = true) {
        
    }
    
    /**
     * Load RememberMe component and Auth objects
     *
     * @return void
     */
    protected function _loadRememberMe()
    {
        $this->getController()->loadComponent('UserAuthentication.RememberMe');
    }
    
    /**
     * Attach the isUrlAuthorized event to allow using the Auth authorize from the UserHelper
     *
     * @return void
     */
    protected function _attachPermissionChecker()
    {
        EventManager::instance()->on(self::EVENT_IS_AUTHORIZED, [], [$this, 'isUrlAuthorized']);
    }
    
    protected function _initAuth(){
        if(Configure::read('Users.Auth')) {
            // Initialize Auth Component
            $this->getController()->loadComponent('Auth', Configure::read('Auth'));
        }
        
        list($plugin,$controller) = pluginSplit(Configure::read('Users.Controller'));
        if( $this->getController()->request->getParam('plugin') === $plugin &&
            $this->getController()->request->getParam('controller') === $controller
        ) {
            $this->getController()->Auth->allow([
                'login',
                'verify',
                'register',
                'validateEmail',
                'changePassword',
                'resetPassword',
                'requestResetPassword',
                'resendTokenValidation'
            ]);
        }
    }
    
    /**
     * Check if a given url is authorized
     *
     * @param Event $event event
     *
     * @return bool
     */
    public function isUrlAuthorized(Event $event)
    {
        $url = Hash::get((array)$event->data, 'url');
        if (empty($url)) {
            return false;
        }

        if (is_array($url)) {
            $requestUrl = Router::normalize(Router::reverse($url));
            $requestParams = Router::parseRequest(new ServerRequest($requestUrl));
        } else {
            try {
                //remove base from $url if exists
                $normalizedUrl = Router::normalize($url);
                $requestParams = Router::parseRequest(new ServerRequest($normalizedUrl));
            } catch (MissingRouteException $ex) {
                //if it's a url pointing to our own app
                if (substr($normalizedUrl, 0, 1) === '/') {
                    throw $ex;
                }

                return true;
            }
            $requestUrl = $url;
        }
        // check if controller action is allowed
        if ($this->_isActionAllowed($requestParams)) {
            return true;
        }

        // check we are logged in
        $user = $this->getController()->Auth->user();
        if (empty($user)) {
            return false;
        }

        $request = new ServerRequest($requestUrl);
        $request = $request->addParams($requestParams);

        $isAuthorized = $this->getController()->Auth->isAuthorized(null, $request);

        return $isAuthorized;
    }
    
    /**
     * Validate if the passed configuration makes sense
     *
     * @throws BadConfigurationException
     * @return void
     */
    protected function _validateConfig()
    {
        if (!Configure::read('Users.Email.required') && Configure::read('Users.Email.validate')) {
            $message = __d('UserAuthentication', 'You can\'t enable email validation workflow if use_email is false');
            throw new BadConfigurationException($message);
        }
    }
    
    /**
     * Check if the action is in allowedActions array for the controller
     * Important, this function will check only for allowed actions in the current
     * controller, creating a new instance and providing initialization for the Auth
     * instance in another controller could lead to undesired side effects.
     *
     * @param array $requestParams request parameters
     * @return bool
     */
    protected function _isActionAllowed($requestParams = [])
    {
        if (empty($requestParams['action'])) {
            return false;
        }
        if (!empty($requestParams['controller']) && $requestParams['controller'] !== $this->getController()->name) {
            return false;
        }
        $action = strtolower($requestParams['action']);
        if (in_array($action, array_map('strtolower', $this->getController()->Auth->allowedActions))) {
            return true;
        }

        return false;
    }

}
