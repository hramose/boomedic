@extends('adminlte::page')

@section('title', 'Boomedic')

@section('content_header')

@stop

@section('content')
	
	<style type="text/css">
		.info-box .progress .progress-bar {
		    background: #160404;
		}
	</style>

	<div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="selectWorkPlace" > Citas del día: </label>
            	<select class="form-control" name="selectWorkPlace" id="selectWorkPlace" size="1">
            	  	@foreach($workplaces as $workplace)

            	  		<option value="{{$workplace->id}}">{{$workplace->workplace}}</option>

            	  	@endforeach
            	</select>
          	</div>
          	@foreach($medAppoints as $cite)
	          	<div class="info-box ">
			        <span class="info-box-icon">
			        	<i class="ion ion-ios-heart-outline"></i>
			        </span>

			        <div class="info-box-content">
			          	<span class="info-box-text">{{$cite->firstname}} {{$cite->lastname}}</span>
			          	<span class="info-box-number">{{$cite->age}} de edad</span>
			          	<div class="progress">
			            	<div class="progress-bar" style="width: 20%"></div>
			          	</div>
			          	<span class="progress-description">
			                20% Increase in 30 Days
			            </span>
			        </div>
			    </div>
		    @endforeach
		</div>
		<div class="col-sm-8">

		</div>
	</div>

    <script type="text/javascript">
    	$(function () {
            $('select').select2({
                width: "100%",
            });
         });
    </script>
	
@stop