<?php
$config = simplexml_load_file("config.xml");
header('Content-type: application/json');
?>
{"Protocol":"AutodiscoverV1","Url":"<?php echo $config->autodiscoverAddress; ?>/Autodiscover/Autodiscover.xml"}
