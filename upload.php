<?php
    // $host="wiresharklab.uoi.gr";
    // $port=4567;

    $host="127.0.0.1";
    $port=4567;

    if (isset($_FILES) && $_FILES["file_selector"]["error"] !== UPLOAD_ERR_OK) {
        die("Error: file upload failed");
    }

    $socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
    if($socket==false)
    {
        die("Error: failed to create socket");
    }

    $result=socket_connect($socket, $host, $port);
    if($result==false)
    {
        die("Error: failed to connect to server");
    }

    // get the file contents
    $file_path=$_FILES["file_selector"]["tmp_name"];
    $file=fopen($file_path,"rb");
    if($file==false)
    {
        die("Error: failed to open file");
    }

    $file_contents=fread($file,filesize($file_path));
    fclose($file);

    // Construct the request
    $request = "POST /upload HTTP/1.1\r\n";
    $request .= "Host: $host:$port\r\n";
    $request .= "Content-Type: application/octet-stream\r\n";
    $request .= "Content-Length: " . strlen($file_contents) . "\r\n\r\n";
    $request .= $file_contents . "\r\n";
    $request .= "Name: $firstname $lastname\r\n";

    // Send the request to the server
    $result = socket_write($socket, $request, strlen($request));
    if ($result === false) {
        die("Error: failed to send request to server");
    }
    socket_close($socket);


    echo "<b>The upload was created succefully<b>";
?>