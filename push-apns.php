<?php
//$deviceToken = 'cbe1bae1d45498a6ec126e2f25a3290b5c3103fab736508d111b0012c65998df';

$deviceToken = '0f286a3d6871bae48f6fa6d102b1201932f62afe417d361d3ef3d7c3035a38dc';
    
$body['content_available'] = false;
$body['aps'] = array(
                     'alert' => [
                     		'title' => 'Push',
                     		'body' => 'Hello',
                     		],
                     'badge' => 1
                     );

$body["notification"] = array(
					'body' => 'teste via php'
					); 

$body["data"] = array(
				'tipoGCM' => "noti",
				'tipoArquivo' => 'IMAGE',
				'id' => "205be4d8-eab0-4d0f-a80d-49f730ff9582",
				'descricao' => "Descrição da notícia",
				'tipo' => [
					'tipo' => 'Alerta',
					'nome' => 'Título da notícia',
					'icone' => 'M13,14H11V10H13M13,18H11V16H13M1,21H23L12,2L1,21Z',
					//'id' => 'e53ec432-a143-4c7d-bb3e-3c3e07e50798',
					 'id' => '205be4d8-eab0-4d0f-a80d-49f730ff9582',
					]
				);

    
//Server stuff
$passphrase = '@Senha123';
$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
$fp = stream_socket_client(	'ssl://gateway.sandbox.push.apple.com:2195', $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
// $fp = stream_socket_client(	'ssl://gateway.push.apple.com:2195', $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);
echo 'Connected to APNS' . PHP_EOL;

$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));
if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;
fclose($fp);
    