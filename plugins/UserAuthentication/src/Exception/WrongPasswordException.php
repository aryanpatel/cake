<?php

/**
 * WrongPasswordException
 * 
 * @package UserAuthentication\Controller\Exception
 * @since 1.0.0
 * @author Krutarth Patel <krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Core\Exception\Exception;

class WrongPasswordException extends Exception {
    
    /**
     * WrongPassworException constructor
     * 
     * @param array|string $message Error Message
     * @param int $code Error Code
     * @param null $previous previous
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}