<style type="text/css"> 
    body{
        background-color:transparent;
    }
    ul{
    list-style:none; 
    padding:1px;
    }
    a.title {
    font-family: Arial, Helvetica, sans-serif; 
    font-size: 11pt; 
    font-weight: bold; 
    color: #F98505;
    margin-bottom: 5px;
    } 
    a.url {
    text-decoration:none;
    font-family: Arial, Helvetica, sans-serif; 
    font-size: 10pt;  
    color: #F1AF1A;
    } 
    a.desc {
    text-decoration:none;
    color: #575756;
    } 
    a:link {text-decoration:none;}
    a:visited {text-decoration:none;} 
    a:hover {text-decoration:underline;}   
    a:active {text-decoration:underline;}
    li{
    font-family: Arial, Helvetica, sans-serif; 
    font-size: 9pt;  
    color: #575756;
    }
    li.first{
    position: relative;
    top: -17px;
    }
    .footer{
    position:fixed;
    left:-5px;
    bottom: 0px;
    margin:0 0 0 5px;
    }
</style>

<div class="container">
<ul>
<?
$i = 0;
foreach($ads as $ad):?>
    <li<?=($i===0)?' class="first"':''?> >
        <a class="title" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
            <?=substr($ad->title,0,$adformat->title_size)?>
        </a>
        <br>
        <a class="desc" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
        <?=substr($ad->description,0,$adformat->description_size)?><br>
        <?=substr($ad->description2,0,$adformat->description2_size)?>
        </a>
        <a class="url" target="_blank" href="<?=$hits[$ad->id_ad]['url']?>">
            <?=substr($ad->display_url,0,$adformat->url_size)?>
        </a>
    </li>
<?
$i++;
endforeach?>
</ul>
<a class="footer" target="_blank" href="http://adserum.com/?utm_campaign=<?=date('Y-m-d')?>&utm_medium=banner&utm_source=adserum&a=<?=$affiliate?>">
    <img src="<?=Core::config('common.api_url')?>images/adserum_bottom.png">
</a>
</div>