<?php

$hostname = $_SERVER['SERVER_NAME'];

header( 'Cache-Control: max-age=0' );

$port     = 80;
$debug    = true;
$URL      =  "/*";

purgeURL( $hostname, $port, $URL, $debug );

function purgeURL( $hostname, $port, $purgeURL, $debug )
{
//   Uncomment the below line if you have Varnish running on other HOST and PORT
//    $finalURL = sprintf("http://%s:%d%s", $hostname, $port, $purgeURL );
//If varnish is running on same domain use the line below
$finalURL =$_SERVER['HTTP_HOST'];
    print( "<br> Purging ${finalURL} <br><br>" );

    $curlOptionList = array(
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_CUSTOMREQUEST     => 'PURGE',
        CURLOPT_HEADER            => true ,
        CURLOPT_NOBODY            => true,
        CURLOPT_URL               => $finalURL,
        CURLOPT_CONNECTTIMEOUT_MS => 2000
    );

    $fd = true;
    if( $debug == true ) {
        print "<br>---- Curl debug -----<br>";
        $fd = fopen("php://output", 'w+');
        $curlOptionList[CURLOPT_VERBOSE] = true;
        $curlOptionList[CURLOPT_STDERR]  = $fd;
    }

    $curlHandler = curl_init();
    curl_setopt_array( $curlHandler, $curlOptionList );
    $return = curl_exec($curlHandler);

    if(curl_error($curlHandler)) {
    print "<br><hr><br>CRITICAL - Error to connect to $hostname port $port - Error:  curl_error($curl) <br>";
    exit(2);
 }

    curl_close( $curlHandler );
    if( $fd !== false ) {
        fclose( $fd );
    }
    if( $debug == true ) {
        print "<br> Output: <br><br> $return <br><br><hr>";
    }
}
?>

<title>Purge cache</title>
Press submit to purge the cache
<form method="post" action="<?php echo $PHP_SELF;?>">
<input type="submit" value="submit" name="submit">
</form>
