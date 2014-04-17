<div class="row">
<div class="span12">

<h1><?=__('Total Earnings')?> <?=Controller_Publisher::money_cpm($total_hits)?></h1>  


<p><?=__('You get paid $')?><?=core::config('common.publisher_CPM')?> CPM. <?=__('Your publisher id is')?> <code><?=Auth::instance()->get_user()->id_user?></code></p>

<?if (!Valid::email(Auth::instance()->get_user()->paypal_email)):?>
    <p><a href="<?=Route::url('default',array('controller'=>'profile','action'=>'edit'))?>" ><?=__('Please set your paypal account here')?>.</a></p>
<?endif?>

<?if ($last_date_paid!==NULL):?>

    <p><?=__('Earnings since last payment')?>  <b><?=Controller_Publisher::money_cpm($hits_last_payment)?></b> (<?=Date::unix2mysql($last_date_paid)?>)</p>  

    <?if (Valid::email(Auth::instance()->get_user()->paypal_email) 
        AND Controller_Publisher::money_cpm($hits_last_payment,FALSE)>=core::config('common.publisher_min_pay')):?>
    <form action="" novalidate="novalidate" method="post">
            <input type="hidden" name="nothing">
            <button type="submit" class="btn btn-primary">$ <?=__('Request Payment')?></button>
            <?=Model_Content::text('affiliate.payments')?>
    </form>
    <?endif?>

    <h3><?=__('Payments')?></h3>

    <table class="table table-bordered table-striped span6">
        <thead>
            <tr>
                <th><?=__('Date Paid')?></th>
                <th><?=__('Amount')?></th>
            </tr>                       
        </thead>
        <tbody>
        <?foreach($orders as $order):?>
            <tr>
                <td><?=$order->date_paid?></td>
                <td>$<?=$order->amount?></td>
            </tr>   
        <?endforeach?>
        </tbody>
    </table>

<?else:?>
    <?if (Valid::email(Auth::instance()->get_user()->paypal_email) 
        AND Controller_Publisher::money_cpm($total_hits,FALSE)>=core::config('common.publisher_min_pay')):?>
    <form action="" novalidate="novalidate" method="post">
            <input type="hidden" name="nothing">
            <button type="submit" class="btn btn-primary">$ <?=__('Request Payment')?></button>
            <?=Model_Content::text('affiliate.payments')?>
    </form>
    <?endif?>
<?endif?>

</div>
</div>