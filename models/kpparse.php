<?
namespace models;
class kpparse {
	
	public function getInfoByKinopoisk($url,$proxy=NULL){
		//$url='http://baskino.club/films/multfilmy/11299-golovolomka.html';
		$url=trim($url);
		$host= parse_url($url);
		core()->load('simple_html_dom');
		if(preg_match("/^https\:\/\/www\.kinopoisk\.ru\/film\/([0-9a-zA-Z\-]+)\/$/ui",$url,$matches)){
				
				$genreLis=array(
					"биография"=>1,
					"боевик"=>2,
					"вестерн"=>3,
					"военный"=>4,
					"детектив"=>5,
					"документальный"=>7,
					"драма"=>8,
					"история"=>9,
					"комедия"=>10,
					"мелодрама"=>12,
					"криминал"=>11,
					"мультфильм"=>13,
					"мюзикл"=>14,
					"приключения"=>15,
					"семейный"=>16,
					"спорт"=>17,
					"триллер"=>18,
					"ужасы"=>19,
					"фантастика"=>20,
					"фэнтези"=>21,
				);
				$content=self::getContByUrl($url,$proxy);
				$content = iconv('windows-1251', 'UTF-8',	$content);
				$html = str_get_html($content);
				
				$data['name']=trim(str_replace("— отзывы — КиноПоиск","",$html->find("title", 0)->plaintext));
				$data['name']=trim(str_replace("— отзывы о фильмах — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— смотреть онлайн, отзывы о фильмах — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— смотреть онлайн, отзывы — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— трейлеры, даты премьер — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— смотреть онлайн, трейлеры, даты премьер — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— смотреть онлайн, всё о фильме — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— всё о фильме — КиноПоиск","",$data['name']));
				$data['name']=trim(str_replace("— смотреть онлайн","",$data['name']));
				
				$data['name']=trim(str_replace("— КиноПоиск","",$data['name']));
				
				$data['name']=trim(preg_replace('/\([0-9]{4}\)$/i','',$data['name']));
				
				$data['ori']=trim($html->find("span[itemprop=alternativeHeadline]", 0)->plaintext); 
				$data['image']=trim($html->find("div[class=film-img-box]",0)->find("img[itemprop=image]",0)->getAttribute('src'));
				$data['genre']=array();
				$data['country']=array();
				$data['kpid']=array_pop(explode("-",$matches[1]));
				
				if($html->find("a[data-subscription=FREE]", 0)){
					$data['online']=true;
				}else{
					$data['online']=false;
				}
				
				
				foreach($html->find("table[class=info]",0)->find("td[class=type]") as $td){
					if($td->plaintext =='год'){
						$data['year']=$td->nextSibling()->find("a",0)->plaintext;
					}
					if($td->plaintext =='премьера (РФ)'){
						$dateRf=$td->nextSibling()->find('div[class="prem_ical"]',0)->getAttribute('data-date-premier-start-link');
						$data['dateRf']=preg_replace("/^([0-9]{4})([0-9]{2})([0-9]{2})$/ui","$1-$2-$3",$dateRf);
					}
					if($td->plaintext =='жанр'){
						if($td->nextSibling()->find("span[itemprop=genre]",0)!=NULL){
							if(count($genres=$td->nextSibling()->find("span[itemprop=genre]",0)->find("a"))){
								foreach($genres as $genre){
									if($id=$genreLis[$genre->plaintext]){
										$data['genre'][]=array('id'=>$id,'name'=>$genre->plaintext);
									}
								}
							}
						}
					}
					if($td->plaintext =='режиссер'){
						if($td->nextSibling()->plaintext != '-'){
							$data['director']=$td->nextSibling()->plaintext;
						}
					}
					
					if($td->plaintext =='страна'){
						if($td->nextSibling()->find("a",0)!=NULL){
							if(count($countrys=$td->nextSibling()->find("a"))){
								foreach($countrys as $country){
									preg_match("/lists\/m_act\%5Bcountry\%5D\/(\d+)\//i",$country->getAttribute('href'),$matches);
									if($matches[1]){
										$data['country'][]=array('id'=>$matches[1],'name'=>$country->plaintext);
									}	
								}
							}
						}
					}
				}
				
				$i=1;
				$actors=array();
				if(count($lis=$html->find("li[itemprop=actors]"))){
					foreach($lis as $li){
						if($li->plaintext!="..."){
							$actors[]=$li->plaintext;
						}
						if($i==8)break;
						$i++;
					}
				}

					
				$data['actors']=implode(", ",$actors);
				return $data;
				
			}elseif($host['host']=='baskino.club'){
				$content=self::getContByUrl($url,$proxy);
				$genreLis=array(
					"Биографические"=>1,
					"Боевики"=>2,
					"Вестерны"=>3,
					"Военные"=>4,
					"Детективы"=>5,
					"Драмы"=>8,
					"Исторические"=>9,
					"Комедии"=>10,
					"Мелодрамы"=>12,
					"Криминальные"=>11,
					"Мультфильмы"=>13,
					"Мюзикл"=>14,
					"Приключенческие"=>15,
					"Семейные"=>16,
					"Спортивные"=>17,
					"Триллеры"=>18,
					"Ужасы"=>19,
					"Фантастические"=>20,
				);
				
				$html = str_get_html($content);
				
				$data=array();
				
				$data['name']=trim($html->find("td[itemprop=name]", 0)->plaintext); 
				$data['ori']=trim($html->find("td[itemprop=alternativeHeadline]", 0)->plaintext); 
				$data['image']=trim($html->find("img[itemprop=image]",0)->getAttribute('src'));
				$data['genre']=array();
				$data['actors']=array();
				
				$i=1;
				foreach($html->find("span[itemprop=actor]")as $td){
					$data['actors'][]=trim($td->find("span[itemprop=name]",0)->plaintext);
					if($i==8)break;
					$i++;
				}
				$data['actors']=implode(", ",$data['actors']);
				
				foreach($html->find("a[itemprop=genre]")as $td){
					if($id=$genreLis[trim($td->plaintext)]){
						$data['genre'][]=array('id'=>$id,'name'=>trim($td->plaintext));
					}
				}
				
				
				foreach($html->find("div[class=info] table td.l") as $td){
					
					if($td->plaintext =='Год:'){
						
						$data['year']=$td->nextSibling()->find("a",0)->plaintext;
					}
				}
				
				$data['links']=($tmp=$html->find("#basplayer_hd iframe", 0))?trim($tmp->getAttribute('src')):'';
				$data['links']=str_replace("staticdn.nl","moonwalk.cc",$data['links']);
				$data['trailer']=($tmp=$html->find(".trailer_placeholder iframe", 0))?trim($tmp->getAttribute('src')):'';
				
				
				return $data;
				
			}elseif($host['host']=='kinokrad.net'){
				$content=self::getContByUrl($url,$proxy);
				$genreLis=array(
					"биография"=>1,
					"боевик"=>2,
					"вестерн"=>3,
					"военный"=>4,
					"детектив"=>5,
					"документальный"=>7,
					"драма"=>8,
					"история"=>9,
					"комедия"=>10,
					"мелодрама"=>12,
					"криминал"=>11,
					"мультфильм"=>13,
					"мюзикл"=>14,
					"приключения"=>15,
					"семейный"=>16,
					"спорт"=>17,
					"триллер"=>18,
					"ужасы"=>19,
					"фантастика"=>20,
					"фэнтези"=>21,
				);
				
				$html = str_get_html($content);
				
				$data=array();
				
				$data['name']=trim($html->find("h1", 0)->plaintext);
				
				$data['image']='http://kinokrad.net'.trim($html->find("div.bigposter img", 0)->getAttribute('src'));
				$data['actors']=array();
				$data['genre']=array();
				
				foreach($html->find(".janrfall li span[class=orange]") as $td){
					if(($tmp=trim($td->plaintext))=='Оригинальное название:'){
						$data['ori']=trim(str_replace('Оригинальное название:','',$td->parent()->plaintext));
						continue;
					}
					if(($tmp=trim($td->plaintext))=='Год:'){
						$data['year']=trim(str_replace('Год:','',$td->parent()->plaintext));
						continue;
					}
					
					if(($tmp=trim($td->plaintext))=='Премьера РФ:'){
						$tmp=trim(str_replace('Премьера РФ:','',$td->parent()->plaintext));
						if(preg_match("/.*(\d+)\-(\d+)\-(\d{4}).*/iU",$tmp)){
							$data['dateRf']=preg_replace("/.*(\d+)\-(\d+)\-(\d{4}).*/iU","$3-$2-$1",$tmp);
						}
						continue;
					}
					
					if(($tmp=trim($td->plaintext))=='Жанр:'){
						foreach($td->parent()->find('a') as $val){
							if($id=$genreLis[$val->plaintext]){
								$data['genre'][]=array('id'=>$id,'name'=>$val->plaintext);
							}
						}
						continue;
					}
									
				}
				
				foreach($html->find("div.acttitle")as $td){
					$data['actors'][]=trim($td->plaintext);
					if($i==8)break;
					$i++;
				}
				$data['actors']=implode(", ",$data['actors']);
			
				return $data;
			}elseif($host['host']=='films.imhonet.ru'){
				$content=self::getContByUrl($url,$proxy);
				$genreLis=array(
					"Биография"=>1,
					"Боевики"=>2,
					"Военные фильмы"=>4,
					"Детективы"=>5,
					"Документальные"=>7,
					"Драмы"=>8,
					"Исторические фильмы"=>9,
					"Комедии"=>10,
					"Мелодрамы"=>12,
					"Криминал"=>11,
					"Мультфильмы"=>13,
					"Мюзиклы"=>14,
					"Приключения"=>15,
					"Спорт"=>17,
					"Триллеры"=>18,
					"Ужасы"=>19,
					"Фантастика"=>20,
				);
				
				$html = str_get_html($content);
				$data=array();
				
				$data['name']=trim($html->find("h1", 0)->plaintext); 
				$data['ori']=trim($html->find("span[itemprop=alternativeHeadline]", 0)->plaintext); 
				$data['image']=trim($html->find("img.m-elementdescription-image",0)->getAttribute('src'));
				
				preg_match('/\<span class\=\"m_caption\"\>Год выпуска\: \<\/span\>.*\<span  class\=\"m_value\"\>(\d+)\<\/span\>/iUs',$content,$matches);
				$data['year']=$matches[1];
				
				$data['genre']=array();
				$data['actors']=array();
				
				$i=1;
				foreach($html->find("div[itemprop=actor] span[itemprop=name]")as $td){
					$data['actors'][]=trim($td->plaintext);
					if($i==8)break;
					$i++;
				}
				$data['actors']=implode(", ",$data['actors']);
				
				
				foreach($html->find("span[itemprop=genre]")as $td){
					if($id=$genreLis[trim($td->plaintext)]){
						$data['genre'][]=array('id'=>$id,'name'=>trim($td->plaintext));
					}
				}
				
				return $data;
			}else{
				return array('error'=>1);
			}
	}
	public function getContByUrl($url,$proxy=NULL,$povtor=NULL){
				$ch=curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_TIMEOUT,10);
				//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
				curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/".rand(10000000, 25000000)." Firefox/1.0.7");
				curl_setopt ($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_REFERER, "http://www.kinopoisk.ru/");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				if(stripos($url,"images")===false){
					curl_setopt($ch, CURLOPT_HEADER, true);
				}
				if($proxy){
					curl_setopt($ch, CURLOPT_PROXY, $proxy); 
				}
				
				$headers = array( 
					"Content-type: text/xml;charset=\"utf-8\"", 
					"Accept: text/xml",
					"Accept-Language: ru",
					"Accept-Charset: utf-8",
					"Accept-Encoding: deflate",
					"Accept-Ranges: bytes", 
					"Cache-Control: no-cache", 
				 ); 
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
				$content = curl_exec($ch);
				//$content = iconv('windows-1251', 'UTF-8',	$content);
				//var_dump($url);
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if (($code == 301 || $code == 302) && $povtor===NULL) {
						preg_match('/Location:(.*?)\n/', $content, $matches);
						$newurl = trim(array_pop($matches));
						curl_close ($ch);
						
						return self::getContByUrl("https://www.kinopoisk.ru".$newurl,$proxy,1);
						
					}
				return $content;
		}
}
?>