@extends('layouts.app')
@section('content')
<style>
.activated{
  width:500px;
  padding:6px;
}
input[type=small-text-box] {
    max-width: 100%;
    border: 0;
    background-color: #f9f9f9;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-right: 5px;
    padding-left: 10px;
    border-radius: 5px;
    font-size: 16px;
    border: 2px solid #fafafa;
    margin-top: 5px;
    margin-bottom: 16px;
    display: block;
    box-shadow: inset 1px 1px 3px rgb(0 0 0 / 20%);
    position: relative;
    transition: .2s;
  }
.medium-text-box{
    max-width: 100%;
    border: 0;
    background-color: #f9f9f9;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-right: 5px;
    padding-left: 10px;
    border-radius: 5px;
    font-size: 16px;
    border: 2px solid #fafafa;
    margin-top: 5px;
    margin-bottom: 16px;
    display: block;
    box-shadow: inset 1px 1px 3px rgb(0 0 0 / 20%);
    position: relative;
    transition: .2s;
    width:345px;
  }

  label {
    display: block;
    font-weight: 600;
}
.authentication-select{
  display: block;
    font-size: 16px;
    font-family: sans-serif;
    font-weight: 700;
    color: #444;
    line-height: 1.3;
    padding: 0.6em 1.4em 0.5em 0.8em;
    width: 25%;
    box-sizing: border-box;
    margin: 0;
    border: 1px solid #aaa;
    box-shadow: 0 1px 0 1px rgb(0 0 0 / 4%);
    border-radius: 0.5em;
  background-color: #f2f2f2;
    border: 0;
    font-family: 'Open Sans';
    font-size: 14px;
    padding-top: 12px;
    padding-bottom: 12px;
    margin-top: 7px;
    margin-bottom: 16px;
    font-weight: 600;
    background-repeat: no-repeat,repeat;
    background-position: right 0.7em top 50%,0 0;
    background-size: -28.35em auto,100%;
    box-shadow: 1px 2px 1px 0 rgb(0 0 0 / 15%);
}
textarea {
    max-width: 100%;
    width: 780px;
    background-color: #f9f9f9;
    margin-top: 15px;
    padding:50px;
}

</style>
<div class="wrapper">
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Payment authorization
</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
       <div class="card">
        <div class="card-header">
        
      <div id="routerView">
      <div id="registrationRoutePage"></div><p class="header-description"></p><h5><span>1 - Enter the <b>externalRef and deviceName</code></span></h5>

      <p><label>External reference</label></p><input id="externalRef" type="small-text-box" aria-label="External reference"><br><h5><span>2 - Click the <b>Get available devices</b> button and select an authentication device</span></h5><div>
      <button class="btn btn-primary button" id="activate_device" onclick='GetAvailableDevice()'>Get available devices</button></div><p><label>Available Devices</label></p><select data-v-ab286c5a="" text="Choose a device to Authenticate" aria-label="Available devices" id="authentication-select" class="authentication-select"><option data-v-ab286c5a="" value="">  </option></select>
     
      <br>
      <h5><strong>3 - Optionally enter additional information to be passed back to the app</strong></h5>
      <p> <label> <input id="push_payload" value="" aria-label="Activation code" type="checkbox" style="padding-right:3px;margin-right:7px;">Specify push payload</label></p>

      <input type="text" id="push_payment_msg" class="medium-text-box" placeholder="Push message payload" style="display:none;">

      <h5><span>4 - Enter payment information and click the <b>Authorize button</b></span></h5>
       <p> <label> <input id="authorize_payload" value="" aria-label="Activation code" type="checkbox" style="padding-right:3px;margin-right:7px;">Payment Informaion</label></p>
  
      <input type="text" id="payment_information" class="medium-text-box" placeholder="payment informaion" style="display:none;">
      <button class="btn btn-primary button" id="activate_device" onclick='doAuthenticate()'>Authorize</button>

       <h5><span>5 -  Push notification is displayed on the mobile device. Carry out authentication Authentication response</b></span></h5>
      <textarea id="payment_response"></textarea>
      </div>
        </div>
       
        <div class="card-body">
           <p><strong>
           </strong>
          </p>
        </div>
      </div>
    </section>
  </div>
  
</div>
@endsection
<!-- ./wrapper -->
@push('scripts')

<script>

function GetAvailableDevice(){
  var client_id = document.getElementById("externalRef").value;
  var base_url = '{{ getenv('APP_URL') }}';
        $.ajax({
             type:'GET',
             url:base_url+'/client/'+client_id+'',
             dataType:'json',
             success:function(data){
                  $("#authentication-select").attr('disabled', false);
              if (data.status=='success') {
                $("#authentication-select").empty().append('<option value=' + data.data + ' selected>' + data.data + '</option>');
              }
              else{
                 $("#authentication-select").empty();
              }
             }
        });
  }

function doAuthenticate(){
  var client_id = document.getElementById("externalRef").value;
  var device = document.getElementById("authentication-select").value;
  var payment_information = btoa(document.getElementById("payment_information").value);

  var base_url = '{{ getenv('APP_URL') }}';
        $.ajax({
             type:'POST',
            // url:base_url+'/api/oauth2/auth',
             url:base_url+'/authorizepayment',
             dataType:'json',
             data:{
                 "_token": "{{ csrf_token() }}",
                 "client_id":client_id,
                 "device_id":device,
                 "preContextTitle":payment_information,
             },
             success:function(data){
                  $("#authentication-select").attr('disabled', false);
              if (data.status=='success') {
                $("#authentication-select").empty().append('<option value=' + data.data + ' selected>' + data.data + '</option>');
              }
              else if (data.status=='error'){
                 document.getElementById("payment_response").innerHTML = JSON.stringify(data);
              }
              else{
                 $("#authentication-select").empty();
              }

             }
        });
  }


$("#push_payload").click(function(){
    if($('#push_payload').is(":checked"))   
      $("#push_payment_msg").show();
    else
     $("#push_payment_msg").hide();
});

$("#authorize_payload").click(function(){
    if($('#authorize_payload').is(":checked"))   
      $("#payment_information").show();
    else
     $("#payment_information").hide();
});

</script>
@endpush