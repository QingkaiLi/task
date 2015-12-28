<?php

class SQLProfileEntry {
    public $sql = null; 
    public $execute_count = 0;
    public $execute_time = 0;
    public $start_time = 0;  
    public function __construct($sql) {
        $this->sql = $sql; 
    }
    
}

// not implemented yet
function profile_script_start()
{
    
} 

// not implemented yet
function profile_script_end()
{
    
} 

$__profile_sql_index = 0;
$__profile_executing_sql = NULL; 
$__profile_sql_start_time = 0;
function profile_sql_start($sql)
{
    global $__profile_executing_sql, $__profile_sql_start_time; 
    $__profile_executing_sql = $sql; 
    $__profile_sql_start_time = microtime(true);
}

function profile_sql_end() 
{
    global $__profile_executing_sql, $__profile_sql_start_time; 
    global $__profile_sql_index; 
    if ($__profile_executing_sql) {
        $__profile_sql_index += 1; 
        $sql = $_SERVER["SCRIPT_NAME"] . ".${__profile_sql_index}"; 
        // $sql = $__profile_executing_sql; 
        $start_time = $__profile_sql_start_time; 
        $take_time = microtime(true) - $start_time; 
        $profile = apcfetch("SQL_PROFILE");
        if ($profile === FALSE) $profile = array();
        $entry = null;
        if (!isset($profile[$sql])) {
            $entry = new SQLProfileEntry($sql);
            $profile[$sql] = $entry; 
        } else {
            $entry = $profile[$sql];
        }
        $entry->execute_count++;
        $entry->execute_time += $take_time; 
        apcstore("SQL_PROFILE", $profile); 
    }
}

function get_sql_profile() {
    $profile = apcfetch("SQL_PROFILE");
    return $profile; 
}

function profile_record_lock_time($lock_time) {
    $lc = apcfetch("LOCKTIME");
    if ($lc !== FALSE) {
        $lc[0] += $lock_time;
        $lc[1] += 1;
    } else {
        $lc = array($lock_time, 1);
    }
    apcstore("LOCKTIME", $lc);
}

function profile_average_lock_time() {
    $lc = apcfetch("LOCKTIME");
    return $lc[0] / $lc[1];
}

?>
