<?php

/**
 * Token Expired Exception
 * 
 * @package UserAuthentication/Exception
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Core\Exception\Exception;

class UserAlreadyActiveException extends Exception {
    
    /**
     * UserAlreadyActiveException constructor
     * @param array $message Error Message
     * @param int $code Error Code
     * @param null $previous Previous Error
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}