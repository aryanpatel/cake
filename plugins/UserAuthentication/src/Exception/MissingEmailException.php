<?php
/**
 * MissingEmailException
 * 
 * @package UserAuthentication/Exception
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Core\Exception\Exception;

class MissingEmailException extends Exception {
    /**
     * MissingEmailException Constructor
     * 
     * @param array|string $message Message
     * @param int $code Error code
     * @param null $previous Previous Error
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
}