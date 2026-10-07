<?php

require dirname(__DIR__) . '/vendor/autoload.php';

register_shutdown_function(function () {
    restore_error_handler();
    restore_exception_handler();
});