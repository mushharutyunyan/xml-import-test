<?php
use Server\Autoloader;
require_once '../server/Autoloader.php';
new Autoloader('System', '/../system/');
new Autoloader('App\\Controllers', '/../app/Controllers');
new Autoloader('App\\Repositories', '/../app/Repositories');
new Autoloader('App\\DTO\\Requests', '/../app/DTO/Requests');
new Autoloader('App\\Requests', '/../app/Requests');
new Autoloader('App\\Models', '/../app/Models');
new Autoloader('App\\Errors', '/../app/Errors');
new Autoloader('App\\Responses', '/../app/Responses');
new Autoloader('App\\ValueObjects', '/../app/ValueObjects');
new Autoloader('App\\Services', '/../app/Services');

new Autoloader('Helpers', '/../helpers');

