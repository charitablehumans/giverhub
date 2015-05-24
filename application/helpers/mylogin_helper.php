<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('generaterandomkey')) {

    function generaterandomkey($uniquekeys, $process = '') {
        $currentuniquekeys = array();
        foreach ($uniquekeys as $Ukey) {
            if ($process == 'retr') {
                $currentuniquekeys[] = $Ukey->uniquekey;
            } else {
                $currentuniquekeys[] = $Ukey->activation_key;
            }
        }
        $a = str_shuffle('abcdefghijklmabcdefghijklmnopqrstuvwxyz1234567890nopqrstuvwxyz1234567890abcdefghijklmnopqrstuvwxyz1234567890');

        $temprand = substr($a, 0, 100);
        while (in_array($temprand, $currentuniquekeys)) {
            $temprand = substr($a, 0, 100);
        }
        $readyuniquekey = $temprand;
        return $readyuniquekey;
    }

}

if (!function_exists('emailsending')) 
{
    function emailsending($from, $to, $subject, $body, $companyname, $email_type = 0) {
        $CI = & get_instance();
        $CI->load->library('email');
        $CI->email->set_newline("\r\n");
        if ($email_type == 1) {
            $CI->email->set_mailtype("html");
        }

        $CI->email->from($from, $companyname);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($body);
        //$CI->email->send();

        if ($CI->email->send()) 
        {
            return TRUE;
        } 
        else 
        {
            return show_error($CI->email->print_debugger());
        }
    }
}

