<?php

return [

    'welcome' => 'Welkom bij :app_name',
    'welcome_name' => 'Welkom bij :app_name, :name'.'!',
    'welcome_line_1' => 'Je kunt nu inloggen met de volgende gegevens:',
    'welcome_email' => 'E-mailadres: :email',
    'welcome_password' => 'Wachtwoord: :password',
    'welcome_action' => 'Log nu in op :app_name',
    'file' => [
        'mimes' => 'Het bestand moet van het type .xls of .xlsx zijn',
        'max' => 'De bestandsgrootte moet kleiner zijn dan 2 MB'
    ],
    'errors' => [
        'name' => [
            'required' => 'De naam van de gebruiker is verplicht.'
        ],
        'email' => [
            'required' => 'Het e-mailadres is verplicht.',
            'email' => 'Dit e-mailadres heeft niet de juiste opmaak: ":email"',
            'unique' => 'Er bestaat al een gebruiker met dit e-mailadres: :email'
        ],
        'group' => [
            'exists' => 'Er bestaat geen gebruikersgroep met de naam: :name'
        ]
    ]
];
