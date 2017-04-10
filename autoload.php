<?php

function my_autoloader($class) {
    $class = str_replace('\\','/',$class);

    include $class . '.class.php';
}

spl_autoload_register('my_autoloader');
