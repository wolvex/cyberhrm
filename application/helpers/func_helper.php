<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('startsWith')) {
    
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (! function_exists('endsWith')) {
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 || 
        (substr($haystack, -$length) === $needle);
    }
}

if (! function_exists('trimArray')) {
    function trimArray($array)
    {
        foreach ($array as &$value) {
            $value = trim($value);
        }
        unset($value);
        return $array;
    }
}

if (! function_exists('array_nvl')) {
    
    function array_nvl($arr, $key, $default) {
        if (isset($arr[$key]) && $arr[$key] !== '') {
            return $arr[$key];
        } else {
            return $default;
        }
    }
}

if (! function_exists('json_response')) {
    
    function json_response($status, $message, $data = array()) {
        $data['status'] = $status;
        $data['error'] = $message;
        return json_encode($data);
    }
}

if (! function_exists('json_forbidden')) {
    
    function json_forbidden() {
        exit(json_encode(array(
            'status' => false,
            'error' => 'Maaf, Anda tidak diperkenankan untuk melakukan operasi ini'
        )));
    }
}

if (! function_exists('json_notfound')) {
    
    function json_notfound() {
        exit(json_encode(array(
            'status' => false,
            'error' => 'Maaf, data tidak ditemukan'
        )));
    }
}

if (! function_exists('json_error')) {
    
    function json_error($message) {
        exit(json_encode(array(
            'status' => false,
            'error' => $message
        )));
    }
}

if (! function_exists('json_success')) {
    
    function json_success($payload = array(), $message = 'Sukses') {
        if (count($payload) > 0) {
            exit(json_encode(array(
                'status' => true,
                'error' => $message,
                'payload' => $payload
            )));
        } else {
            exit(json_encode(array(
                'status' => true,
                'error' => $message
            )));
        }
    }
}

if (! function_exists('sum_array')) {
    
    function sum_array($arr, $key, $amount) {
        if (!isset($arr[$key])) $arr[$key] = 0;
        $arr[$key] = $arr[$key] + $amount;
        return $arr;
    }
}