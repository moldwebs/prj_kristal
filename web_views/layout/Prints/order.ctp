<?php Configure::write('Config.tpl_language', 'rom');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
/*If product is more then 6 line */
<?php if(count($order['ModOrderItem']['item']) > 6):?>
 .clientpart {page-break-before: always;}
<?php endif;?>

div {float:left; display:block; color:#000; font-size:12px;}
body { width:755px;  font-family: "Times New Roman", Times, serif; font-size:13px;}
.depozit {width:755px;}
.infocomanda{width:755px;}
.infoproduse {width:755px;}
.info_date {width:554px; font-size:14px;}
.info_operator {width:200px; text-align:right; font-size:14px;}

.comanda_nr { font-size:16px;font-weight:bold;}

.mytable { text-align:center; border-collapse: collapse; font-size:12px;}
.mytable tr{}
.mytable tr td{ padding:2px;}
.mytable th { background-color:#D3D3D3; padding: 10px 0 10px 0;}


.mytable tr th {text-align:center;}


.infoproduse  {width:755px; padding-top:20px;}

.mytable tr td {text-align:center;}
.mytable tr td:nth-child(3){ text-align:left;}
.tr.totalr td:nth-child(1){ text-align:right; padding-right:10px; }





.depozit_detaliat{width:755px; padding-top:30px;}
.bloc_dep { width:250px;}
.bloc_dep1 { width:130px;padding-right:10px;}
.bloc_dep:nth-child(1) { }
.totalr { }
tr.totalr td:nth-child(1) {
    padding-right: 10px;
    text-align: right;
}

.subtotal { width:264px; text-align:center; border-collapse: collapse; font-size:12px;}
.subtotal tr td:nth-child(1){  width:174px;}
.subtotal tr td:nth-child(2){  width:90px;}

.bloc_dep3{ width:354px;}

.bloc_dep3 tr td:nth-child(1) {
    padding-right: 10px;
    text-align: right;
}
.confirmare_client_info {width:755px; padding-top:40px;}
.signature_client {width:755px; padding-top:30px;}
.sign_date {width:200px;}
.sign_date_cumparator {width:200px; padding-left:300px;}
.sing_date_title{width:90px; text-align: right; margin-right:10px;}
.sing_date_complite{width:100px; border-bottom:solid 1px #1E1E1E; }
.clientpart {width:755px; padding-top:60px;}
.title_client { width:755px; border-top:1px dashed #000000; font-size:16px; padding-top:15px; font-weight:bold;}
.sign_date_final {width:200px; padding-left:500px;}


.precizie tr td:nth-child(1){ text-align:left;}
.precizie tr td:nth-child(2){ text-align:center;}
.precizie tr td:nth-child(3){ text-align:center;}
.precizie tr td:nth-child(4){ text-align:center;}
.precizie tr.totalr td:nth-child(1){ text-align:right;}
.revers-deposit {text-transform: lowercase;  text-transform: capitalize;}
</style>




</head>

<body>

<div class="depozit">	
	<div class="infocomanda">
    <div class="info_date">
    Primită: <?php e(date("H:i d.m.Y", strtotime($order['ModOrder']['created'])))?>
    </div>
    <div class="info_operator">
    Comanda nr. <span class="comanda_nr"><?php e($order['ModOrder']['id'])?> </span>
    </div>
    </div>
    

    <div class="infoproduse">

    <table width="755" border="1" class="mytable precizie" >
  <tbody>
    <tr>

      <th colspan="2" width="545">Marfa</th>
      <th width="30">Buc.</th>
      <th width="80">Preț</th>
      <th width="90">Total</th>
    </tr>

    <?php foreach($order['ModOrderItem']['item'] as $_item):?>
    <?php $total += $_item['price_total'];?>
    <tr>
      <td><?php e($_item['title'])?> ~ <?php e($_item['code'])?></td>
      <td><?php if(!empty($vendors[$_item['data']['vendor']['vendor_id']])):?><?php e($vendors[$_item['data']['vendor']['vendor_id']])?> ~ <?php e($_item['data']['vendor']['vendor_code'])?><?php endif;?></td>
      <td><?php e($_item['quantity'])?></td>
      <td><?php e($_item['price'])?> <?php e($_item['currency'])?></td>
      <td style="text-align: right;"><?php e($_item['price_total'])?> <?php e($_item['currency'])?></td>      
    </tr>
    <?php endforeach;?>
    <tr class="totalr">
      <td colspan="4"><strong>Total:</strong></td>
      <td style="text-align: right;"><?php e($total)?> <?php e($order['ModOrder']['currency'])?></td>      
    </tr>
    
    <?php foreach($order['ModOrderItem'] as $_tp => $_items) if($_tp != 'item' && $_tp != 'payment') foreach($_items as $_item):?>
    <?php if(empty($_item['title'])) continue;?>
    <tr class="totalr">
      <td colspan="4"><strong><?php ___e($_item['title'])?>:</strong></td>
      <td style="text-align: right;"><?php e(!empty($_item['price']) ? $_item['price'] : '0')?> <?php e($_item['currency'])?></td>      
    </tr>
    <?php endforeach;?>

    
    <tr class="totalr">
      <td colspan="4"><strong>Spre achitare:</strong></td>
      <td style="text-align: right;"><strong><?php e($order['ModOrder']['price'])?> <?php e($order['ModOrder']['currency'])?></strong></td>      
    </tr>
        
  </tbody>
</table>


    
    
    
    </div>

    
    <div class="signature_client">

   
     <div class="sign_date_final">
   <div class="sing_date_title">Șofer &nbsp; &nbsp;<br> 
     <em>Водитель &nbsp; &nbsp;</em></div>
   <div class="sing_date_complite"> &nbsp;</div>
   </div>
   
   </div>
   
    
 <div class="clientpart">
 	<div class="title_client">&nbsp;</div>


	<div class="infocomanda">
    <div class="info_date">
    Primită: <?php e(date("H:i d.m.Y", strtotime($order['ModOrder']['created'])))?>
    </div>
    <div class="info_operator">
    Comanda nr. <span class="comanda_nr"><?php e($order['ModOrder']['id'])?> </span>
    </div>
    </div>
    
    
    
    <div class="infoproduse">
    
    <table width="755" border="1" class="mytable precizie" >
  <tbody>
    <tr>

      <th width="545">Marfa</th>
      <th width="30">Buc.</th>
      <th width="80">Preț</th>
      <th width="90">Total</th>
    </tr>

    <?php foreach($order['ModOrderItem']['item'] as $_item):?>
    <tr>
      <td><?php e($_item['title'])?> ~ <?php e($_item['code'])?></td>
      <td><?php e($_item['quantity'])?></td>
      <td><?php e($_item['price'])?> <?php e($_item['currency'])?></td>
      <td style="text-align: right;"><?php e($_item['price_total'])?> <?php e($_item['currency'])?></td>      
    </tr>
    <?php endforeach;?>
    <tr class="totalr">
      <td colspan="3"><strong>Total:</strong></td>
      <td style="text-align: right;"><?php e($total)?> <?php e($order['ModOrder']['currency'])?></td>      
    </tr>
    
    <?php foreach($order['ModOrderItem'] as $_tp => $_items) if($_tp != 'item' && $_tp != 'payment') foreach($_items as $_item):?>
    <?php if(empty($_item['title'])) continue;?>
    <tr class="totalr">
      <td colspan="3"><strong><?php ___e($_item['title'])?>:</strong></td>
      <td style="text-align: right;"><?php e(!empty($_item['price']) ? $_item['price'] : '0')?> <?php e($_item['currency'])?></td>      
    </tr>
    <?php endforeach;?>

    
            <tr class="totalr">
      <td colspan="3"><strong>Spre achitare:</strong></td>
      <td style="text-align: right;"><strong><?php e($order['ModOrder']['price'])?> <?php e($order['ModOrder']['currency'])?></strong></td>      
    </tr>
        
  </tbody>
</table>

    
    
    
    </div>

    <div class="confirmare_client_info">Confirm primirea produsului și nu am nici o pbiecție. Produsul primit este de calitate și deține toate accesoriile conform datelor tehnice.<br>
   Подтверждаю полкчение товара и не имею никаких претензий. Принятый товар обладает ассортиментом - еомплектующих, соответсвующим требованиям.</div>
   
   <div class="signature_client">
   <div class="sign_date">
   <div class="sing_date_title">Data &nbsp; &nbsp;<br> <em>Дата &nbsp; &nbsp;</em></div>
   <div class="sing_date_complite"> &nbsp;</div>
   </div>
   
   
     <div class="sign_date_cumparator">
   <div class="sing_date_title">Cumpărător &nbsp; &nbsp;<br> 
     <em>Покупатель &nbsp; &nbsp;</em></div>
   <div class="sing_date_complite"> &nbsp;</div>
   </div>
   
   </div>
 

 
 </div>	 
 
 


</div>



</body>
</html>
