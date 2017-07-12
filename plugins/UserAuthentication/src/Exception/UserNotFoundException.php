<?php
/**
 * UserNotFoundException
 * 
 * @package UserAuthentication/Exception
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */

namespace UserAuthentication\Exception;

use Cake\Datasource\Exception\RecordNotFoundException;

class UserNotFoundException extends RecordNotFoundException{
    
    /**
     * UserNotFoundException Constructor
     * 
     * @param message $message Error Message
     * @param code $code Error code
     * @param null $previous previous
     */
    
    public function __construct($message, $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}