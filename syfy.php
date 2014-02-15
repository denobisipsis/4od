// Syfy->m3u8

  $video	=extrae($contenth,'<meta property="og:video" content=','"','"');
	
	$video	=explode("/p/",explode("&",$video)[0])[1];
	
	if (strpos($video,"/swf/select/"))
		{
		$v1=explode("/",explode("/swf/select/",$video)[0])[1];
		
		$v2=explode("/swf/select/",$video)[1];
		
		$video=str_replace($v1,$v2,$video);
		
		$video=explode("/swf/select/",$video)[0];		
		}

//		GET M3U8
		
	$smil   ="http://link.theplatform.com/s/$video?mbr=true&manifest=m3u&format=smil";
