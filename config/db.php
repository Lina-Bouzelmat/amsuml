<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=etd',
    'username' => 'uapv2202351',
    'password' => 'vCnUzE',
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql' => [
            'class' => 'yii\db\pgsql\Schema',
            'defaultSchema' => 'fredouil', // Spécifie le schéma par défaut 'fredouil'
            ]
    ],
];
