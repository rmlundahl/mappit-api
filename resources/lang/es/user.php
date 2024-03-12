<?php

return [

    'welcome' => 'Bienvenido a :app_name',
    'welcome_name' => '¡Bienvenido a :app_name, :name'.'!',
    'welcome_line_1' => 'Ahora puede iniciar sesión con los siguientes datos:',
    'welcome_email' => 'Dirección de correo electrónico: :email',
    'welcome_password' => 'Contraseña: :password',
    'welcome_action' => 'Conéctese ahora a :app_name',
    'file' => [
        'mimes' => 'El archivo debe ser del tipo .xls o .xlsx',
        'max' => 'El tamaño del archivo debe ser inferior a 2 MB'
    ],
    'errors' => [
        'name' => [
            'required' => 'El nombre del usuario es obligatorio.'
        ],
        'email' => [
            'required' => 'La dirección de correo electrónico es obligatoria.',
            'unique' => 'Ya existe un usuario con esta dirección de correo electrónico: :email'
        ],
        'group' => [
            'exists' => 'No existe ningún grupo de usuarios con el nombre: :name'
        ]
    ]
];
