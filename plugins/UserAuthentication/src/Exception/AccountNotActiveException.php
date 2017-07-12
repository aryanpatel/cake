<?php
/**
 * AccountNotActiveException
 * 
 * @package UserAuthentication/Exception
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Core\Exception\Exception;

class AccountNotActiveException extends Exception {
    
    protected $_messageTemplate = '/a/validate/%s/%s';
    /**
     * AccountNotActiveException Constructor
     * 
     * @param array|string $message Message
     * @param int $code Error code
     * @param null $previous Previous Error
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
}