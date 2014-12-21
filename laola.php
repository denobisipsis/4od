<?		
	echo "Laola<br>";		
	
	$headers = array(		 	          
	          "User-Agent: 		  stagefright/1.2 (Linux;Android 4.2.2)",
	          "Accept: 		      text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
	          "Connection: 		  keep-alive",
	          "Accept-Encoding:	gzip,deflate"
	          );
	
	// $title		=infobase($contenth);
	
	function extr($str,$pattern)
		{
		preg_match($pattern,$str,$matches);
		return $matches[1];
		}
		
	$iframe		=file_get_contents(extr($contenth,'/<iframe data-location[^>]+src=[\'"]([^"]+)[\'"]/'));
	
	$opts		=array("streamid","partnerid","portalid","sprache","auth","timestamp","v5ident");
	
	foreach ($opts as $opt)
		{
		${"$opt"}=extr($iframe,'/flashvars.'.$opt.' = [\'"]([^"]+)[\'"]/');
		}
	
	$call	  ="http://cdn.laola1.tv/server/hd_video.php";
	
	$options="?play=$streamid&partner=$partnerid&portal=$portalid&v5ident=$v5ident&lang=$sprache";
	
	$stream	=simplexml_load_string(file_get_contents($call.$options));
	
	$ident	=10000000 + floor(rand() * (99999999 - 10000000 + 1)).time();
	
	$userid	=0;
	
	$premium=1;
 
	$link	=file_get_contents($stream->video->url."&ident=$ident&klub=$premium&unikey=$userid&timestamp=$timestamp&auth=$auth");
		
	$link	=simplexml_load_string($link);
	
	$link	=$link->token->attributes();
	
	$f4m	=$link->url."?hdnea=".$link->auth."&g=MXKNXXYEYNEB&hdcore=3.2.0";

	echo $m3u8 =str_replace(array("/z/","manifest.f4m"),array("/i/","master.m3u8"),$f4m);
	
	// ffmpeg -i $m3u8
		
	//mpeg3u($m3u8,"http");
?>
