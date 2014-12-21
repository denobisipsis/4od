<?		
	echo "Laola<br>";		
	
	$headers = array(		 	          
	          "User-Agent: 		  stagefright/1.2 (Linux;Android 4.2.2)",
	          "Accept: 		      text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
	          "Connection: 		  keep-alive",
	          "Accept-Encoding:	gzip,deflate"
	          );
	
	$title		=infobase($contenth);
	
	$iframe		=file_get_contents(explode('"',explode("src=\"",explode("<iframe data-location",$contenth)[1])[1])[0]);
	
	$streamid	  =extrae($iframe,"flashvars.streamid =",'"','"');
	$partnerid	=extrae($iframe,"flashvars.partnerid =",'"','"');
	$portalid	  =extrae($iframe,"flashvars.portalid =",'"','"');
	$sprache	  =extrae($iframe,"flashvars.sprache =",'"','"');
	$auth		    =extrae($iframe,"flashvars.auth =",'"','"');
	$timestamp	=extrae($iframe,"flashvars.timestamp =",'"','"');
	$v5ident	  =extrae($iframe,"flashvars.v5ident =",'"','"');
	
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
	
	$m3u8 =str_replace(array("/z/","manifest.f4m"),array("/i/","master.m3u8"),$f4m);
	
	mpeg3u($m3u8,"http");
?>
