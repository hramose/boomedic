@extends('adminlte::page')

@section('title', 'Boomedic')

@section('content_header')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jquery.Jcrop.css') }}" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    <script type="text/javascript" src="js/cssrefresh.js"></script>



		<script type="text/javascript">

				Dropzone.options.myAwesomeDropzone = { 
				 
				 // set following configuration
				 	paramName: "file",
				    maxFiles: 1,
				    acceptedFiles: "image/*",
				    addRemoveLinks: true,
				    dictRemoveFile: "Eliminar",
				    dictCancelUpload: "Cancel",
				    dictDefaultMessage: "Arraste y suelte una nueva foto de perfil...",
				     success: function(file, response){
					        //alert(response);
					  $('#loading').modal("show");
					  setTimeout('window.location.reload(true)',8000);
					     	}
				    //autoProcessQueue : false 
				 };
				 var val = "@php echo session()->get('val'); @endphp";
				 		if(val == "true"){
				 		setTimeout(function() {
						    $('#modal').modal("show");
						}, 1000);	
					}
					$('#modal').modal({backdrop: 'static', keyboard: true});
					    
		</script>

@stop

@section('content')


	<br/>



	@if( empty($status) )

		<div class="lockscreen-item" style="margin: 10px 0 30px auto;">
		    <!-- lockscreen image -->
		    <div class="lockscreen-image">
		    	@if($photo == '')
		    	 	<img src="https://s3.amazonaws.com/abiliasf/profile-42914_640.png">
				@else
					<img src="{{ $photo }}" >			
		    	@endif 

		    </div>
		    <!-- /.lockscreen-image -->

		    <!-- lockscreen credentials (contains the form) -->
		    <form class="lockscreen-credentials" action="/user/edit/complete" method="get">
		    	{{ csrf_field() }}
		      	<div class="input-group">
		        	<div class="form-control">{{ $name }}</div>
		        	<input type="hidden" name="id" value="{{ $userId }}">
		        	<div class="input-group-btn">
			          	<button type="submit" class="btn">
			          		<i class="fa fa-pencil text-muted"></i>
			          	</button>
		        	</div>
		      	</div>
		    </form>
		    <!-- /.lockscreen credentials -->

		</div>

	@endif

