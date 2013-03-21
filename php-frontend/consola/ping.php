<?php
$logdir = dirname(dirname(__FILE__)) . "/tms/logs";
$NLINES = 1000;
$cmd = "tail -n $NLINES $logdir/log.txt";

//echo ('a '. microtime() .  "<br/>");
$lines =  shell_exec( $cmd ); 
//die('b '. microtime());
$lines = explode("\n",  $lines);
$uniq = array();
$uniqnodes = array();
foreach ($lines as $ll) {
	if (trim($ll) == '') {
		continue;
	}

	$ll = explode(' - ', $ll);
	$fecha = $ll[0];
	$tile = $ll[1];	
	$origen = explode(',', $ll[2]);
	
	//var_dump($origen);
	$origen[0] = explode('?', $origen[0]);
	$referer = trim($origen[0][0]); 
	
	$ip = trim($origen[1]);
	if (! $ip ) {
		$ip = " IP pública N/D";
	}
	$private_ip = trim(@$origen[2]);
	if (! $private_ip ) {
		$private_ip = "IP privada N/D";
	}
	$tein_proxy = ($private_ip != 'IP privada N/D');
	$thisNode = "http://www.ign.gob.ar/tms";
  if ($referer) {
  	if ($tein_proxy) {
  		$uniq[] =  "$referer -> Proxy $ip  {color:#218559, weight:2}";	
  	} else {
  		$uniq[] =  "$referer -> $ip  {color:#218559, weight:2}";	
  	}
		
		$uniq[] = "$thisNode -> $referer {color:#192823, weight:5}";
		$uniqnodes[] = "$referer {color:#218559} ";
	} else {
		$uniq[] =  "$ip";
		$uniq[] = "$thisNode -> $ip {color:red, weight:5}";
	}
	if ($tein_proxy) {
		$uniq[] = "Proxy $ip -> $private_ip  {color:#D0C6B1, weight:2}";
		$uniqnodes[] = "Proxy $ip {color:#D0C6B1}";
		$uniqnodes[] = "$private_ip {color:#EBB035}";
	} else {
		$uniqnodes[] = "$ip {color:#EBB035}";
	}

}
	$uniqnodes[] = "$thisNode {color:#192823}";
	echo implode("\n", array_unique($uniq)); 
	echo "\n";
	echo implode("\n", array_unique($uniqnodes)); 

