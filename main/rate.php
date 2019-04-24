<?
class rate{
	private $table;
	private $masstime=3600;//время ограниччивающее массовое голосование
	private $onetime=5592000;// время снимающее запрет на голосование той же записи.
	private $maxvoice=10;// максимальное количество голосов
	
	public function __construct($table) {
      $this->table=$table;
	}
	
	public function voice($targ_id,$rate){
		$countVoice=(int)$_COOKIE["countVoice"];
		$voiced=unserialize($_COOKIE["voiced"]);
		if(!is_array($voiced))$voiced=array();
		
		$targ_id=(int)$targ_id;
		if(array_search($targ_id,$voiced)===false && $countVoice<=$this->maxvoice && $targ_id && ($rate===0 || $rate===1 || $rate==='0' || $rate==='1')){
			
			if(db()->getBySql("select `targ_id` from `".$this->table."` where `targ_id`='$2' and `ip`=INET_ATON('$1') and `timestamp`>'".date('Y-m-d H:i:s',(time()-$this->onetime))."'",array('$1'=>$_SERVER["REMOTE_ADDR"],'$2'=>$targ_id))){
				return false;
			}
			
			$data=db()->getBySql("select COUNT(`targ_id`) as `count` from `".$this->table."` where `ip`=INET_ATON('$1') and `timestamp`>'".date('Y-m-d H:i:s',(time()-$this->masstime))."'",array('$1'=>$_SERVER["REMOTE_ADDR"]));
			if($data['count']>$this->maxvoice)return false;
			
			db()->query("insert into `".$this->table."`(`ip`,`targ_id`,`rate`) values(INET_ATON('$1'),'$2','$3')",array('$1'=>$_SERVER["REMOTE_ADDR"],'$2'=>$targ_id,'$3'=>$rate));
			setcookie('countVoice',($countVoice+1),(time()+$this->masstime),'/');
			$voiced[]=$targ_id;
			setcookie('voiced',serialize($voiced),(time()+$this->onetime),'/');
		}else{
			return false;
		}
		
		return true;
	}
	
	public function getRate($targ_id){
		$targ_id=(int)$targ_id;
		$rate=0;
		if($targ_id){
			$data=db()->getBySql("select COUNT(`targ_id`)as `count`, SUM(`rate`) as `rate`  from `".$this->table."` where `targ_id`='$2'",array('$2'=>$targ_id));
			if($data){
				if(!$data['rate'])return (-1)*$data['count'];
				$rate=$data['rate']/$data['count'];
				if($rate==1 && $data['count']>1)$rate+=($data['count']-1)/1000;
			}
		}	
		return $rate;
	}
	
}