<div id="modal" class="modal fade" role="dialog" style="width: 100%">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" >
                  <div class="modal-header">   
                    <label for="recorte">Recorte de imagen:</label>
                  </div>
                  <div class="modal-body" >

                        <div align="center">
                   

                           <img src="https://s3.amazonaws.com/abiliasf/{{ $userId }}.jpg" id="target" style="width:350px; height: 350px;">
                    
                           <form enctype="multipart/form-data" action="/user/cropProfile/{{$userId}}" method="post" onsubmit="return checkCoords();">
                           	<input type="hidden" id="x" name="x" />
							<input type="hidden" id="y" name="y" />
							<input type="hidden" id="w" name="w" />
							<input type="hidden" id="h" name="h" /><br/>
	                        <span class="input-group-btn">
	                        <input type="submit" class="btn btn-secondary btn-block btn-flat" value="Guardar"></span>
                          </form>
                       </div>
                     <!--<input id="submit" type="button" value="Buscar" class="map-marker text-muted">-->
                  </div>
                </div>
              </div>
 </div>

  <div class="loading" class="modal fade" role="dialog">
    <div class="ventana">
      <center><h1><i class="fa fa-refresh fa-spin"></i> Cargando ...</h1></center>
    </div>
  </div>


    <div class="box">
	  	<div class="box-header with-border">
		    <h3 class="box-title">Información de usuario</h3>
	    	<!-- /.box-tools -->
	  	</div>
	  	<!-- /.box-header -->
	  	<div class="box-body">
	  		@if( !empty($status) )

		  		@if ($status == "In Progress")
		  			<div class="callout callout-success">
		                <h4>Ya casi estamos listos {{ $firstname }} !!!</h4>

		                <p>Confirma y completa la información que esta debajo</p>
		            </div>
	    		@endif
	    		<div class="row">
	    		<label class="col-sm-2 control-label" style="text-align: right;">Foto de perfil</label>
	    		<div class="col-sm-4" align="center">
	    			@if($photo == '')
		    	 		<img src="https://s3.amazonaws.com/abiliasf/profile-42914_640.png" alt="User Image"  style="width:150px; height: 150px;">
					@else
					<?php 
					  $imagen = getimagesize($photo);    //Sacamos la información
			          $width = $imagen[0];              //Ancho
			          $height = $imagen[1];  

			          if($height > '300' || $width > '300'){
			            $height = $height / 2;
			            $width = $width / 2;
			        }
					 ?>
						<img src="{{ $photo }}" style="width:{{ $width }}px; height: {{ $height }}px;">			
			    	@endif 
	    			
	    		</div>
	    		<div class="col-sm-4" align="center" style="width:230px; height: 230px; "><form enctype="multipart/form-data" action="/user/updateProfile/{{$userId}}" method="post" class="dropzone" id="myAwesomeDropzone"></form></div></div><br/>
	    		<form enctype="multipart/form-data" action="/user/update/{{$userId}}" method="post" class="form-horizontal">
	    			{{ csrf_field() }}

	    			<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
	                    <label for="firstname" class="col-sm-2 control-label">Nombre</label>
	                	<div class="col-sm-10">
	                  		<input type="text" name="firstname" class="form-control" id="firstname" value="{{ $firstname }}">
	                	</div>
	              	</div>

	              	<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
	                    <label for="lastname" class="col-sm-2 control-label">Apellidos</label>
	                	<div class="col-sm-10">
	                  		<input type="text" name="lastname" class="form-control" id="lastname" value="{{ $lastname }}">
	                	</div>
	              	</div>

	              	<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
	                    <label for="email" class="col-sm-2 control-label">Correo electrónico</label>
	                	<div class="col-sm-10">
	                  		<input type="email" name="email" class="form-control" id="email" value="{{ $email }}">
	                	</div>
	              	</div>

	              	<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
	                    <label for="username" class="col-sm-2 control-label">Nombre de usuario</label>
	                	<div class="col-sm-10">
	                  		<input type="email" name="username" class="form-control" id="username" value="{{ $username }}">
	                	</div>
	              	</div>

	              	<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
	                    <label for="age" class="col-sm-2 control-label">Edad</label>
	                	<div class="col-sm-10">
	                  		<input type="text" name="age" class="form-control" id="age" value="{{ $age }}">
	                	</div>
	              	</div>

	              	<div class="callout callout-default" align="right">
		                <b>Información personal</b>
		            </div>

		            <div class="form-group has-feedback {{ $errors->has('occupation') ? 'has-error' : '' }}">
	                    <label for="occupation" class="col-sm-2 control-label">Ocupación</label>
	                	<div class="col-sm-10">
	                  		<input type="text" name="occupation" class="form-control" id="occupation" value="{{ $occupation }}">
	                	</div>
	              	</div>
	              	
	              	<div class="form-group has-feedback {{ $errors->has('gender') ? 'has-error' : '' }}">
	                  <label for="gender" class="col-sm-2 control-label">Genero</label>
	                  <div class="col-sm-10">
		                  <select class="form-control" name="gender">
		                    <option value="female" {{ ($gender == 'female') ? 'selected' : '' }}>Femenino</option>
		                    <option value="male"   {{ ($gender == 'male')   ? 'selected' : '' }}>Masculino</option>
		                  </select>
	                  </div>
	                </div>

	                <div class="form-group has-feedback {{ $errors->has('scholarship') ? 'has-error' : '' }}">
	                    <label for="scholarship" class="col-sm-2 control-label">Escolaridad</label>
	                	<div class="col-sm-10">
	                  		<input type="text" name="scholarship" class="form-control" id="scholarship" value="{{ $scholarship }}">
	                	</div>
	              	</div>

	              	<div class="form-group has-feedback {{ $errors->has('maritalstatus') ? 'has-error' : '' }}">
	                  <label for="maritalstatus" class="col-sm-2 control-label">Estado civil</label>
	                  <div class="col-sm-10">
		                  <select class="form-control" name="maritalstatus">
		                    <option value="single"  {{ ($maritalstatus == 'single') ? 'selected' : '' }}>Soltero</option>
		                    <option value="married" {{ ($maritalstatus == 'married') ? 'selected' : '' }}>Casado</option>
		                  </select>
	                  </div>
	                </div>

	                <div class="form-group">
	                	<label for="mobile" class="col-sm-2 control-label"># Móvil</label>
	                	<div class="col-sm-10">
		                  	<input type="text" name="mobile" id="mobile" value="{{ $mobile }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
	                	</div>
	                	<!-- /.input group -->
	              	</div>

	              	<div class="callout callout-default" align="right">
		                <b>Dirección</b>
		            </div>
		            <div class="form-group">
		            	<label for="autocomplete" class="col-sm-2 control-label">
		            		<i class="fa fa-location-arrow"></i>
		            	</label>
		            	<div id="locationField" class="col-sm-10">
					      	<input id="autocomplete" class="form-control" placeholder="Ingresa tu dirección" onFocus="geolocate()" type="text"></input>
					    </div>
		            </div>

		            <div align="right">
		            	<div class="row" style="width: 90%;" >
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $street }}" class="form-control" name="street" id="street_number"  placeholder="Número de calle" {{ ( empty( $street ) ) ? 'disabled="true"' : '' }}></input>
			            	</div>
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $colony }}" class="form-control" name="colony" id="route" {{ ( empty( $colony ) ) ? 'disabled="true"' : '' }}></input>
			            	</div>
			            </div>
						<br />              	
		              	<div class="row" style="width: 90%;" >
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $delegation }}" class="form-control" name="delegation" id="locality" {{ ( empty( $delegation ) ) ? 'disabled="true"' : '' }} placeholder="Ciudad"></input>
			            	</div>
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $state }}" class="form-control" name="state" id="administrative_area_level_1" placeholder="Estado" {{ ( empty( $state ) ) ? 'disabled="true"' : '' }}></input>
			            	</div>
			            </div>
						<br />
			            <div class="row" style="width: 90%;" >
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $postalcode }}" class="form-control" name="postalcode" id="postal_code" {{ ( empty( $postalcode ) ) ? 'disabled="true"' : '' }} placeholder="Código postal"></input>
			            	</div>
			            	<div class="col-sm-6">
			            		<input type="text" value="{{ $country }}" class="form-control" name="country" id="country" placeholder="País" {{ ( empty( $country ) ) ? 'disabled="true"' : '' }}></input>
			            	</div>
			            </div>
		            </div>

		            <input type="text" style="display: none;" name="latitude" id="latitudeFend" />
		            <input type="text" style="display: none;" name="longitude" id="longitudeFend" />
		            <br/>
		            <!-- /.box-body -->
				  	<div class="box-footer">
				    	<div class="row">

				    		@if ($status == "In Progress")
					    		<div class="col-sm-4">
					            	&nbsp;
					            </div>
					    		<div class="col-sm-4">
						    		<button type="submit" class="btn btn-secondary btn-block btn-flat">
						                Guardar
						            </button>
					            </div>
					            <div class="col-sm-4">
					            	&nbsp;
					            </div>
					       	@else 
					       		<div class="col-sm-4">
					            	&nbsp;
					            </div>
					       		<div class="col-sm-4">
						    		<button type="submit" class="btn btn-secondary btn-block btn-flat">
						                Guardar
						            </button>
					            </div>
					    		<div class="col-sm-4">
					    			<a href="{{ url()->previous() }}" class="btn btn-default btn-block btn-flat">
						                Cancelar
						            </a>
					            </div>
					            <div class="col-sm-4">
					            	&nbsp;
					            </div>
							@endif
							
				    	</div>
				  	</div>
				  	<!-- box-footer -->
			</form>
	    		

	    	@else
    			<!-- Custom Tabs -->
		        <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              	<li class="active"><a href="#tab_1" data-toggle="tab">Información personal</a></li>
		              	<li><a href="#tab_2" data-toggle="tab">Familia</a></li>
		              	<li><a href="#tab_3" onclick="initMapAddressUser();" data-toggle="tab">Dirección de usuario</a></li>
		            </ul>
		            <div class="tab-content">
		              	<div class="tab-pane active" id="tab_1">
		              		<br/>

			                <div class="row">
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Corre electrónico:</b></div>
			                			<div class="col-sm-6" align="left">{{ $email }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Nombre de usuario:</b></div>
			                			<div class="col-sm-6" align="left">{{ $username }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Edad:</b></div>
			                			<div class="col-sm-6" align="left">{{ $age }}</div>
			                		</div>
			                	</div>
			                </div>
			                <br/>
			                <div class="row">
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Ocupación:</b></div>
			                			<div class="col-sm-6" align="left">{{ $occupation }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Genero:</b></div>
			                			<div class="col-sm-6" align="left">{{ $gender }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Escolaridad:</b></div>
			                			<div class="col-sm-6" align="left">{{ $scholarship }}</div>
			                		</div>
			                	</div>
			                </div>
			                <br/>
			                <div class="row">
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Estado civil:</b></div>
			                			<div class="col-sm-6" align="left">{{ $maritalstatus }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b># Móvil:</b></div>
			                			<div class="col-sm-6" align="left">{{ $mobile }}</div>
			                		</div>
			                	</div>
			                	<div class="col-sm-4">
			                		<div class="row">
			                			<div class="col-sm-6" align="left"><b>Ultima modificación:</b></div>
			                			<div class="col-sm-6" align="left">{{ $updated_at }}</div>
			                		</div>
			                	</div>
			                </div>


		              	</div>
		              	<div class="tab-pane" id="tab_2">
		                	space..
		              	</div>
		              	<div class="tab-pane" id="tab_3" style="height: 250px;">
		              		<div id="mapAddressUser" ></div>
		              	</div>
		            </div>
		        </div>
    		@endif






    		<script type="text/javascript">


    			window.onload = function(){
    				initAutocomplete();

    				@if( empty($status) )
    					initMapAddressUser();
					@endif
    			};


		      // This example displays an address form, using the autocomplete feature
		      // of the Google Places API to help users fill in the information.

		      // This example requires the Places library. Include the libraries=places
		      // parameter when you first load the API. For example:
		      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

		      var placeSearch, autocomplete;
		      var componentForm = {
		        street_number: 'short_name',
		        route: 'long_name',
		        locality: 'long_name',
		        administrative_area_level_1: 'short_name',
		        country: 'long_name',
		        postal_code: 'short_name'
		      };

		      function initAutocomplete() {
		        // Create the autocomplete object, restricting the search to geographical
		        // location types.
		        autocomplete = new google.maps.places.Autocomplete(
		            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
		            {types: ['geocode']});

		        // When the user selects an address from the dropdown, populate the address
		        // fields in the form.
		        autocomplete.addListener('place_changed', fillInAddress);
		      }

		      function fillInAddress() {
		        // Get the place details from the autocomplete object.
		        var place = autocomplete.getPlace();

		        for (var component in componentForm) {
		          document.getElementById(component).value = '';
		          document.getElementById(component).disabled = false;
		        }

		        // Get each component of the address from the place details
		        // and fill the corresponding field on the form.
		        for (var i = 0; i < place.address_components.length; i++) {
		          var addressType = place.address_components[i].types[0];
		          if (componentForm[addressType]) {
		            var val = place.address_components[i][componentForm[addressType]];
		            document.getElementById(addressType).value = val;
		          }
		        }
		      }

		      // Bias the autocomplete object to the user's geographical location,
		      // as supplied by the browser's 'navigator.geolocation' object.
		      function geolocate() {

		        if (navigator.geolocation) {
		          navigator.geolocation.getCurrentPosition(function(position) {
		            var geolocation = {
		              lat: position.coords.latitude,
		              lng: position.coords.longitude
		            };

		            console.log(geolocation.lat + ' ' + geolocation.lng);
		            document.getElementById('latitudeFend').value = geolocation.lat; 
		            document.getElementById('longitudeFend').value = geolocation.lat;
		            var circle = new google.maps.Circle({
		              center: geolocation,
		              radius: position.coords.accuracy
		            });
		            autocomplete.setBounds(circle.getBounds());
		          });
		        }
		      }	

		    </script>

		    @if( empty($status) )

		    	<script type="text/javascript">
		    		var counter = -1;

			      	function initMapAddressUser() {

				      	if(!counter > 0){
				      		var map = new google.maps.Map(document.getElementById('mapAddressUser'), {
					          zoom: 7,
					          center: {lat: {{ $longitude }} , lng: {{ $latitude }} }
					        });

					        var image = "{{ asset('maps-and-flags_1.png') }}";
					        
					        var beachMarker = new google.maps.Marker({
					          position: {lat: {{ $longitude }} , lng: {{ $latitude }} },
					          map: map,
					          icon: image
					        });
					    }
				        counter++;
			      	}
		    	</script>

			@endif

	  	</div>	  	
	</div>
    <script src="{{ asset('js/jquery.Jcrop.min.js') }}"></script>
	<script src="{{ asset('js/jquery.color.js') }}"></script>
	<script src="{{ asset('js/jquery.Jcrop.js') }}"></script>

<script type="text/javascript">

    jQuery(function(){ jQuery('#target').Jcrop(); });
     jQuery('#target').Jcrop({
      aspectRatio: 1,
      onSelect: updateCoords,
	  setSelect: [0, 0, 300, 300],
      bgColor:     'black',

     });
     function updateCoords(c){
      jQuery('#x').val(c.x);
      jQuery('#y').val(c.y);
      jQuery('#w').val(c.w);
      jQuery('#h').val(c.h);
     };
     function checkCoords()
{
	if (parseInt(jQuery('#w').val())>0) return true;
	alert('Seleccione una coordenada para subir');
	return false;
};
</script>
@stop
