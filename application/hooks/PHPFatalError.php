<?php

class PHPFatalError {
    public function setHandler() {
        register_shutdown_function('handleShutdown');
    }
}

function handleShutdown() {
    if (($error = error_get_last())) {

        $error = print_r($error, true);

        $server = print_r($_SERVER, true);
        $post = print_r($_POST, true);
        $get = print_r($_GET, true);
        $request = print_r($_REQUEST, true);
        $msg =
"Error: {$error}

_POST: {$post}
_GET: {$get}
_REQUEST: {$request}
_SERVER: {$server}";

        mail('admin@giverhub.com', 'fatal-error @' . @$_SERVER['SERVER_NAME'], $msg);
    }
}