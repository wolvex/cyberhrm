<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function queryRecord($sql, $args = array()) {
    $ci = &get_instance();
    $query = $ci->db->query($sql, $args);
    return $query->result_array();
}

function setValue($ci, $fieldData, $value)  {
    $field = $fieldData->name;
    $type  = $fieldData->type;
  
    if ($value == '' || strtoupper($value) == 'NULL') {
        $ci->db->set($field, 'NULL', false);
    } else if (strtoupper($value) == 'SYSDATE' || strtoupper($value) == 'NOW') {
        $ci->db->set($field, 'SYSDATE', false);
    } else if ($type == 'DATE' && strlen($value) >= 4) {
        if (preg_match('/[0-9]{1,2}-[a-zA-Z]{3}-[0-9]{4} [0-9]{1,2}:[0-9]{2}/', $value) == 1) {
            $ci->db->set($field, "to_date('".$value."','DD-MON-YYYY HH24:MI')", false);
        } else if (preg_match('/[a-zA-Z]{3,10} [0-9]{1,2}, [0-9]{4} [0-9]{1,2}:[0-9]{2}/', $value) == 1) {
            $ci->db->set($field, "to_date('".$value."','MONTH DD, YYYY HH24:MI')", false);
        } else if (preg_match('/[0-9]{1,2}-[a-zA-Z]{3}-[0-9]{4}/', $value) == 1) {
            $ci->db->set($field, "to_date('".$value."','DD-MON-YYYY')", false);
        } else if (preg_match('/[a-zA-Z]{3,10} [0-9]{1,2}, [0-9]{4}/', $value) == 1) {
            $ci->db->set($field, "to_date('".$value."','MONTH DD, YYYY')", false);
        } else if (preg_match('/[0-9]{1,2}:[0-9]{2}/', $value) == 1) {
            $ci->db->set($field, "to_date('".$value."','HH24:MI')", false);
        }
    } else {  
        if (is_numeric(str_replace(',','',$value))) {
            $value = str_replace(',','',$value);
        }     
        $ci->db->set($field, $value);
    }
}

function insertRecord($table, $data, $keys) {
    $ci = &get_instance();

    $table = strtoupper($table);
    $generated_keys = array();
    
    array_change_key_case($data, CASE_UPPER);
    array_change_key_case($keys, CASE_UPPER);

    $fields = $ci->db->field_data($table);
	foreach ($fields as $fieldData) {
        $field = $fieldData->name;
        
        if (array_key_exists($field, $data)) {
            $value = $data[$field];
        } else {
            $value = $ci->getDefault($field, true);
            if ($value == '') continue;
        }

        if (in_array($field, $keys)) {
            if ($value == '0' || $value == '') {
                $value = $ci->getSequence($table);
                $generated_keys[$field] = $value;
            }
        }
        setValue($ci, $fieldData, $value);
	}
	$ci->db->insert($table);
	
	return $generated_keys;
}

function updateRecord($table, $data, $keys) {
    array_change_key_case($data, CASE_UPPER);
    array_change_key_case($keys, CASE_UPPER);

    //check completeness
	foreach ($keys as $field) {
		if (!array_key_exists($field, $data)) return 0;
    }
    
    $ci = &get_instance();
    $table = strtoupper($table);
    
    $fields = $ci->db->field_data($table);
	foreach ($fields as $fieldData) {
        $field = $fieldData->name;
		
        if (!array_key_exists($field, $data)) {
            $value = $ci->getDefault($field);
            if ($value == '') continue;
        } else {
            $value = $data[$field];
        }
		
	    if (in_array($field, $keys)) {
            $ci->db->where($field, $value);
        } else {
            setValue($ci, $fieldData, $value);
        }
	}
	$ci->db->update($table);
	return $ci->db->affected_rows();
}

function deleteRecord($table, $data, $keys) {
    array_change_key_case($data, CASE_UPPER);
    array_change_key_case($keys, CASE_UPPER);

    //check completeness
	foreach ($keys as $field) {
		if (!array_key_exists($field, $data)) return 0;
    }
    
	$ci = &get_instance();
    $table = strtoupper($table);
    
    foreach ($keys as $field) {
		$ci->db->where($field, $data[$field]);
    }
    $ci->db->delete($table);
	return $ci->db->affected_rows();
}