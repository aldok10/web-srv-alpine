<?php
use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

$server = new OpenSwoole\HTTP\Server("127.0.0.1", 1215);

$server->on("start", function (Server $server) {
    echo "OpenSwoole http server is started at http://127.0.0.1:1215\n";
});

$server->on("request", function (Request $request, Response $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n" . swoole_cpu_num());
});

$server->start();

