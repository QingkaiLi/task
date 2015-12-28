<?php

require_once '../common/common.php';

$fileName = param("file");
$type = param("type", "icon");

//$filePath = uploadFile($fileName, $type);

//json_put("filename", "upload/".$filePath);
//json_output();

$filePath = uploadFile("file", $type);
json_put("file", $filePath);
json_output();
?>
