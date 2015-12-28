<?php

function apcfetch($key) {
    return apc_fetch($_SERVER["SERVER_NAME"].".".$key);
}

function apcstore($key, $value) {
    apc_store($_SERVER["SERVER_NAME"].".".$key, $value);
}

function apcdelete($key) {
    apc_delete($_SERVER["SERVER_NAME"].".".$key);
}

?>