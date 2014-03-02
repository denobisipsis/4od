<? 
// Ceska Televiza

$headers = array(		 	          
		          "User-Agent: 		Mozilla/5.0 (Windows NT 6.2; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0",
		          "Accept: 		text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
		          "Connection: 		keep-alive",
			  "Accept-Language: 	es-ES,es;q=0.8,de;q=0.6,en;q=0.4",
		          "Accept-Encoding: 	"
		        );
	
	$proxy=getproxyonline("CZ","","");
	
	$base="http://www.ceskatelevize.cz";
	
	if (substr($topenlace,-1)!="/") $topenlace.="/";
	
	$contenth=curl_proxy($topenlace,$proxy[0],"",$headers,1,"",0);

	$title=infobase($contenth);
	
	$videoplayerconfig=trim(extrae($contenth,"CT_VideoPlayer.config.ajaxUrl","= '","'"));
			
	if (strpos($contenth,"getPlaylistUrl"))
		$playlist=json_decode(extrae($contenth,'getPlaylistUrl','([',']'));
	else
		{				
		if (strpos($contenth,"Location:"))
			{
			$embed=$base.trim(extrae($contenth,'Location',':','Content-'));
			}
			
		$cookie=getcookie($contenth,"PHPSESSID").";".getcookie($contenth,"BIGipServerwx-squid").";".getcookie($contenth,"BIGipServerwx.ct24.cache").";".getcookie($contenth,"BIGipServerwx.beta");
	
		if ($embed=="")
			{			
			if (strpos($contenth,'void(q='))
				{
				$player=extrae($contenth,"void(q=","'","'");
				$post="cmd=getVideoPlayerUrl&q=".urlencode($player)."&autoStart=true";
				$call=$base.$videoplayerconfig;
				
				foreach ($proxy as $prox)
				     	{						
					$embed=curl_proxy($call,$prox,"","",0,$post);
					$embed=json_decode($embed);
				
					if (!$embed->failure) break;	
					}
					
				if (!$embed->videoPlayerUrl) die("mal");
				
				$embed=str_replace(array("autoStart=false","amp;"),"",$embed->videoPlayerUrl);
					
				}
			else if (strpos($contenth,'<iframe'))
				$embed=str_replace(array("autoStart=false","amp;"),"",extrae($contenth,'<iframe','src="','"'));	
			else    die("no embed");
			}		
				
		if (!strpos($embed,"ttp://")) $embed=$base.$embed;
						
		$iframe=curl_proxy(str_replace(" ","%20",$embed),$prox,"",$headers,1,"",0);
		
		$playlist=json_decode(extrae($iframe,'getPlaylistUrl','([',']'));
		}
	
	$post="";
	
	if ($playlist->type)
		$post=     urlencode("playlist[0][type]")."=".urlencode($playlist->type);
	if ($playlist->id)
		$post.="&".urlencode("playlist[0][id]")."=".urlencode($playlist->id);
	if ($playlist->startTime)
		$post.="&".urlencode("playlist[0][startTime]")."=".urlencode($playlist->startTime);
	if ($playlist->stopTime)
		$post.="&".urlencode("playlist[0][stopTime]")."=".urlencode($playlist->stopTime);
		
	$post.="&".urlencode("requestUrl")."=".urlencode("/ivysilani/embed/iFramePlayer.php").
	$post.="&".urlencode("requestSource")."=".urlencode("iVysilani");

	$call=$base."/ivysilani/ajax/get-playlist-url";
	
	foreach ($proxy as $prox)
		{	
		$smil=curl_proxy($call,$prox,$embed,$headers,0,$post);
		$smil=json_decode($smil);
		if ($smil->url) break;
		}
	
	if (!$smil->url) die("no json");
		
	$smil=urldecode($smil->url);
	
	$links=curl_proxy($smil,$prox,$topenlace,$headers);
	$links=simplexml_load_string($links);
	
	$items =$links->smilRoot->body->switchItem;

	foreach ($items as $path)
		{		
		if (!strpos($path->attributes()->id,"AD"))
			{
			$server=$path->attributes()->base;
			
			$base=explode(".",explode("/",$server)[2])[0];
			
			$server=str_replace($base,"wcdn101",$server);
			
			foreach ($path->video as $link)
				{
				$attr  =$link->attributes();
				
				if ($attr->label!="AD")
					{
					$bitrate=$attr->{"system-bitrate"};
					
					echo "<br>".$label." ".$bitrate."<br>";
					
					echo rtmp(base64_encode($server.$attr->src),"ceska/$title",base64_encode($topenlace));			
					}
				}
			}
		}		
?>
