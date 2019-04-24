<?
namespace models;
class selection extends \table{
	protected $relations=array('films'=>'films_selection');
	
	
	
	public function add($data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя подборки пустое');
			return false;
		}
		if(trim($data['crop_name'])==''){
			\msg::set('error','Краткое имя подборки пустое');
			return false;
		}
		core()->load('translit');
		$data['url']=translit($data['name']);
		if($img=\image::get('img',true)){
			if($img->imageType!='JPG'){
				\msg::set('error','Изображение должно быть JPG');
				return false;
			}
			$img->name=$data['url'].".jpg";
			
		}
		$id=$this->insert($data);
		if($img){
			$img->moveTo("/uploads/selection/$id/");
			$img->resize($img->cropPath,config('cat_img_w'),config('cat_img_h'));
		}
		\msg::set('success','Подборка добавлена');	
		return true;
	}
	
	public function change($id,$data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя подборки пустое');
			return false;
		}
		if(trim($data['crop_name'])==''){
			\msg::set('error','Краткое имя подборки пустое');
			return false;
		}
		if($img=\image::get('img',true)){
			if($img->imageType!='JPG'){
				\msg::set('error','Изображение должно быть JPG');
				return false;
			}
			$obj=$this->select('url')->get($id);
			if($obj['url']){
				$img->name=$obj['url'].".jpg";
				$img->moveTo("/uploads/selection/$id/");
				$img->resize($img->cropPath,config('cat_img_w'),config('cat_img_h'));
			}
		}
		
		$id=$this->update($id,$data);
		\msg::set('success','Подборка изменена');	
		return true;
	}
	public function delete($id){
		parent::delete($id);
		\dir::delete("/uploads/selection/$id/");
		\msg::set('success','Подборка удалена');	
		return true;
	}
	
	
}
?>