<?php 

session_destroy();
$arr = array("Message" => "Çıkış Yaptınız");
echo json_encode($arr);