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


<!-- MESSAGE -->
<?php 
    $message = Session::get('message');
    Session::forget('message'); 
?>
@if(isset($message))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{$message}}
    </div>
@endif



<br><div class="row">
    <div class="col-lg-12">
  <h4><font color='green'>Sales Order : {{$order->order_number}} &emsp;| &emsp;&emsp;Client: {{$order->client->name}}  &emsp; |&emsp; Date: {{$order->date}} &emsp; |&emsp; Status: {{$order->status}} </font> </h4>

<hr>
</div>  
</div>
 
<div class="row">
    <div class="col-lg-12">
        <form class="form-inline" action="{{URL::to('erpReports/receipt/'.$order->id)}}" method="POST">
            <div class="form-group">
                <select name="driver" id="driver" class="form-control input-sm" style="width: 250px;" required>
                    <option value="">Please select a driver</option>
                    @if(count($driver) > 0)
                    @foreach($driver as $driver)
                        <option>{{ $driver->surname }} {{ $driver->first_name }}</option>
                    @endforeach
                    @endif
                </select> &emsp;
            </div>
            <div class="form-group">

                @if($order->payment_type === "credit")
                    <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-file fa-fw"></i> Delivery Note/Invoice</button>
                @else
                    <button type="submit" class="btn btn-primary input-sm"><i class="fa fa-file fa-fw"></i> Delivery Note/Invoice</button>
                @endif
                 <!--<a href="{{URL::to('erpReports/kenya/'.$order->id)}}" class="lnk btn btn-primary btn-sm" target="_blank">
            <span class="glyphicon glyphicon-file"></span>&nbsp; Generate Invoice
        </a>-->
          <a class="btn btn-info btn-sm" href="{{ URL::to('lease')}}">Lease item</a>
        
        
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif

    <table class="table table-condensed table-bordered table-hover" >

    <thead>
        <!-- <th><input type="checkbox" id="select_all" value=""></th> -->
        <th>Item</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Discount</th>
        <!-- <th>Amount</th>
        <th>Duration</th> -->
        <th>Total Amount</th>
        <th>Total Payable</th>
       
    </thead>

    <tbody>

   
        <?php $total = 0; ?>

        @foreach($order->erporderitems as $orderitem)

            <?php
            $discount_amount = $orderitem['discount_amount'];            
            $total_amount = $orderitem['price'] * $orderitem['quantity'];
            $amount = $orderitem['price'] * $orderitem['quantity']-$discount_amount * $orderitem['quantity'];
            /*$total_amount = $amount * $orderitem['duration'];*/
            $total = $total + $amount;
            ?>
        <tr>
            <!-- <td><input type="checkbox" class="checkbox" name="{{$orderitem->item->id}}" value=""></td> -->
            <td>{{$orderitem->item->item_make}}</td>
            <td>{{$orderitem['quantity']}}</td>
            <td>{{asMoney($orderitem['price'])}}</td>
            <td id="tid">{{asMoney($discount_amount * $orderitem['quantity'])}}</td>
            <!-- <td>{{$amount}}</td>
            <td>{{$orderitem['duration']}}</td> -->
            <td>{{asMoney($amount) }}</td>
            <td>{{asMoney($amount-$orders->discount_amount)}}</td>
            
        </tr>

        @endforeach

        <!-- <tr>
           <td></td>
            <td></td>
            <td></td>
            <td></td> -->
            <!-- <td></td>
            <td><strong>Grand Total</strong></td>
            <td><strong>{{asMoney($total)}}</strong></td>
          
        </tr> --> 
    </tbody>
        
    </table>
        

  </div>

</div>

@stop