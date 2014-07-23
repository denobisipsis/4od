<?
/*

PHP SCRIPT TO GET YOUKU VIDEOS

DENOBIS 23/07/2014

PLEASE SHARE BUT DONT FORGET TO MENTION

*/

function na($a)
		{
		
		// NA FUNCTION TRANSFORM INITIAL EP VALUE
		
		$h=array(-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1);
		
		$i=strlen($a);
		$d="";$f=0;
		
		while ($f<$i)
			{		
			$c=$h[ord($a[$f])&255];$f++;
			
			while ($f<$i and $c==-1) 	{$c=$h[ord($a[$f])&255];$f++;}
			
			if ($c==-1)	break;
			
			$b=$h[ord($a[$f])&255];$f++;
				
			while ($f<$i and $b==-1)	{$b=$h[ord($a[$f])&255];$f++;}
			
			if ($b==-1)	break;
			
			$d.=chr($c<<2|($b&48)>>4);
			
			$c=ord($a[$f])&255;$f++;
			if ($c==61)	return $d;
			$c=$h[$c];
								
			while ($f<$i and $c==-1)
				{
				$c=ord($a[$f])&255;$f++;
				if ($c==61)return $d;
				$c=$h[$c];
				}
			
			if ($c==-1)	break;
			
			$d.=chr(($b&15)<<4|($c&60)>>2);
			
			$b=ord($a[$f])&255;$f++;
			if ($b==61)	return $d;
			$b=$h[$b];
							
			while ($f<$i and $b==-1)
				{
				$b=ord($a[$f])&255;$f++;
				if ($b==61)return $d;
				$b=$h[$b];
				}	
				
			if ($b==-1)	break;
			
			$d.=chr(($c&3)<<6|$b);
			}
			
		return $d;
		}
	
	function ee($a,$c)
		{
		for($h=0;$h<256;$h++)
				{$b[$h]=$h;}
		
		for($h=0;$h<256;$h++)
			{
			// swap b(h), b(f)
			
			$f=($f+$b[$h]+ord($a[$h%strlen($a)]))%256;
			$i=$b[$h];
			$b[$h]=$b[$f];
			$b[$f]=$i;
			}
			
		$f=$h=0;
		for($q=0;$q<strlen($c);$q++)
			{
			$h=($h+1)%256;
			$f=($f+$b[$h])%256;
			$i=$b[$h];
			$b[$h]=$b[$f];
			$b[$f]=$i;
			$d.=chr(ord($c[$q])^$b[($b[$h]+$b[$f])%256]);
			}
		return $d;
		}
	
	function ff($a,$c)
		{
		$b=array();
		for($f=0;$f<strlen($a);$f++)
			{
			for($d=0;$d<36;$d++)
				{
				if (ord($a[$f])>=97 and ord($a[$f])<=122) $i=ord($a[$f])-97;
				else                             	  $i=$a[$f]+26;
				
				if($i==$c[$d])
					{$i=$d;break;}
				}
				
			if ($i>25) $b[$f]=$i-26; 
			else       $b[$f]=chr($i+97);			
			}
			
		return implode($b);
		}

	function gen_ep($str)
		{
		$a="boa4poz1";
		$c=array(19,1,4,7,30,14,28,8,24,17,6,35,34,16,9,10,13,22,32,29,31,21,18,3,2,23,25,27,11,20,5,15,12,0,33,26);
		
		return urlencode(base64_encode(ee(ff($a,$c),$str)));
		}

	function gen_token($str)
		{
		$a="b4eto0b4";
		$c=array(19,1,4,7,30,14,28,8,24,17,6,35,34,16,9,10,13,22,32,29,31,21,18,3,2,23,25,27,11,20,5,15,12,0,33,26);
		
		return ee(ff($a,$c),na($str));		
		}
				
	function RandomProxy($seed)
		{	
		$_loc2_ = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/\:._-1234567890";
	
		$_loc4_ = strlen($_loc2_);
	
		$_loc5_ = 0;$name_1 = "";
		
		while($_loc5_ < $_loc4_)
			{
			$seed =($seed * 211 + 30031) % 65536;
			
			$_loc7_ = (int)($seed/65536 * strlen($_loc2_));
						
			$name_1.= $_loc2_[$_loc7_];			
			
			$_loc2_=explode($_loc2_[$_loc7_],$_loc2_)[0].explode($_loc2_[$_loc7_],$_loc2_)[1];
			
			$_loc5_++;			
			}
			
		return $name_1;		
		}
	
	function cg_fun($param1,$seed)
		{
		
		// YES, VERY FUN
		
		$name_1=RandomProxy($seed);		
			
		$_loc2_= explode("*",$param1);
		$_loc3_= "";
		$_loc4_ = 0;
	
		while($_loc4_ < (sizeof($_loc2_)-1))
			{						
			$_loc3_.=$name_1[$_loc2_[$_loc4_]]; 						
			$_loc4_++;
			}
			
		return $_loc3_;
		}
			
	function fileid($streamfileids,$seed,$no)
		{		
		$z=cg_fun($streamfileids,$seed);
		
		return substr($z,0,8).strtoupper($no).substr($z,10);
		}	
	
  /* MAIN CODE TO OBTAIN THE LINKS */
  
	$v	=extrae(file_get_contents($link),'var videoId',"'","'");

	$api="http://v.youku.com/player/getPlayList/VideoIDS/$v/Pf/4/ctype/12/ev/1?__callback=";
	
	$json1=json_decode(file_get_contents($api));
	
	$ip		=$json1->data[0]->ip;		
	$ep		=$json1->data[0]->ep;
	
	$key1		=$json1->data[0]->key1;
	$key2		=$json1->data[0]->key2;
							
	$ts		=intval($json1->data[0]->seconds);
	$seed		=$json1->data[0]->seed;

	$headers = array(		 	          
	          "User-Agent: 		    Youku HD;4.1.1;Android;4.0.4;GT-N7000",
	          "Accept: 		        text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
	          "Connection: 		    keep-alive",
	          "Accept-Encoding: 	gzip,deflate"
	          );
	
	$streamtypes	=$json1->data[0]->streamtypes;
	
	$sidtoken	=gen_token($ep);
	
	$sid		  =explode("_",$sidtoken)[0];
	$token		=explode("_",$sidtoken)[1];
			
	foreach ($streamtypes as $item)
		{
		
		// QUALITIES
		
		$streamfileids	=$json1->data[0]->streamfileids->{"$item"};
	
		if($item=="hd2" or $item=="hd3" or $item=="flv" or $item=="flvhd") $st="flv"; else $st="mp4";

		if($item=="flv" or $item=="flvhd") 	$hd="0";		
		else if($item=="mp4")			$hd="1";		
		else if($item=="hd2")			$hd="2";		
		else if($item=="hd3")			$hd="3";
		else $hd="1";
		
		echo "Qual $item<br>";
		
		$parts=$size=$tsize=0;
		
		foreach ($json1->data[0]->segs->{"$item"} as $part)
			{
			
			// PARTS
			
			$no	=dechex($part->no);
			if(strlen($no) == 1) $no="0".$no;	
			
			$k	=$part->k;
			
			if ($k=="-1" or $k=="") {$k=$key2.$key1;}
				
			$fileid	=fileid($streamfileids,$seed,$no);
										
			$ep	=gen_ep($sid."_".$fileid."_".$token);
									
			$link ="http://k.youku.com/player/getFlvPath/";
			$link.="sid/$sid"."_$no/";
			$link.="st/$st/";
			$link.="fileid/$fileid";
			$link.="?K=$k";	
			$link.="&hd=$hd";
			$link.="&myp=0";
			$link.="&ts=$ts";
			$link.="&ypp=0";		
			$link.="&ctype=12";
			$link.="&ev=1";
			$link.="&token=$token";
			$link.="&oip=$ip";
			$link.="&ep=$ep";
			
			// $LINK ITS THE FINAL LINK
			
			echo "$parts <a href='$link'>$title</a> ".formatBytes($size)."<br><br>";
			++$parts;
			}
		}
?>
