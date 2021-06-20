<?php

if ( isset( $_POST['go'] ) ) {

    connect_to_server();

    exit;

}

function error_handler( $errno, $errstr, $errfile, $errline )
{

    if (  ( $errno & error_reporting() ) > 0 ) {
        throw new ErrorException( $errstr, 500, $errno, $errfile, $errline );
    } else {
        return false;
    }

}

function connect_to_server()
{

    set_error_handler( 'error_handler' );

    try {
        /* Change this IP with your own */
        $server = '192.168.1.19';
        /* Change this Port with your own */
        $port = 8080;

        $fp = fsockopen( $server, $port, $errno, $errstr, 10 );

        if ( ! $fp ) {

            echo $errstr;
            die();

        } else {
            /* Turn Relay On */
            fwrite( $fp, hex2bin( 'a00101a2' ) );
            /* Need to sleep 0.5 second */
            usleep( 500000 );
            /* Turn Relay Off */
            fwrite( $fp, hex2bin( 'a00100a1' ) );
            /* Close connection */
            fclose( $fp );
            /* Send info to client */
            echo 'ok';

            die();

        }

    } catch ( Exception $e ) {

        echo $e->getMessage();

    }

}

?>

<html>

<head>
    <title>Remote ESP8266 WiFi 2 Channel Relay via WEB</title>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta content="utf-8" http-equiv="encoding">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="js/jquery.min.js"></script>

    <script>
    jQuery(document).ready(function($) {

        $(".btn").on("click", function() {

            var that = $(this);

            that.text('Connecting...');

            jQuery.ajax({
                type: "POST",
                data: {
                    go: 'connect'
                },
                success: function(data) {

                    if (data == 'ok') {
                        $("#audio").get(0).play();
                    } else {
                        alert(data);
                    }

                    that.text('CONNECT');

                }
            });

        });


    });
    </script>

</head>

<body ontouchstart="">
    <div class="box">
        <div class="btn" id="btn_connet">CONNECT</div>
        <audio style="display: none;" id="audio" src="audio/beep.wav" autoplay="false"></audio>
    </div>
</body>

</html>