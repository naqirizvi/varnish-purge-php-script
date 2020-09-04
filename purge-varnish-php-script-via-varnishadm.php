<?php
function _bot_detected() {

  return (
    isset($_SERVER['HTTP_USER_AGENT'])
    && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
  );
}
if (isset($_SERVER['HTTP_HOST']) && _bot_detected()!=1) {
$test1=$_SERVER['HTTP_HOST'];
shell_exec("/usr/bin/varnishadm -S /home/master/applications/qqkrczjdhg/public_html/secret -T localhost:6082 ban req.http.host == $test1");
echo "Varnish has been purged for\t";
echo $test1;
}else{echo "You may not purge Varnish";}
?>
