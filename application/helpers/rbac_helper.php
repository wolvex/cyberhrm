<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('validateSession')) {
    
    function validateSession() {
        if (!isset($_SESSION['profile'])) {
            header("Location: login");
            exit;
        }
    }
}

if (! function_exists('isAdminMode')) {
    
    function isAdminMode() {
        if (isset($_SESSION['profile'])) {
            return ($_SESSION['profile']['user_role'] == 'ADMIN');
        } else {
            return false;
        }
    }
}

if (! function_exists('getRoleAction')) {
    
    function getRoleAction($controller) {
        $controller = strtolower($controller);
        if (!isset($_SESSION["modules"][$controller])) return "";
        return strtolower($_SESSION["modules"][$controller]);
    }
}

if (! function_exists('isAllowCreate')) {
    function isAllowCreate($controller) {
        $action = getRoleAction($controller);
        return (strpos($action,"c") !== false);
    }
}

if (! function_exists('isAllowUpdate')) {
    function isAllowUpdate($controller) {
        $action = getRoleAction($controller);
        return (strpos($action,"u") !== false);
    }
}

if (! function_exists('isAllowDelete')) {
    function isAllowDelete($controller) {
        $action = getRoleAction($controller);
        return (strpos($action,"d") !== false);
    }
}

if (! function_exists('isAllowPosting')) {
    function isAllowPosting($controller) {
        $action = getRoleAction($controller);
        return (strpos($action,"p") !== false);
    }
}

if (! function_exists('isAllowApprove')) {
    function isAllowApprove($controller) {
        $action = getRoleAction($controller);
        return (strpos($action,"v") !== false);
    }
}

if (! function_exists('isGranted')) {
    
    function isGranted($controller="", $method="") {
        $CI = &get_instance();

        if ($controller == "")
            $controller = strtolower($CI->router->fetch_class());  //Controller name
            
        if ($method == "")
            $method = strtolower($CI->router->fetch_method());

        $m = '';
        for ($i=0;$i<strlen($method);$i++) {
            $c = substr($method,$i,1);
            if (ord($c) >= 65 && ord($c) <= 90) break;
            $m .= $c;
        }
        $method = $m;

        log_message("info", "user_id=[".$_SESSION['profile']['user_id']."]module=[".$controller."]method[".$method."]");

        return true;

        if (!isset($_SESSION["modules"][$controller])) return false;
        $action = strtolower($_SESSION["modules"][$controller]);

        switch ($method) {
            case "upload": case "create": case "copy":
                return isAllowCreate($controller);

            case "delete": case "remove":
                return isAllowDelete($controller);

            case "update": case "cancel": case "setDefault":
                return isAllowUpdate($controller);

            case "process": case "apply":
                return isAllowPosting($controller);

            case "approve": case "reject":
                return isAllowApprove($controller);

            default:
                return true;
        }
    }
}
