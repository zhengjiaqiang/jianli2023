<?php 
final class Html{
	public static function text($id,$val=null,$attr=null){
		$h = '<input type="text" ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr('value',$val).self::attr($attr).' />');
		echo $h;
	}
	public static function number($id,$val=null,$attr=null){
		$h = '<input type="number" ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr('value',$val).self::attr($attr).' />');
		echo $h;
	}
	public static function textarea($id,$val=null,$attr=null){
		$h = '<textarea ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr($attr).' >');
		if(!empty($val)){
			$h .= htmlspecialchars($val);
		}
		$h .= '</textarea>';
		echo $h;
	}
	public static function password($id,$val=null,$attr=null){
		$h = '<input type="password" ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr('value',$val).self::attr($attr).' />');
		echo $h;
	}
	public static function hidden($id,$val=null,$attr=null){
		$h = '<input type="hidden" ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr('value',$val).self::attr($attr).' />');
		echo $h;
	}
	public static function label($for=null,$text=null,$attr=null){
		$h = '<label '. self::attr('for',$for) . self::attr($attr);
		$h .= '>';
		if(empty($text)){
			$h .=(empty($for) ? '' : $for);
		}else{
			$h .= $text;
		}
		$h .= '</label>';
		echo $h;
	}

	public static function checkbox($id,$c=false,$attr=null){
		$h = '<input type="checkbox" ' . self::attr('id',$id) . self::attr('name',$id);
		if($c){
			$h .= self::attr('checked','checked');
		}
		$h .= (self::attr($attr).' />');
		echo $h;
	}
	//<input type="radio" checked="checked" name="Sex" value="male" />
	public static function radio($id,$c=false,$attr=null){
		$h = '<input type="radio" ' . self::attr('name',$id);
		if($c){
			$h .= self::attr('checked','checked');
		}
		$h .= (self::attr($attr).' />');
		echo $h;
	}
	public static function submit($id,$val=null,$attr=null){
		$h = '<input type="submit" ' . self::attr('id',$id) . self::attr('name',$id);
		$h .= (self::attr('value',$val).self::attr($attr).' />');
		echo $h;
	}
	
	public static function select($id,$list,$val=null,$attr=null,$def=null,$htmlfirst=null,$defValue=-1,$noshow=null){
		$h ='<select ' . self::attr('name',$id) . self::attr('id',$id);
		$h .= (self::attr($attr).'>');

		if(! empty($htmlfirst)){
			if(is_array($htmlfirst)){
				foreach($htmlfirst as $n=>$v){
					$h .= '<option'.' value="'.trim($n).'">'.$v.'</option>';
				}
			}else{
				$h .= empty($htmlfirst)? : '<option value="'.$defValue.'">'.$htmlfirst.'</option>';
			}
		}
		if(!empty($def)){
			foreach($def as $n=>$v){
				$h .= '<option'.(($n==$val && ! ($val ===null)) ? ' selected="selected"':'').' value="'.trim($n).'">'.$v.'</option>';
			}
		}
		if(!empty($list)){
			foreach($list as $n=>$v){
				if(!empty($noshow)){
					if(in_array($n,$noshow)) continue;
				}
				$h .= '<option'.(($n==$val && ! ($val ===null)) ? ' selected="selected"':'').' value="'.trim($n).'">'.$v.'</option>';
			}
		}
		$h .='</select>';
		echo $h;
	}
	
	private static function attr($name,$val=null){
		if(!empty($name) && is_array($name)){
			$h ='';
			foreach($name as $n=>$v){
				$h .= ($n.'="'.$v.'" ');
			}
			return $h;
		}
		if($val==null){
			return '';
		}
		return ($name.'="'.$val.'" ');
	}
	
}