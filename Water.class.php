<?php
//$dst:大图路径
//$src:水印图片路径
//$pos:水印的位置 0,1,2,3,4,5,6,7,8
//$tm:水印的透明度
class Water{	
	function addWaterImg($dst,$src,$pos=8,$tm=90){
		//1.创建画布
		$dst_arr = $this->getinfo($dst);//调用下面的getinfo函数,获取本图片的资源和大小组成的数组
		$dst_res = $dst_arr['res'];//获取该图片的资源
		$src_arr = $this->getinfo($src);
		$src_res = $src_arr['res'];
		
		//2.合并图片
		switch($pos){//判断水印存在的位置
			case 0://0位置
				$x = 0;//左上角的x为0
				$y = 0;//左上角的y为0
				break;
			case 1://上部的中间
				$x = $dst_arr['width']/2-$src_arr['width']/2;//大图宽度的一半减去小图宽度的一半
				$y = 0;
				break;
			case 2://右上角
				$x = $dst_arr['width']-$src_arr['width'];//大图宽度减去小图宽度就是小图要放的坐标x的位置
				$y = 0;
				break;
			case 3://中间左侧
				$x = 0;
				$y = $dst_arr['height']/2-$src_arr['height']/2;//大图高度的一半减去小图高度的一半
				break;
			case 4:
				$x = $dst_arr['width']/2-$src_arr['width']/2;
				$y = $dst_arr['height']/2-$src_arr['height']/2;
				break;
			case 5:
				$x = $dst_arr['width']-$src_arr['width'];
				$y = $dst_arr['height']/2-$src_arr['height']/2;
				break;
			case 6:
				$x = 0;
				$y = $dst_arr['height']-$src_arr['height'];
				break;
			case 7:
				$x = $dst_arr['width']/2-$src_arr['width']/2;
				$y = $dst_arr['height']-$src_arr['height'];
				break;
			case 8:
			default:
				$x = $dst_arr['width']-$src_arr['width'];
				$y = $dst_arr['height']-$src_arr['height'];
				echo $x;
				echo $y;
				break;
		}
		
		imagecopymerge($dst_res,$src_res,$x,$y,0,0,$src_arr['width'],$src_arr['height'],$tm);//合并两个图片
		//4.输出图像
		imagepng($dst_res,$dst);
		//5.销毁资源
		imagedestroy($dst_res);
		imagedestroy($src_res);
	}
	/*
	*$dst_path 原图路径
	*文字水印内容
	*字体颜色
	*水印位置
	*保存目录
	*字体大小
	*/
	function addWaterText($imgSrc,$markText,$TextColor,$markPos,$markedfilename="",$fontSize = 30){
	    $fontType = $_SERVER['DOCUMENT_ROOT']."/static/admin/fonts/simsun.ttc";
	    $srcInfo  = @getimagesize($imgSrc);
	    $srcImg_w = $srcInfo[0];
	    $srcImg_h = $srcInfo[1];
	    $markText = mb_convert_encoding($markText, "html-entities","utf-8" );
	    switch ($srcInfo[2]) 
	    { 
	        case 1: 
	            $srcim =imagecreatefromgif($imgSrc);
	            break; 
	        case 2: 
	            $srcim =imagecreatefromjpeg($imgSrc); 
	            break; 
	        case 3: 
	            $srcim =imagecreatefrompng($imgSrc); 
	            break; 
	        default: 
	            echo "不支持的图片文件类型"; 
	            return false;
	    }
	    {
	        
	        if(!empty($markText))
	        {
	            if(!file_exists($fontType))
	            {
	               echo '字体文件不存在'; 
	               return false;
	            }
	        }
	        else {
	            echo '没有水印文字'; 
	            return false;
	        }
	        //此函数返回一个含有8个单元的数组表示文本外框的四个角，索引值含义：0代表左下角 X 位置，1代表坐下角 Y 位置，
	        //2代表右下角 X 位置，3代表右下角 Y 位置，4代表右上角 X 位置，5代表右上角 Y 位置，6代表左上角 X 位置，7代表左上角 Y 位置
	        $box = @imagettfbbox($fontSize, 0, $fontType,$markText);
	        //var_dump($box);exit;
	        $logow = max($box[2], $box[4]) - min($box[0], $box[6]);
	        $logoh = max($box[1], $box[3]) - min($box[5], $box[7]);
	    }
	        
	     
	    switch($markPos)
	    {
	        case 1:
	            $x = 5;
	            $y = $fontSize;
	            break;
	        case 2:
	            $x = ($srcImg_w - $logow) / 2;
	            $y = $fontSize;
	            break;
	        case 3:
	            $x = $srcImg_w - $logow - 5;
	            $y = $fontSize;
	            break;
	        case 4:
	            $x = $fontSize;
	            $y = ($srcImg_h - $logoh) / 2;
	            break;
	        case 5:
	            $x = ($srcImg_w - $logow) / 2;
	            $y = ($srcImg_h - $logoh) / 2;
	            break;
	        case 6:
	            $x = $srcImg_w - $logow - 5;
	            $y = ($srcImg_h - $logoh) / 2;
	            break;
	        case 7:
	            $x = $fontSize;
	            $y = $srcImg_h - $logoh - 5;
	            break;
	        case 8:
	            $x = ($srcImg_w - $logow) / 2;
	            $y = $srcImg_h - $logoh - 5;
	            break;
	        case 9:
	            $x = $srcImg_w - $logow - 5;
	            $y = $srcImg_h - $logoh -5;
	            break;
	        default: 
	            $x = rand ( 0, ($srcImg_w - $logow) );
	            $y = rand ( 0, ($srcImg_h - $logoh) );
	    }
	        
	    $dst_img = @imagecreatetruecolor($srcImg_w, $srcImg_h);
	        
	    imagecopy ( $dst_img, $srcim, 0, 0, 0, 0, $srcImg_w, $srcImg_h);
	        
	        
	    {
	        $rgb = explode(',', $TextColor);
	      
	        $color = imagecolorallocate($dst_img, intval($rgb[0]), intval($rgb[1]), intval($rgb[2]));
	        imagettftext($dst_img, $fontSize, 0, $x, $y, $color, $fontType,$markText);
	    }
	    
	    switch ($srcInfo[2]) 
	    { 
	        case 1:
	            imagegif($dst_img,$markedfilename); 
	            break; 
	        case 2: 
	            imagejpeg($dst_img,$markedfilename); 
	            break; 
	        case 3: 
	            imagepng($dst_img,$markedfilename); 
	            break;
	        default: 
	            echo ("不支持的水印图片文件类型"); 
	            return false; 
	    }
	    imagedestroy($dst_img);
	    imagedestroy($srcim);
	}
	function getinfo($path){
		$image = getimagesize($path);//获取图片大小和类型
		switch($image['mime']){//判断图片的mime类型
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/jpg':
				$img = imagecreatefromjpeg($path);
				$ext="jpg";
				break;
			case 'image/gif':
				$img = imagecreatefromgif($path);
				$ext="gif";
				break;
			case 'image/png':
				$img = imagecreatefrompng($path);
				$ext="png";
				break;
			case 'image/wbmp':
			default:
				$img = imagecreatefromwbmp($path);
				$ext="wbmp";
				break;
		}
		
		$info['width'] = $image[0];//将图片的宽度存入到数组中
		$info['height'] = $image[1];//将图片的高度存入到数组中
		$info['res'] = $img;//将图片的资源存入到数组中
		$info['ext'] = $ext;//图片的扩展名
		return $info;//返回这个数组(图片的宽度,高度和资源)
	}
}