<?php

/**
 * RememberMe Component
 * 
 * @package UserAuthentication/Controller/Component
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Security;
use InvalidArgumentException;

class RememberMeComponent extends Component {

    public $components = ['Cookie', 'Auth'];

    /**
     * Name of the cookie
     * @var string
     */
    protected $_cookieName = null;

    /**
     * Initialize config data and properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize($controller) {
        parent::initialize($config);
        $this->_cookieName = Configure::read('Users.RememberMe.Cookie.name');
        $this->_validateConfig();
        $this->setCookieOptions();
        $this->_attachEvents();
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
     * Validate component config
     *
     * @throws InvalidArgumentException
     * @return void
     */
    protected function _validateConfig() {
        if (mb_strlen(Security::salt(), '8bit') < 32) {
            throw new InvalidArgumentException(
            __d('UserAuthentication', 'Invalid app salt, app salt must be at least 256 bits (32 bytes) long')
            );
        }
    }

    /**
     * Attach the afterLogin and beforeLogount events
     *
     * @return void
     */
    protected function _attachEvents() {
        $eventManager = $this->getController()->eventManager();
        $eventManager->on(UsersAuthComponent::EVENT_AFTER_LOGIN, [], [$this, 'setLoginCookie']);
        $eventManager->on(UsersAuthComponent::EVENT_BEFORE_LOGOUT, [], [$this, 'destroy']);
    }

    /**
     * Sets cookie configuration options
     *
     * @return void
     */
    public function setCookieOptions() {
        $cookieConfig = Configure::read('Users.RememberMe.Cookie.Config');
        $this->Cookie->configKey($this->_cookieName, $cookieConfig);
    }

    /**
     * Sets the login cookie that handles the remember me feature
     *
     * @param Event $event event
     * @return void
     */
    public function setLoginCookie(Event $event) {
        $user['id'] = $this->Auth->user('id');
        if (empty($user)) {
            return;
        }
        $user['user_agent'] = $this->getController()->request->getHeaderLine('User-Agent');
        $this->Cookie->write($this->_cookieName, $user);
    }

    /**
     * Destroys the remember me cookie
     *
     * @param Event $event event
     * @return void
     */
    public function destroy(Event $event) {
        if ($this->Cookie->check($this->_cookieName)) {
            $this->Cookie->delete($this->_cookieName);
        }
    }

    /**
     * Reads the stored cookie and auto login the user if present
     *
     * @param Event $event event
     * @return mixed
     */
    public function beforeFilter(Event $event) {
        $user = $this->Auth->user();
        if (!empty($user) ||
                $this->getController()->request->is(['post', 'put']) ||
                $this->getController()->request->getParam('action') === 'logout' ||
                $this->getController()->request->session()->check(Configure::read('Users.Key.Session.social')) ||
                $this->getController()->request->getParam('provider')) {
            return;
        }

        $user = $this->Auth->identify();
        // No user no cookies
        if (empty($user)) {
            return;
        }
        $this->Auth->setUser($user);
        $event = $this->getController()->dispatchEvent(UsersAuthComponent::EVENT_AFTER_COOKIE_LOGIN);
        if (is_array($event->result)) {
            return $this->getController()->redirect($event->result);
        }
        $url = $this->getController()->request->getRequestTarget();

        return $this->getController()->redirect($url);
    }

}
