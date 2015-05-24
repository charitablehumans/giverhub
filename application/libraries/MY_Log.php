<?php
class MY_Log extends CI_Log {

    public function __construct() {
        parent::__construct();
    }

    public function write_log($level = 'error', $msg, $php_error = FALSE) {

        $result = parent::write_log($level, $msg, $php_error);

        if ($result == TRUE && strtoupper($level) == 'ERROR') {

            $server = print_r($_SERVER, true);
            $post = print_r($_POST, true);
            $get = print_r($_GET, true);
            $request = print_r($_REQUEST, true);
            $msg =
"level: {$level}
msg: {$msg}
php_error: {$php_error}

_POST: {$post}
_GET: {$get}
_REQUEST: {$request}
_SERVER: {$server}";

            mail('admin@giverhub.com', 'ci-error @' . @$_SERVER['SERVER_NAME'], $msg);
        }

        return $result;
    }

}