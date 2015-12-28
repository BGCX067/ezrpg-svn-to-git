<?php
/*************************************/
/*           ezRPG script            */
/*         Modified by Zeggy         */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/
/**
 * ***************************
 *	Bar Generator
 * 	By Mathew Collins
 * 	mathew.collins@gmail.com	http://onovia.com
 * 
 *     This class is available free of charge for personal or non-profit works. If
 *     you are using it in a commercial setting, please contact the author for license
 *     information.
 *     This class is provided as is, without guarantee. You are free to modify
 *     and redistribute this code, provided that the original copyright remain in-tact.
 * 
 *******************************/

class barGen
{
	
	function setWidth($value)
	{
		$this->bar_w = $value;
	}
	
	function setHeight($value)
	{
		$this->bar_h = $value;
	}
	
	function setFontSize($value)
	{
		$this->fontSize = $value;
	}
	
	function setFileType($value)
	{
		$this->fileType = $value;
	}
	
	function setFillColor($value)
	{
		// Bar Colors
		switch ($value){
			case 'green':
				$this->fill_color 	= imagecolorallocate($this->bar, 50, 170, 0);
				break;
			case 'red':
				$this->fill_color	= imagecolorallocate($this->bar, 170, 0, 0);
				break;
			case 'yellow':
				$this->fill_color = imagecolorallocate($this->bar, 220, 220, 0);
				break;
			case 'blue':
				$this->fill_color	= imagecolorallocate($this->bar, 0, 0, 200);
				break;
			default:
				$this->fill_color = imagecolorallocate($this->bar, 170, 170, 170);
		}
	}
	
	function setBackColor()
	{
		$this->backColor = imagecolorallocate($this->bar, 200, 0, 0);
	}
	
	function setData($max, $value)
	{
		$this->max = $max;
		$this->value = $value;
		
		$this->dataPercent = intval($this->value / $this->max * 100);
		
		$this->text = $this->value . " / " . $this->max;
	}
	
	function setTitle($prefix)
	{
		$this->text = $prefix . $this->text;
	}
	
	function makeBar()
	{
		$this->bar = imagecreate($this->bar_w, $this->bar_h);
		$this->setBackColor();
	}
	
	function generateBar()
	{		
		$white 	= imagecolorallocate($this->bar, 255, 255, 255);
		$grey 	= imagecolorallocate($this->bar, 180, 180, 180);
		$black 	= imagecolorallocate($this->bar, 0, 0, 0);
		
		// Background
		imagefill($this->bar, 0, 0, $this->backColor);
		// Fill
		$this->barPercent = $this->bar_w / 100 * $this->dataPercent;
		imagefilledrectangle($this->bar, 0, 0, $this->barPercent, $this->bar_h, $this->fill_color);
		// Border
		imagerectangle($this->bar, 0, 0, $this->bar_w - 1, $this->bar_h - 1, $grey);
		// Text
		imagestring($this->bar, $this->fontSize, round(($this->bar_w/2)-((strlen($this->text)*imagefontwidth($this->fontSize))/2), 1), round(($this->bar_h/2)-(imagefontheight($this->fontSize)/2)), $this->text, $white);
		
		
		// Output, check for various image type support
		if ((imagetypes() & IMG_PNG) && $this->fileType == "PNG")
		{
			header('Content-type: image/png');
			imagepng($this->bar, "", 9);
		}
		else if ((imagetypes() & IMG_GIF) && $this->fileType == "GIF")
		{
			header ("Content-type: image/gif");
			imagegif($this->bar);
		}
		else if ((imagetypes() & IMG_JPG) && $this->fileType == "JPG")
		{
			header("Content-type: image/jpeg");
			imagejpeg($this->bar);
		}
		else
		{
			return 0;
		}
		
		imagedestroy($this->bar);
	}
}
?>