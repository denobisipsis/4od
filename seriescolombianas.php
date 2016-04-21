<?
/*
  -----BEGIN GEEK CODE BLOCK-----
  Version: 3.1
  GP/ED/H/L/CS dpu s-:- a+ C++(++++) w++++   
  O- M- PS++>$ PE-->$ Y-- PGP t- X++ R* tv+ b+++ D+++
  G+++ e++++ h--- r+++ z+++**
  ------END GEEK CODE BLOCK------
How to catch hqq videos
http://hqq.tv/watch_video.php?v=MDAS77YX4XSU
http://c8.vkcache.com/sec/ji8eBvEDg4COxQJJNvYXfg/1428224400/hls-vod-s4/flv/api/files/videos/2015/03/20/1426797363a86f2.mp4.m3u8
Denobis 2015
*/
function unicode2html($str)
		{
		    $str=str_replace("%u",'\u',$str);
		    
		    $i=65535;
		    
		    while($i>0){
		     $hex=dechex($i);
			   $zeros=str_repeat("0",4-strlen($hex));
		     
			   $str=str_replace("\u$zeros$hex","&#$i;",$str);
		        $i--;
		     }
		    
		    return html_entity_decode($str);
		}
	
	function un($t)	
		{
		// ajusta a unicode
		
		$t=substr($t,1);$s2="";
			for($i=0;$i<strlen($t);$i+=3)
			  {
			  $s2.="%u0".substr($t,$i,3);
			  }
		return $s2;
		}
	function y($data)
		{
		//base64_decode-> unused but fun
		
		$OO1lOI="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		$i=0;$enc="";
		while($i<strlen($data))	
			{
			for ($j=1;$j<5;$j++) ${"h$j"}=strpos($OO1lOI,substr($data,$i++,1));
			$bits=$h1<<18|$h2<<12|$h3<<6|$h4;
			
			$o1=$bits>>16&0xff;
			$o2=$bits>>8&0xff;
			$o3=$bits&0xff;
			
			if     ($h3==64) $enc.=chr($o1); 
			else if($h4==64) $enc.=chr($o1).chr($o2); 
			else        	   $enc.=chr($o1).chr($o2).chr($o3); 
			} 
				
		return $enc;
		}
if (!strpos($link,"embed"))
{
$vid  	=explode("v=",$link)[1];
$embed	=file_get_contents("http://hqq.tv/player/embed_player.php?vid=$vid&autoplay=no");
}
else
{
$vid=explode("&",explode("vid=",$link)[1])[0];
$embed  =file_get_contents($link);
}
$script	=base64_decode(trim(extract($embed,'<script src="data:text/javascript;charset=utf-8;base64',',','">')));
$vareval=explode("(",extract($script,'eval(','(',')'))[1];
$vareval=extract($script,"$vareval=","'","'");
$form    =unicode2html(extract(base64_decode(implode(array_reverse(str_split($vareval)))),'escape=',"'","'"));
$at		        =extract($form,'<input name="at"','value="','"');
$http_referer	=extract($form,'<input name="http_referer"','value="','"');
$embedsec1="http://hqq.tv/sec/player/embed_player.php?vid=$vid&at=$at&autoplayed=yes&referer=on&http_referer=";
$embedsec=explode("document.write(unescape",file_get_contents($embedsec1.urlencode($embedsec1.$http_referer."&pass=")."&pass=","","",$headers));
// se un-ea el 2º unescape
$unescape2=extract($embedsec[2],'(','"','"');
$unescape2=unicode2html(str_replace("%","%u00",$unescape2));
$link     ="#".extract($unescape2,'var','"#','"');
echo $link     =unicode2html(un($link));
?>
