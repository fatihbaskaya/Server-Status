<?php

$title = "Server Status"; // website's title
header("Refresh: 60;");
$servers = array(
    'google.com' => array(
        'ip' => 'google.com',
        'port' => 443,
        'info' => 'Google',
        'purpose' => 'Website'
    ),
    'cs.fatihbaskaya.com' => array(
        'ip' => '1.11.11.11',
        'port' => 21,
        'info' => 'Hosted by Fatih Baskaya',
        'purpose' => 'Counter-Strike 1.6 Server'
    )
    'fatihbaskaya.com' => array(
        'ip' => 'fatihbaskaya.com',
        'port' => 443,
        'info' => 'Hosted by Fatih Baskaya',
        'purpose' => 'Website'
    )

);

if (isset($_GET['host'])) {
    
    $host = $_GET['host'];
    
    if (isset($servers[$host])) {
        header('Content-Type: application/json');

        $return = array(
            'status' => test($servers[$host])
        );

        echo json_encode($return);

        exit;
    } else {
        header("HTTP/1.1 404 Not Found");
    }
}

$names = array();
foreach ($servers as $name => $info) {
    $names[$name] = md5($name);
}


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
        <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }
        
        .ping__box__wrap {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px;
            justify-content: center;
        }
        
        .ping__box {
            font-family: helvetica, arial, sans-serif;
            display: flex;
            flex-direction: column;
            background: #ccc;
            width: 300px;
            height: auto;
            padding: 15px;
            box-sizing: border-box;
            justify-content: space-evenly;
            border-radius: 6px;
        }
        
        .ping__box .ping__box__line {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .ping__box .ping__box__line:first-child {
            padding-bottom: 10px;
        }
        
        .ping__box .ping__box__line:last-child {
            border: none;
            padding-top:10px;
        }
        
        .ping__box .ping__box__line .ping__box__line__title {
            font-weight: 600;
            font-size: 18px;
            color: #fafafa;
            margin-bottom:4px;
        }
        
        .ping__box .ping__box__line .ping__box__line__content {
            font-weight: 400;
            font-size: 16px;
            color: #dbdbdb;
        }
        .success{
            background:#007E34 !important;
        }
        .error{
            background:#CC0001 !important;
        }
        h1{
            text-align: center;
            font-family:helvetica, arial, sans-serif;
            margin-top:15px;
            margin-bottom:15px;
            color:#fafafa !important;
        }
        body{
            background:#121212 !important;
        }
        </style>
    </head>
    <body>

            <h1><?php echo $title; ?></h1>
           
            <div class="ping__box__wrap">
                    <?php foreach ($servers as $name => $server): ?>

                        <div id="<?php echo md5($name); ?>" class="ping__box">
                            <div class="ping__box__line">
                                <span class="ping__box__line__title"><?php echo $name; ?></span>
                                <span class="ping__box__line__content name"><?php echo $server['purpose']; ?> <i class="icon-spinner icon-spin icon-large"></i></span>
                            </div>
                            <div class="ping__box__line">
                                <span class="ping__box__line__title">PING</span>
                                <span class="ping__box__line__content"><?php exec("ping -n 2 ".$server['ip'], $output, $status);
                                if ($status == 0){
                                    echo $output[8];
                                    unset($output);
                                } 
                                ?></span>
                            </div>
                        </div>
                        
                    <?php endforeach; ?>
            </div>
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript">

            function test(host, hash) {
                // Fork it
                var request;

                // fire off the request to /form.php
                request = $.ajax({
                    url: "<?php echo basename(__FILE__); ?>",
                    type: "get",
                    data: {
                        host: host
                    },
                    beforeSend: function () {
                        $('#' + hash).children().children().children().css({'visibility': 'visible'});
                    }
                });

                // callback handler that will be called on success
                request.done(function (response, textStatus, jqXHR) {
                    var status = response.status;
                    var statusClass;
               
                    if (status) {
                        statusClass = 'success';
                    } else {
                        statusClass = 'error';
                    }

                    $('#' + hash).removeClass('success error').addClass(statusClass);
                });

                // callback handler that will be called on failure
                request.fail(function (jqXHR, textStatus, errorThrown) {
                    // log the error to the console
                    console.error(
                        "The following error occured: " +
                            textStatus, errorThrown
                    );
                });


                request.always(function () {
                    $('#' + hash).children().children().children().css({'visibility': 'hidden'});
                })

            }

            $(document).ready(function () {

                var servers = <?php echo json_encode($names); ?>;
                var server, hash;

                for (var key in servers) {
                    server = key;
                    hash = servers[key];

                    test(server, hash);
                    (function loop(server, hash) {
                        setTimeout(function () {
                            test(server, hash);

                            loop(server, hash);

                        }, 6000);
                    })(server, hash);
                }

            });
        </script>

    </body>
</html>
<?php
/* Misc at the bottom */
function test($server) {
    $socket = @fsockopen($server['ip'], $server['port'], $errorNo, $errorStr, 3);
    if ($errorNo == 0) {
        return true;
    } else {
        return false;
    }
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

?>