function rtpa($url,$window)
	{	
	wb_set_text(wb_get_control($window, 803),"Rtpa");
	
	$totallinks=array();
	$contenth=curl_proxy(utf8_encode($url));
	
	$idactual =trim(extrae($contenth,'id_actual','=',';'));
	
	$idbase   =extrae($contenth,'url:"api/muestra_json_vod.php?id_programa','=','&');
	
	if ((int)($idbase)==0) {wb_message_box($window, "No id",$A3MENSAJE);return;}
	
	$json	    =json_decode(curl_proxy("http://www.rtpa.es/api/muestra_json_vod.php?id_programa=$idbase"));
	
	$mp4base="http://rtpa.ondemand.flumotion.com/rtpa/ondemand/vod/$link";
	if (strpos($idactual,"id_generado"))
		{
		$idvod=_explode("data[",_explode("]",$idactual,0),1);
		$item=$json->VOD[$idvod];	
		}
	else	foreach ($json->VOD as $item) {if ($idactual==$item->id_generado) break;}
						
	if ($item->url=='') 			              $link=$mp4base.$item->id_programacion.'_1.mp4';	
	else if (!strpos($item->url,"ttp://")) 	$link=$mp4base.$item->url.'_1.mp4';		
	else 					                          $link=$item->url;
								
	$img	    =$item->url_imagen;	
	epiinfo($window,$item->titulo);
		
	$totallinks[0]["tipo"]="_";
	$totallinks[0]["link"]=$link;
	$totallinks[0]["mode"]="MP4";
			
	$totallinks[0]["img"] =$img;
		
	return $totallinks;
	}
