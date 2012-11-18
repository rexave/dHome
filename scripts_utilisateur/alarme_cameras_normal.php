<?php

echo "normal";

	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "dhomeUI/scripts_utilisateur/alarme_cameras.php?etat=normal");
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

echo "NORMAL";


?>