<?php
	$client = new Yar_Client("http://192.168.0.109/yar_server");

	echo $client->api('test');
?>
