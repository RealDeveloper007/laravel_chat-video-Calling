<?php

require '../vendor/autoload.php';

use Amir\Comm;

//set an array of origins allowed to connect to this server
$allowed_origins = ['localhost', '127.0.0.1', 'domain.com', 'www.domain.com'];

// Run the server application through the WebSocket protocol on port 8080
$app = new Ratchet\App('domain.com', 8080, '0.0.0.0');//App(hostname, port, 'whoCanConnectIP', '')

//create socket routes
//route(uri, classInstance, arrOfAllowedOrigins)
$app->route('/comm', new Comm, $allowed_origins);

//run websocket
$app->run();