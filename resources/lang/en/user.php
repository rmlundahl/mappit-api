<?php

return [

    'welcome' => 'Welcome to :app_name',
    'welcome_name' => 'Welcome to :app_name, :name'.'!',
    'welcome_line_1' => 'You can now log in with the following details:',
    'welcome_email' => 'Email address: :email',
    'welcome_password' => 'Password: :password',
    'welcome_action' => 'Login to :app_name now',
    'file' => [
        'mimes' => 'The file must be of type .xls or .xlsx',
        'max' => 'The file size must be smaller than 2 MB'
    ],
    'errors' => [
        'name' => [
            'required' => 'The user name is mandatory.'
        ],
        'email' => [
            'required' => 'The e-mail address is mandatory.',
            'email' => 'This e-mail address does not have the correct format: ":email"',
            'unique' => 'A user already exists with this email address: :email'
        ],
        'group' => [
            'exists' => 'No user group exists with the name: :name'
        ]
    ]
];
