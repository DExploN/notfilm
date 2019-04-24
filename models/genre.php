<?
namespace models;
class genre extends \table{
	protected $relations=array('films'=>'films_genre');
	
	
	
	public function add($data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя категории пустое');
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
		if($data['anime']==1){
			$data['anime']=1;
		}else{
			$data['anime']=0;
		}
		
		$id=$this->insert($data);
		if($img){
			$img->moveTo("/uploads/genre/$id/");
			$img->resize($img->cropPath,config('cat_img_w'),config('cat_img_h'));
		}
		\msg::set('success','Категория добавлена');		
		return true;
	}
	
	public function change($id,$data){
		if(trim($data['name'])==''){
			\msg::set('error','Имя категории пустое');
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
				$img->moveTo("/uploads/genre/$id/");
				$img->resize($img->cropPath,config('cat_img_w'),config('cat_img_h'));
			}
		}
		
		if($data['anime']==1){
			$data['anime']=1;
		}else{
			$data['anime']=0;
		}
		
		$id=$this->update($id,$data);
		\msg::set('success','Категория изменена');		
		return true;
	}
	
	public function getBySelection($id){
		$id=(int)$id;
		return db()->getAllBySql("select `id`,`name` from `genre` where `id` in (select distinct(`genre_id`) from `films_genre` left join (select `films_id` from `films_selection` where `selection_id`='$1')`t` on `t`.`films_id`=`films_genre`.`films_id` where `t`.`films_id`>0)",array('$1'=>$id));
	}
	public function delete($id){
		parent::delete($id);
		\dir::delete("/uploads/genre/$id/");
		\msg::set('success','Категория удалена');	
	}
	
	
}
?>