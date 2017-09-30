<?php

function asMoney($value) {
  return number_format($value, 2);
}

?>

@extends('layouts.erp')

{{ HTML::script('media/js/jquery.js') }}

<script type="text/javascript">
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>

@section('content')

<br><div class="row">
	<div class="col-lg-12">
  <h4><font color='green'>Purchase Order : {{$order->order_number}} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Client: {{$order->client->name}}  &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; Date: {{$order->date}} &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; Status: {{$order->status}} </font> </h4>

<hr>
</div>	
</div>

@if (Session::get('notice'))
            <div class="alert alert-info">{{ Session::get('notice') }}</div>
        @endif


<div class="row">
    <div class="col-lg-12">
    @if($order->reviewed_by > 0 && $order->authorized_by > 0)
    <a href="{{URL::to('erpReports/PurchaseOrder/'.$order->id)}}" class="btn btn-primary"> Generate Purchase Order</a>
    <a href="{{URL::to('erpquotations/mail')}}" class="btn btn-success"> Mail Purchase Order</a>
    @endif
    @if(!Entrust::can('authorize_purchase_order') || !Entrust::can('review_purchase_order'))
    @if($order->prepared_by == null || $order->prepared_by == "")
    <a href="{{URL::to('submitpurchaseorder/'.$order->id)}}" class="btn btn-success"> Submit For Approval</a>
    @endif
    @endif
    @if(Entrust::can('review_purchase_order'))
    @if($order->reviewed_by == null || $order->reviewed_by == "")
    <a href="{{URL::to('reviewpurchaseorder/'.$order->id)}}" class="btn btn-warning"> Review Purchase Order</a> 
    @endif
    @endif
    @if(Entrust::can('authorize_purchase_order'))
    @if(($order->reviewed_by != null || $order->reviewed_by != "") && $order->authorized_by == null || $order->authorized_by == "")
    <a href="{{URL::to('authorizepurchaseorder/'.$order->id)}}" class="btn btn-danger"> Authorize Purchase Order</a> 
    @endif
    @endif
    
    </div>
</div>

<div class="row">
	<div class="col-lg-12">

    <hr>
		
		 @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif

    <table class="table table-condensed table-bordered table-hover" >

    <thead>
        
        <th>Item</th>
        <th>Quantity</th>
        <th>Price</th>
        <!-- <th>Amount</th>
        <th>Duration</th> -->
        <th>Total Amount</th>
        <th>Grand Total</th>
       
    </thead>

    <tbody>

   
        <?php $total = 0; ?>
        @foreach($order->erporderitems as $orderitem)

            <?php

            $amount = $orderitem['price'] * $orderitem['quantity'];
            /*$total_amount = $amount * $orderitem['duration'];*/
            $total = $total + $amount;
            ?>
        <tr>
            
            <td>{{$orderitem->item->item_make}}</td>
            <td>{{$orderitem['quantity']}}</td>
            <td>{{asMoney($orderitem['price'])}}</td>
            <!-- <td>{{$amount}}</td>
            <td>{{$orderitem['duration']}}</td> -->
            <td>{{asMoney($amount) }}</td>
            <td></td>
            
        </tr>

        @endforeach

        <tr>
           <td></td>
            <td></td>
            <!-- <td></td>
            <td></td> -->
            <td></td>
            <td><strong><font color = "red">Grand Total</strong></font></td>
            <td><strong><font color = "red">{{asMoney($total)}}</strong></font></td>
          
        </tr>
    </tbody>
        
    </table>
		

  </div>

</div>




@stop