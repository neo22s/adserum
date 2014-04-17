<style type="text/css"> 
	body{
        background-color:transparent;
    }
	ul{
	list-style:none; 
	width:240px;
	height:240px; 
	padding:1px;
	}
	a.title {
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 10pt; 
	font-weight: bold; 
	color: #F98505;
	} 
	a.url {
	text-decoration:none;
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 9pt;  
	color: #F1AF1A;
	margin-top:100px;  
	} 
	a:link {text-decoration:none;}
	a:visited {text-decoration:none;} 
	a:hover {text-decoration:underline;}   
	a:active {text-decoration:underline;}
	li{
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 9pt;  
	color: #575756;
	margin-bottom:10px;
	margin-left:5px;
	}
	li.first{
	margin-top: 3px;
	}
	.footer{
    position:fixed;
    left:-5px;
    bottom: 0px;
    margin:0 0 0 5px;
    }
</style>


<ul>
<?
$i = 0;
foreach($ads as $ad):?>
	<li<?=($i===0)?' class="first"':''?> >
		<a class="title" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
			<?=substr($ad->title,0,$adformat->title_size)?>
		</a><br>
        <?=substr($ad->description,0,$adformat->description_size)?><br>
        <?=substr($ad->description2,0,$adformat->description2_size)?><br>
        <a class="url" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
        	<?=substr($ad->display_url,0,$adformat->url_size)?>
        </a>
	</li>
<?
$i++;
endforeach?>
	<li class="footer">
		<a target="_blank" href="http://adserum.com/?utm_campaign=<?=date('Y-m-d')?>&utm_medium=banner&utm_source=adserum&a=<?=$affiliate?>">
			<img src="<?=Core::config('common.api_url')?>images/adserum_bottom.png">
		</a>
	</li>
</ul>