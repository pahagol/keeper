<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Баннеры для партнерской системы</title>
	<link rel="stylesheet" type="text/css" href="/css/banner.css"/>
</head>
<body>
<div align="center">
<? if (!empty($banner_flash)):?>	
	<? foreach ($banner_flash as $banner): ?>
		<!--a rel="nofollow" target="_blank" href="<?=$ref_link_flash?>" style="position:relative; z-index:1000; display:block; width:<?= $banner->width ?>px; height:<?= $banner->height ?>px;"></a-->
		<div style="padding-top: 40px">
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"
					width="<?=$banner->width?>"
					height="<?=$banner->height?>">
				<param name="movie" value="http://2056.aratog.com/banners/<?=$banner->file_name?>" quality="high"/>
				<param name="flashVars" value="link1=<?=$ref_link_flash?>">
				<param name="allowScriptAccess" value="always"/>
				<embed src="http://2056.aratog.com/banners/<?=$banner->file_name?>"
					allowScriptAccess="always"
					quality="high"
					flashvars="link1=<?=$ref_link_flash?>"
					pluginspage="http://www.macromedia.com/go/getflashplayer"
					type="application/x-shockwave-flash"
					width="<?=$banner->width?>"
					height="<?=$banner->height?>">
				</embed>
			</object>
			<br />
			<textarea style="color: #b3a377; background-color: #000000" cols="60" rows="4">&lt;object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="<?=$banner->width?>" height="<?=$banner->height?>"&gt; &lt;param name="movie" value="http://2056.aratog.com/banners/<?=$banner->file_name?>" quality="high"/&gt; &lt;param name="flashVars" value="link1=<?=$ref_link_flash?>"&gt; &lt;param name="allowScriptAccess" value="always"/&gt; &lt;embed src="http://2056.aratog.com/banners/<?=$banner->file_name?>" allowScriptAccess="always" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" flashvars="link1=<?=$ref_link_flash?>" width="<?=$banner->width?>" height="<?=$banner->height?>"&gt; &lt;/embed&gt; &lt;/object&gt;
			</textarea>
		</div>
	<? endforeach; ?>
<? endif; ?>	
<? if (!empty($banner_image)):?>	
	<? foreach ($banner_image as $banner): ?>
		<div style="padding-top: 40px">
			<a href="<?=$ref_link_image?>" target="_blank" rel="nofollow"><img src="http://2056.aratog.com/banners/<?=$banner->file_name?>" alt=""/></a>
			<br />
			<textarea style="color: #b3a377; background-color: #000000" cols="60" rows="4">&lt;a href="<?=$ref_link_image?>"&gt; &lt;img src="http://2056.aratog.com/banners/<?=$banner->file_name?>"&gt;&lt;/a&gt;
			</textarea>
		</div>
	<? endforeach;?>
<? endif; ?>	
</div>
</body>
</html>