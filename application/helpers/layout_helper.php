<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('layout')) {
    
    function layout($view, $data) {
        $ci = &get_instance();
        $data['view'] = $view;
        $ci->load->view('layouts/default', $data);
    }

    function layoutReport($view, $data) {
        $ci = &get_instance();
        $data['view'] = $view;
        $ci->load->view('layouts/report', $data);
    }

}