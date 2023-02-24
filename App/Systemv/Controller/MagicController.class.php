<?php
// +----------------------------------------------------------------------
// | I DO
// +----------------------------------------------------------------------
// | Copyright (c) 2016 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Systemv\Controller;
use Systemv\Common\BaseController;
class MagicController extends BaseController{
	public function key(){
		$key=I('get.key');
		echo  $this->calcRsaVal($key);
		die();
		
	}
	/**生成加密字符串算法 */
	private function calcRsaVal($key){
		$string = '623224023783266990398957217534766630';
		$map = '{"0":48,"1":49,"2":50,"3":51,"4":52,"5":53,"6":54,"7":55,"8":56,"9":57,"a":97,"b":98,"c":99,"d":100,"e":101,"f":102,"g":103,"h":104,"i":105,"j":106,"k":107,"l":108,"m":109,"n":110,"o":111,"p":112,"q":113,"r":114,"s":115,"t":116,"u":117,"v":118,"w":119,"x":120,"y":121,"z":122,"-":45}';
		$Rsa=json_decode($map,true);
		$keyarray=str_split($key);
		$stringarray=str_split($string);
		$array=[];
		foreach($keyarray as $k=>$v){
			$m=$Rsa[$v];
			$n=$Rsa[$stringarray[$k]];
			if($m==null || $n==null) return null;
			$array[]=$n > 100 ? (($m >> 2) | $n) : (($m << 2) & $n);
		}
		$nstr='';
		foreach($array as $v) 	$nstr.=$v;
		$narr=str_split($nstr);
		$result = [];
		for($i=(count($narr)-1);$i>=2;$i--) 	$result[$i]=$narr[$i];
		return implode('',$result);
	}
}