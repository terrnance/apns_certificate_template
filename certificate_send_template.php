<html>
<head>
  <title></title>
  <?php
//Load and Run this code after you have created your pem notifications certificate

  //Background white on load
  $backgroundColor = "FFFFFF";

  //Either supply Url Parameters or default values for notification body
    $title = extractUrlParameter('title','title');//Title of Alert
  $message = extractUrlParameter('message','message');//Message of Alert

  $deviceTokens = array();//Enter Device Tokens Here
  //Extract the list of device token
  if (isset($_GET["deviceTokens"])) && count($deviceTokens) == 0){
    //OR If fully supplied through url -extract those supplied
    parse_str(urldecode($_GET["deviceTokens"]),$deviceTokens); //List of device tokens
  }

  //Verify that the parameters were filled correctly
  if (strlen($message) > 0 && strlen($title) > 0 && count($deviceTokens) > 0){


    //CREATE THE BASIC CONTEXT
    //OPTION 1 - THIS FOR BASIC CONTEXT
    $ctx = stream_context_create();


    //       //OPTION 2 - UNCOMMENT FOR CUSTOMIZED CONTEXT - (use this in the case that
    //       //the cacert.pem is not located in your php.ini file)
    //       //To validate ssl connection - point to the cacert.pem
    //       $contextOptions = array(
    //     'ssl' => array(
    //         'verify_peer' => true, // You could skip all of the trouble by changing this to false, but it's WAY uncool for security reasons.
    //         'cafile' => '/etc/ssl/cacert.pem', //i.e. '/etc/ssl/cacert.pem'
    //         //'CN_match' => 'example.com', // Change this to your certificates Common Name (or just comment this line out if not needed)
    //         'ciphers' => 'HIGH:!SSLv2:!SSLv3',
    //         'disable_compression' => true,
    //     )
    // );
    //$ctx = stream_context_create($contextOptions);


    // Put your private key's passphrase here:
    $passphrase = '##########';
    //Put your filename of pem file here (include complete path if necessary)
    $filename = "#####.pem"
    stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    // Open a connection to the APNS server
    //DEVELOPMENT gateway.sandbox.push.apple.com:2195
    //PRODUCTION gateway.push.apple.com:2195
    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

    //Error checking with apns server
    if (!$fp)
    exit("Failed to connect: $err $errstr" . PHP_EOL);

    echo 'Connected to APNS' . PHP_EOL;


    // Create the payload body using values given
    $myObj['title'] = $title;
    $myObj['body'] = $message;
    $alertJSON['alert'] = $myObj;
    $alertJSON['badge'] = 0;
    $body['aps'] = $alertJSON;

    // Encode the payload as JSON
    $payload = json_encode($body);

    $failed_delivery = false;

    //Iterate through all of the device tokens provided
    foreach ($deviceTokens as $deviceToken) {
      //Verify that device token is of the correct length (typically 64 but can vary)
      if (strlen(htmlspecialchars($deviceToken)) > 30){
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Check whether the message was successfully delivered
        if (!$result){
          $failed_delivery = true;
          echo 'Message not delivered' . PHP_EOL;
        }
        else{
          echo 'Message successfully delivered' . PHP_EOL;
        }


      }
    }

    //Show color based on results

    if ($failed_delivery == true){
      //One or more of the notifications failed to deliver - show GREEN
      $backgroundColor = 'A2CD23'; //GREEN
    }

    else{
      //Success on all devicce tokens
      $backgroundColor = '00BBF6'; //Success blue
    }


    //Close the connection
    fclose($fp);

  }
  else{
    //Invalid values are provided (i.e. no title, body, or device tokens given)
    exit();
  }

//Extracts the url parameter value
// if null given -- use the default value supplied
  function extractUrlParameter($parameter_name,$default_value){
    $str_returned = $default_value;
    if (isset($_GET[$parameter_name])){
      //If parameter - extract and return value
      $str_returned = htmlspecialchars(urldecode($_GET[$parameter_name]));
    }
    return $str_returned;
  }

  ?>
</head>
<body style="background-color: #<?php echo $backgroundColor; ?>;"></body>
</html>
