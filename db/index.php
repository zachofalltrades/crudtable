<?php

//   http://www.restapitutorial.com/httpstatuscodes.html
//   http://www.jopera.org/files/SOA2009-REST-Patterns.pdf

ini_set("display_errors", "Off");
ini_set("error_log", __DIR__ . DIRECTORY_SEPARATOR . ".log");
error_reporting(E_ALL);//(E_ALL) (E_ERROR | E_WARNING | E_PARSE)
//add current directory to include path
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
spl_autoload_register();
$enableDebug = false;
$request = new RequestObject();
debug($request);

try {
	require_once ('config.php');
	$db = new PDO($db_conn, $db_user, $db_pass);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db_pass = null;
	$db_user = null;
	$db_conn = null;
} catch (PDOException $e) {
	exitWithException($e, 503);//Service Unavailable
}

$model = new AnimalModel($db);
$handler = new CrudHandler($db);

try {
	//TODO let model specify which operations are supported
	if ($request->method === 'POST') {
		$statement = $model->getCreateStatement($request->parameters);
		$handler->handleCreate($statement);

	} else if ($request->method === 'GET') {
		$statement = $model->getReadStatement($request->parameters);
		$handler->handleRead($statement);

	} else if ($request->method === 'PUT') {
		$statement = $model->getUpdateStatement($request->parameters);
		$handler->handleUpdate($statement);

	} else if ($request->method === 'DELETE') {
		$statement = $model->getDeleteStatement($request->parameters);
		$handler->handleDelete($statement);

	} else if ($request->method === 'HEAD' || $request->method === 'OPTIONS') {
		header('Cache-Control: no-cache, must-revalidate');
		header("Allow: GET, POST, PUT, DELETE");
		exitWithHttpCode(204);//No Content

	} else {
		header("Allow: GET, POST, PUT, DELETE");
		exitWithMessage("http method '$request->method' not supported", 405);//Method Not Allowed
	}
	
	exitWithMessage("unexpected logical fall-through", 501);// Not Implemented

} catch (PDOException $e) {
	exitWithException($e, 500);//Internal Server Error
}

function exitWithException(Exception $e, $code) {
	cleanup();
	debug($e, true);
	exitWithMessage($e->getMessage(), $code);
}

function exitWithMessage($msg, $code) {
	cleanup();
	$error_message = print_r($msg, true);
	debug("HTTP ".$code." : ".$error_message, true);
	http_response_code($code);
	include('error.php');
	exit(1);
}

function exitWithHttpCode($code) {
	cleanup();
	debug("HTTP ".$code);
	http_response_code($code);
	exit(0);
}

function debug($x, $force = false) {
	global $enableDebug;
	if ($enableDebug || $force) {
		error_log(print_r($x, true));
	}
}

function cleanup() {
	global $db, $model, $handler;
	$db = null;
	$model = null;
	$handler = null;
}
?>