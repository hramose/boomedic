@extends('adminlte::page')

@section('title', 'Boomedic')

@section('content_header')

@stop

@section('content')
			<script type="text/javascript">

 $(document).ready(function(){
				var code = "@php echo $code; @endphp";
				    var IS_IPAD = navigator.userAgent.match(/iPad/i) != null,
				    IS_IPHONE = !IS_IPAD && ((navigator.userAgent.match(/iPhone/i) != null) || (navigator.userAgent.match(/iPod/i) != null)),
				    IS_IOS = IS_IPAD || IS_IPHONE,
				    IS_ANDROID = !IS_IOS && navigator.userAgent.match(/android/i) != null,
				    IS_MOBILE = IS_IOS || IS_ANDROID;
				    var market_a = location.href = "https://sbx00.herokuapp.com/verify/" + code;
					var market_i = location.href = "https://sbx00.herokuapp.com/verify/" + code;

							//schema of the app
									
					if(IS_ANDROID) {
							setTimeout( function() {
									goMarket();
								}, 25);
						location.href = "boomedic://verify/"+ code;
						}
						else if(IS_IOS) {
							setTimeout( function() {
									goMarket();
								}, 25);
							location.href = "boomedic://verify/"+ code;
							} else {
								location.href = "https://sbx00.herokuapp.com/verify/"+ code;
							}

						function goMarket() {
							if(IS_ANDROID) {
								location.href=market_a;
							} else if(IS_IOS) {
								location.href=market_i;
							} else {
								// do nothing
							}
					}
			})		
			</script>
<div class="box">
  	<div class="box-header with-border">
	    <h3 class="box-title">Redireccionamiento en correo</h3>
  	</div>
  	<div class="box-body">
	  
 	</div>
</div>	  	
@stop