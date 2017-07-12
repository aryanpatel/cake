<?php
/**
 * UserNotActiveException
 * 
 * @package UserAuthentication/Exception
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Core\Exception\Exception;

class UserNotActiveException extends Exception {
    
    /**
     * UserNotActiveException constructor
     * 
     * @param array|string $message Error Message
     * @param int $code Error code
     * @param null $previous previous
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}