<?php

/**
 * @package UserAuthentication/config
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */
use Cake\Core\Configure;
use Cake\Routing\Router;

$config = [
    'Users' => [
        //Table used to store user details
        'table' => 'UserAuthentication.Users',
        //Controller for user authenticatoin functions
        'controller' => 'UserAuthentication.Users',
        //configure Auth Component
        'auth' => TRUE,
        //Forget password token expirations in seconds
        'Token' => ['expiration' => 3600],
        'Email' => [
            // determines if the user should include email
            'required' => true,
            // determines if registration workflow includes email validation
            'validate' => true,
        ],
        'Registration' => [
            // defines if the new registration is enabled
            'active' => TRUE,
            // defines if the reCaptcha is enabled for registration
            'reCaptcha' => TRUE,
            // allow a logged in user to access the registration form
            'allowLoggedIn' => FALSE,
            //ensure user is active (confirmed email) to reset his password
            'ensureActive' => TRUE,
            // default role name used in registration
            'defaultRole' => 'customer',
        ],
        'Profile' => [
            'route' => ['plugin' => 'UserAuthentication', 'controller' => 'Users', 'action' => 'profile']
        ],
        'Key' => [
            'Session' => [
                // userId key used in reset password workflow
                'resetPasswordUserId' => 'Users.resetPasswordUserId',
            ]
        ],
        // Avatar placeholder
        'Avatar' => ['placeholder' => 'UserAuthentication.avatar_placeholder.png'],
    ],
    // default configuration used to for the Auth Component. Can be override to change the way Authentication works
    'Auth' => [
        'loginAction' => [
            'plugin' => 'UserAuthentication',
            'controller' => 'Users',
            'action' => 'login',
            'prefix' => FALSE
        ],
        'authentication' => [
            'all' => [
                'finder' => 'auth'
            ],
            'Form'
        ]
    ]
];

return $config;
