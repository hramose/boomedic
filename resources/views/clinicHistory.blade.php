@extends('adminlte::page')

@section('title', 'Boomedic')
@section('content_header')

@stop
@section('content')


<div class="box">
  	<div class="box-header with-border">
	    <h3 class="box-title">Historia Clínica</h3>
  	</div>
  	<div class="box-body">
  <div class="container" id="myWizard">
  
   <h3>Bootstrap Wizard</h3>
  
   <hr>
  
   <div class="progress">
     <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 20%;">
       Step 1 of 5
     </div>
   </div>
  
   <div class="navbar">
      <div class="navbar-inner">
            <ul class="nav nav-pills">
               <li class="active"><a href="#step1" data-toggle="tab" data-step="1">Step 1</a></li>
               <li><a href="#step2" data-toggle="tab" data-step="2">Step 2</a></li>
               <li><a href="#step3" data-toggle="tab" data-step="3">Step 3</a></li>
               <li><a href="#step4" data-toggle="tab" data-step="4">Step 4</a></li>
               <li><a href="#step5" data-toggle="tab" data-step="5">Step 5</a></li>
            </ul>
      </div>
   </div>
   <div class="tab-content">
      <div class="tab-pane fade in active" id="step1">
         
        <div class="well"> 
          
            <label>Security Question 1</label>
            <select class="form-control input-lg">
              <option value="What was the name of your first pet?">What was the name of your first pet?</option>
              <option value="Where did you first attend school?">Where did you first attend school?</option>
              <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
              <option value="What is your favorite car model?">What is your favorite car model?</option>
            </select>
            <br>
            <label>Enter Response</label>
            <input class="form-control input-lg">
            
        </div>

         <a class="btn btn-default btn-lg next" href="#">Continue</a>
      </div>
      <div class="tab-pane fade" id="step2">
         <div class="well"> 
          
            <label>Security Question 2</label>
            <select class="form-control  input-lg">
              <option value="What was the name of your first pet?">What was the name of your first pet?</option>
              <option selected="" value="Where did you first attend school?">Where did you first attend school?</option>
              <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
              <option value="What is your favorite car model?">What is your favorite car model?</option>
            </select>
            <br>
            <label>Enter Response</label>
            <input class="form-control  input-lg">
            
         </div>
         <a class="btn btn-default next" href="#">Continue</a>
      </div>
      <div class="tab-pane fade" id="step3">
        <div class="well"> <h2>Step 3</h2> Add another step here..</div>
         <a class="btn btn-default next" href="#">Continue</a>
      </div>
      <div class="tab-pane fade" id="step4">
        <div class="well"> <h2>Step 4</h2> Add another almost done step here..</div>
         <a class="btn btn-default next" href="#">Continue</a>
      </div>
      <div class="tab-pane fade" id="step5">
        <div class="well"> <h2>Step 5</h2> You're Done!</div>
         <a class="btn btn-success first" href="#">Start over</a>
      </div>
   </div>
  
   <hr>
  
   <a href="http://www.bootply.com/wj9gWh8ulj">Edit on Bootply</a>
  
   <hr>
  
</div>
            </div>
        </div>

				<script>
					$('.next').click(function(){

					  var nextId = $(this).parents('.tab-pane').next().attr("id");
					  $('[href=#'+nextId+']').tab('show');
					  return false;
					  
					})

					$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					  
					  //update progress
					  var step = $(e.target).data('step');
					  var percent = (parseInt(step) / 5) * 100;
					  
					  $('.progress-bar').css({width: percent + '%'});
					  $('.progress-bar').text("Step " + step + " of 5");
					  
					  //e.relatedTarget // previous tab
					  
					})

					$('.first').click(function(){

					  $('#myWizard a:first').tab('show')

					})
				</script>

@stop