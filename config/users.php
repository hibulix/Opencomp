<?php

return ['Users' => [
    //Table used to manage users
    'table' => 'Users',
    //configure Auth component
    'auth' => true,
    'Email' => [
        //determines if the user should include email
        'required' => true,
        //determines if registration workflow includes email validation
        'validate' => true,
    ],
    'Registration' => [
        //determines if the register is enabled
        'active' => true,
        //ensure user is active (confirmed email) to reset his password
        'ensureActive' => false
    ],
    'Tos' => [
        //determines if the user should include tos accepted
        'required' => true,
    ],
    'Social' => [
        //enable social login
        'login' => false,
    ],
    //Avatar placeholder
    'Avatar' => ['placeholder' => 'CakeDC/Users.avatar_placeholder.png'],
    'RememberMe' => [
        //configure Remember Me component
        'active' => true,
    ],
],
//default configuration used to auto-load the Auth Component, override to change the way Auth works
    'Auth' => [
        'authenticate' => [
            'all' => [
                'finder' => 'active',
            ],
            'CakeDC/Users.RememberMe',
            'Form'
        ],
        'authorize' => [
            'CakeDC/Users.Superuser',
            'CakeDC/Users.SimpleRbac',
        ],
    ],
];
