<?php

use mavoc\console\Route;

Route::command('example', ['ConsoleController', 'example']);
Route::command('view', ['ConsoleController', 'view']);




// MOVE
//Route::command('mig down', ['ConsoleMigController', 'down']);
//Route::command('mig init', ['ConsoleMigController', 'init']);
//Route::command('mig new', ['ConsoleMigController', 'new']);
//Route::command('mig up', ['ConsoleMigController', 'up']);



