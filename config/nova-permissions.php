<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User model class
    |--------------------------------------------------------------------------
    */

    'user_model' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Nova User resource tool class
    |--------------------------------------------------------------------------
    */

    'user_resource' => 'App\Nova\User',

    /*
    |--------------------------------------------------------------------------
    | The group associated with the resource
    |--------------------------------------------------------------------------
    */

    'role_resource_group' => 'Other',

    /*
    |--------------------------------------------------------------------------
    | Database table names
    |--------------------------------------------------------------------------
    | When using the "HasRoles" trait from this package, we need to know which
    | table should be used to retrieve your roles. We have chosen a basic
    | default value but you may easily change it to any table you like.
    */

    'table_names' => [
        'roles' => 'roles',

        'role_permission' => 'role_permission',

        'role_user' => 'role_user',
        
        'users' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        // ---------------------------------------------- House
        'view all houses' => [
            'display_name' => 'Ver todas las viviendas',
            'description'  => 'Ver todas las viviendas',
            'group'        => 'Viviendas',
        ],

        'view community houses' => [
            'display_name' => 'Ver viviendas Comunidad',
            'description'  => 'Ver viviendas Comunidad',
            'group'        => 'Viviendas',
        ],

        'view province houses' => [
            'display_name' => 'Ver viviendas Provincia',
            'description'  => 'Ver viviendas Provincia',
            'group'        => 'Viviendas',
        ],

        'view own houses' => [
            'display_name' => 'Ver viviendas propias',
            'description'  => 'Ver viviendas propias',
            'group'        => 'Viviendas',
        ],

        'create houses' => [
            'display_name' => 'Crear viviendas',
            'description'  => 'Puede crear viviendas',
            'group'        => 'Viviendas',
        ],

         'create own houses' => [
            'display_name' => 'Crear viviendas propias',
            'description'  => 'Puede crear viviendas propias',
            'group'        => 'Viviendas',
        ],

        'edit houses' => [
            'display_name' => 'Editar vivinedas',
           'description'  => 'Puede editar viviendas',
            'group'        => 'Viviendas',
        ],

        'edit own houses' => [
            'display_name' => 'Editar vivinedas propias',
           'description'  => 'Puede editar viviendas propias',
            'group'        => 'Viviendas',
        ],

        'delete houses' => [
            'display_name' => 'Borrar viviendas',
            'description'  => 'Puede borrar viviendas',
            'group'        => 'Viviendas',
        ],

        'delete own houses' => [
            'display_name' => 'Borrar viviendas propias',
            'description'  => 'Puede borrar viviendas propias',
            'group'        => 'Viviendas',
        ],

        'restore houses' => [
            'display_name' => 'Restaurar viviendas',
            'description'  => 'Puede restaurar viviendas',
            'group'        => 'Viviendas',
        ],

        'restore own houses' => [
            'display_name' => 'Restaurar viviendas propias',
            'description'  => 'Puede restaurar viviendas propias',
            'group'        => 'Viviendas',
        ],

        // ---------------------------------------------- Business

        'view all businesses' => [
            'display_name' => 'Ver todos los negocios',
            'description'  => 'Ver todos los negocios',
            'group'        => 'Negocios',
        ],

        'view community businesses' => [
            'display_name' => 'Ver negocios Comunidad',
            'description'  => 'Ver negocios Comunidad',
            'group'        => 'Negocios',
        ],

        'view province businesses' => [
            'display_name' => 'Ver negocios Provincia',
            'description'  => 'Ver negocios Provincia',
            'group'        => 'Negocios',
        ],

        'view own businesses' => [
            'display_name' => 'Ver negocios propios',
            'description'  => 'Ver negocios propios',
            'group'        => 'Negocios',
        ],

        'create businesses' => [
            'display_name' => 'Crear negocios',
            'description'  => 'Puede crear negocios',
            'group'        => 'Negocios',
        ],

         'create own businesses' => [
            'display_name' => 'Crear negocios propios',
            'description'  => 'Puede crear negocios propios',
            'group'        => 'Negocios',
        ],

        'edit businesses' => [
            'display_name' => 'Editar negocios',
           'description'  => 'Puede editar negocios',
            'group'        => 'Negocios',
        ],

        'edit own businesses' => [
            'display_name' => 'Editar negocios propios',
           'description'  => 'Puede editar negocios propios',
            'group'        => 'Negocios',
        ],

        'delete businesses' => [
            'display_name' => 'Borrar negocios',
            'description'  => 'Puede borrar negocios',
            'group'        => 'Negocios',
        ],

        'delete own businesses' => [
            'display_name' => 'Borrar negocios propios',
            'description'  => 'Puede borrar negocios propios',
            'group'        => 'Negocios',
        ],

        'restore businesses' => [
            'display_name' => 'Restaurar negocios',
            'description'  => 'Puede restaurar negocios',
            'group'        => 'Negocios',
        ],

        'restore own businesses' => [
            'display_name' => 'Restaurar negocios propios',
            'description'  => 'Puede restaurar negocios propios',
            'group'        => 'Negocios',
        ],

        //---------------------------------------------------- Lands

        'view all lands' => [
            'display_name' => 'Ver todas las tierras',
            'description'  => 'Ver todas las tierras',
            'group'        => 'Tierras',
        ],

        'view community lands' => [
            'display_name' => 'Ver tierras Comunidad',
            'description'  => 'Ver tierras Comunidad',
            'group'        => 'Tierras',
        ],

        'view province lands' => [
            'display_name' => 'Ver tierras Provincia',
            'description'  => 'Ver tierras Provincia',
            'group'        => 'Tierras',
        ],

        'view own lands' => [
            'display_name' => 'Ver tierras propias',
            'description'  => 'Ver tierras propias',
            'group'        => 'Tierras',
        ],

        'create lands' => [
            'display_name' => 'Crear tierras',
            'description'  => 'Puede crear tierras',
            'group'        => 'Tierras',
        ],

         'create own lands' => [
            'display_name' => 'Crear tierras propias',
            'description'  => 'Puede crear tierras propias',
            'group'        => 'Tierras',
        ],

        'edit lands' => [
            'display_name' => 'Editar tierras',
           'description'  => 'Puede editar tierras',
            'group'        => 'Tierras',
        ],

        'edit own lands' => [
            'display_name' => 'Editar tierras propias',
           'description'  => 'Puede editar tierras propias',
            'group'        => 'Tierras',
        ],

        'delete lands' => [
            'display_name' => 'Borrar tierras',
            'description'  => 'Puede borrar tierras',
            'group'        => 'Tierras',
        ],

        'delete own lands' => [
            'display_name' => 'Borrar tierras propias',
            'description'  => 'Puede borrar tierras propias',
            'group'        => 'Tierras',
        ],

        'restore lands' => [
            'display_name' => 'Restaurar tierras',
            'description'  => 'Puede restaurar tierras',
            'group'        => 'Tierras',
        ],

        'restore own lands' => [
            'display_name' => 'Restaurar tierras propias',
            'description'  => 'Puede restaurar tierras propias',
            'group'        => 'Tierras',
        ],

        // ---------------------------------------------- Jobs

        'view all jobs' => [
            'display_name' => 'Ver todos los trabajos',
            'description'  => 'Ver todos los trabajos',
            'group'        => 'Trabajos',
        ],

        'view community jobs' => [
            'display_name' => 'Ver trabajos Comunidad',
            'description'  => 'Ver trabajos Comunidad',
            'group'        => 'Trabajos',
        ],

        'view province jobs' => [
            'display_name' => 'Ver trabajos Provincia',
            'description'  => 'Ver trabajos Provincia',
            'group'        => 'Trabajos',
        ],

        'view own jobs' => [
            'display_name' => 'Ver trabajos propios',
            'description'  => 'Ver trabajos propios',
            'group'        => 'Trabajos',
        ],

        'create jobs' => [
            'display_name' => 'Crear trabajos',
            'description'  => 'Puede crear trabajos',
            'group'        => 'Trabajos',
        ],

         'create own jobs' => [
            'display_name' => 'Crear trabajos propios',
            'description'  => 'Puede crear trabajos propios',
            'group'        => 'Trabajos',
        ],

        'edit jobs' => [
            'display_name' => 'Editar trabajos',
           'description'  => 'Puede editar trabajos',
            'group'        => 'Trabajos',
        ],

        'edit own jobs' => [
            'display_name' => 'Editar trabajos propios',
           'description'  => 'Puede editar trabajos propios',
            'group'        => 'Trabajos',
        ],

        'delete jobs' => [
            'display_name' => 'Borrar trabajos',
            'description'  => 'Puede borrar trabajos',
            'group'        => 'Trabajos',
        ],

        'delete own jobs' => [
            'display_name' => 'Borrar trabajos propios',
            'description'  => 'Puede borrar trabajos propios',
            'group'        => 'Trabajos',
        ],

        'restore jobs' => [
            'display_name' => 'Restaurar trabajos',
            'description'  => 'Puede restaurar trabajos',
            'group'        => 'Trabajos',
        ],

        'restore own jobs' => [
            'display_name' => 'Restaurar trabajos propios',
            'description'  => 'Puede restaurar trabajos propios',
            'group'        => 'Trabajos',
        ],

        //----------------------------------------------------Demandants

        'view all demandants' => [
            'display_name' => 'Ver todos los solicitantes',
            'description'  => 'Ver todos los solicitantes',
            'group'        => 'Solicitantes',
        ],

        'view community demandants' => [
            'display_name' => 'Ver solicitantes Comunidad',
            'description'  => 'Ver solicitantes Comunidad',
            'group'        => 'Solicitantes',
        ],

        'view province demandants' => [
            'display_name' => 'Ver solicitantes Provincia',
            'description'  => 'Ver solicitantes Provincia',
            'group'        => 'Solicitantes',
        ],

        'create demandants' => [
            'display_name' => 'Crear solicitantes',
            'description'  => 'Puede crear solicitantes',
            'group'        => 'Solicitantes',
        ],

        'edit demandants' => [
            'display_name' => 'Editar solicitantes',
           'description'  => 'Puede editar solicitantes',
            'group'        => 'Solicitantes',
        ],

        'delete demandants' => [
            'display_name' => 'Borrar solicitantes',
            'description'  => 'Puede borrar solicitantes',
            'group'        => 'Solicitantes',
        ],

        'restore demandants' => [
            'display_name' => 'Restaurar solicitantes',
            'description'  => 'Puede restaurar solicitantes',
            'group'        => 'Solicitantes',
        ],

        //---------------------------------------------------- DemandantFollowUps

        'view all demandantfollowups' => [
            'display_name' => 'Ver todas las interacciones',
            'description'  => 'Ver todas las interacciones',
            'group'        => 'Interacciones',
        ],

        'view own demandantfollowups' => [
            'display_name' => 'Ver interacciones propias',
            'description'  => 'Ver interacciones propias',
            'group'        => 'Interacciones',
        ],

        'create demandantfollowups' => [
            'display_name' => 'Crear interacciones',
            'description'  => 'Puede crear interacciones',
            'group'        => 'Interacciones',
        ],

         'create own demandantfollowups' => [
            'display_name' => 'Crear interacciones propias',
            'description'  => 'Puede crear interacciones propias',
            'group'        => 'Interacciones',
        ],

        'edit demandantfollowups' => [
            'display_name' => 'Editar interacciones',
           'description'  => 'Puede editar interacciones',
            'group'        => 'Interacciones',
        ],

        'edit own demandantfollowups' => [
            'display_name' => 'Editar interacciones propias',
           'description'  => 'Puede editar interacciones propias',
            'group'        => 'Interacciones',
        ],

        'delete demandantfollowups' => [
            'display_name' => 'Borrar interacciones',
            'description'  => 'Puede borrar interacciones',
            'group'        => 'Interacciones',
        ],

        'delete own demandantfollowups' => [
            'display_name' => 'Borrar interacciones propias',
            'description'  => 'Puede borrar interacciones propias',
            'group'        => 'Interacciones',
        ],

        'restore demandantfollowups' => [
            'display_name' => 'Restaurar interacciones',
            'description'  => 'Puede restaurar interacciones',
            'group'        => 'Interacciones',
        ],

        'restore own demandantfollowups' => [
            'display_name' => 'Restaurar interacciones propias',
            'description'  => 'Puede restaurar interacciones propias',
            'group'        => 'Interacciones',
        ],


        //----------------------------------------------------

        'view users' => [
            'display_name' => 'View users',
            'description'  => 'Can view users',
            'group'        => 'User',
        ],

        'create users' => [
            'display_name' => 'Create users',
            'description'  => 'Can create users',
            'group'        => 'User',
        ],

        'edit users' => [
            'display_name' => 'Edit users',
            'description'  => 'Can edit users',
            'group'        => 'User',
        ],

        'delete users' => [
            'display_name' => 'Delete users',
            'description'  => 'Can delete users',
            'group'        => 'User',
        ],

        'view roles' => [
            'display_name' => 'View roles',
            'description'  => 'Can view roles',
            'group'        => 'Role',
        ],

        'create roles' => [
            'display_name' => 'Create roles',
            'description'  => 'Can create roles',
            'group'        => 'Role',
        ],

        'edit roles' => [
            'display_name' => 'Edit roles',
            'description'  => 'Can edit roles',
            'group'        => 'Role',
        ],

        'delete roles' => [
            'display_name' => 'Delete roles',
            'description'  => 'Can delete roles',
            'group'        => 'Role',
        ],
    ],
];
