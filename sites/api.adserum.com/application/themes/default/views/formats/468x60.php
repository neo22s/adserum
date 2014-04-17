	<style type="text/css"> 
		body{
        background-color:transparent;
    }
		.content{
		list-style:none; 
		width:450px;
		height:50px;
		padding:3px; 
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
		font-size: 8pt;  
		color: #F1AF1A;  
		} 
		a:link {text-decoration:none;}
		a:visited {text-decoration:none;} 
		a:hover {text-decoration:underline;}   
		a:active {text-decoration:underline;}
		
		.ad{
		font-family: Arial, Helvetica, sans-serif; 
		font-size: 8pt;  
		color: #575756;
		}
		
		.left{
		float:left;
		width:205px;
		margin-right:5px;
		margin-left:5px;
		}
		.center{
		float:left;
		width:205px;
		margin-right:10px;
		}
		
		.right: {
		float:right;	
		width:20px;
		}
    
    </style>

<div class="content">

<?
$i = 0;
foreach($ads as $ad):?>
	<div class="ad <?=($i===count($ads))?'left':'center'?>" >
		<a class="title" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
			<?=substr($ad->title,0,$adformat->title_size)?>
		</a><br>
        <?=substr($ad->description,0,$adformat->description_size)?><br>
        <?=substr($ad->description2,0,$adformat->description2_size)?><br>
        <a class="url" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
        	<?=substr($ad->display_url,0,$adformat->url_size)?>
        </a>
	</div>
<?
$i++;
endforeach?>
	<div class="right">
		<a target="_blank" href="http://adserum.com/?utm_campaign=<?=date('Y-m-d')?>&utm_medium=banner&utm_source=adserum&a=<?=$affiliate?>">
			<img src="<?=Core::config('common.api_url')?>images/adserum_v_2.png">
		</a>
	</div>
</div>