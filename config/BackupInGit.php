<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tabla central
    |--------------------------------------------------------------------------
    |
    | Este valor definr el nombre de la tabla la cual contiene todos los accesos
    | a las demas bases de datos.
    |
    */

    'table_center' => 'clients',

    /*
    |--------------------------------------------------------------------------
    | Columna de Servidor
    |--------------------------------------------------------------------------
    |
    | Este valor define la columna corresponde al servidor dentro
    | de la tabla central (table_center)
    |
    */

    'col_server' => 'server',

    /*
    |--------------------------------------------------------------------------
    | Columna de base datos
    |--------------------------------------------------------------------------
    |
    | Este valor define la columna corresponde al nombre de la base de datos
    | dentro de la tabla central (table_center)
    |
    */

    'col_db' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Columna de Contraseña
    |--------------------------------------------------------------------------
    |
    | Este valor define la columna corresponde a la contraseña dentro de la
    | tabla central (table_center)
    |
    */

    'col_pwd' => 'password',

    /*
    |--------------------------------------------------------------------------
    | Estatus activo de fila
    |--------------------------------------------------------------------------
    |
    | Este valor define la columna corresponde al status de la fila, ademas
	| de definir el valor activo de la columna, esto con el objetivo de no
	| realizar respados a las bases eliminadas o inactivas.
	|
	| Si al valor status.status_enable esta en false, se da por echo que
	| todas las filas son activas y respaldara todo.
    |
    */

    'status' => [
		'status_enable' => true, // true o false
		'col_status' => 'status', // string del nombre de columna
		'value_active' => 1, // Valor que define estado activo
	],

];
