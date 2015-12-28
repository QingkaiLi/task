<?php

require_once 'apc_dict.php'; 

function getIpStats() {
    $stats = new ApcDict("FIREWALL_IP_STATS");
    return $stats; 
}

class IpStat {
    public $ip; 
    public $access_count; 
    public $execute_time; 
    public $imei = null; 
    public $pid = null; 
    public $last_time = 0; 
    public $frequency = 0; 
    
    public function __construct($ip) {
        $this->ip = $ip; 
        $this->access_count = 0; 
        $this->execute_time = 0;
    }
    
    public function frequency() {
        $now = microtime(TRUE); 
        $t = $now - $this->last_time;
        if ($t > 5) $t = 5;  
        $freq = $this->frequency * (5-$t) / 5 / 5;
        return $freq; 
    }
}
// 
// $__firewall_ip_stats = NULL; 
// $__current_ip_stat = NULL; 

function firewall_start()
{

}

function firewall_finish($t) 
{
    global $__firewall_ip_stats, $__current_ip_stat; 
    
    $ip = $_SERVER["REMOTE_ADDR"];
    $stats = getIpStats();
    
    $stat = $stats->fetch($ip); 
    if ($stat === FALSE) {
        $stat = new IpStat($ip);
        $exists = FALSE;  
    } else {
        $exists = TRUE; 
    }
    
    $stat->access_count++;
    $imei = imei();
    $pid = pid(false);
    if ($imei !== null) $stat->imei = $imei;
    if ($pid !== null) $stat->pid =  $pid;
    $stat->url = $_SERVER["REQUEST_URI"];
    $now = microtime(true);
    if ($stat->last_time) {
        $elapsed = $now - $stat->last_time;
        if ($elapsed >= 5) $elapsed = 5; 
        $stat->frequency = $stat->frequency * (5-$elapsed) / 5 + 1; 
    }
    
    $stat->last_time = $now;
    $stat->execute_time += $t; 
    $stats->store($ip, $stat, $exists); 
} 

?>
