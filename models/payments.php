<?
namespace models;
class payments extends \table{
	protected $relations=array();
	
	public function order($user,$money,$text){
		if(!trim($text)){
				\msg::set('error','В примечании укажите кошелек webmoney (WMR) или номер телефона и оператора связи - для оплаты');
				core()->backpage();
				return false;
		}
		if($money>=config('min_pay') ){
			if($this->lastpaystatus()){
				$data['user_login']=$user;
				$data['money']=$money;
				$data['text']=$text;
				$this->insert($data);
				\msg::set('success','Заявка на выплату оформлена. Выплата будет проверена в течении 7 дней.');
				return true;
			}else{
				\msg::set('error','У вас уже есть заказанные выплаты');
				return false;
			}
		}else{
			\msg::set('error','Заказанная выплата меньше минимальной суммы выплаты');
			return false;
		}			
	}
	
	
	public function lastpaystatus(){
		
		$lastPay=$this->getByFields(array('user_login'=>user()->info('login'),'status'=>'0'));
			if($lastPay['id']){
				return false;
			}else{
				return true;
		}
	}
	
	public function success($id,$data){
		
		
		if($data['status']==1){
			$pay=$this->select('user_login')->get($id);
			
			$users = new \models\users();
			$user= $users->select('id,balance')->getByFields(array('login'=>$pay['user_login']));
			
			
			if($data['paid']>$user['balance'])$data['paid']=$user['balance'];
			
			$user['balance']=$user['balance']-$data['paid'];
			
			$users->update($user['id'],array('balance'=>$user['balance']));
			\msg::set('success','Заявку на выплатату одобрена');
		}else{
			\msg::set('success','Заявку на выплатату отклонена');
		}
		
		$this->update($id,$data);
	}
	
}
?>