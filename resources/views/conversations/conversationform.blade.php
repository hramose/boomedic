<style type="text/css">
  .box.box-warning {
    border-top-color: #0e0e0e !important;
}
.direct-chat-warning .right>.direct-chat-text {
    background: #000000;
    border-color: #000000;
    color: #fff;
}
.direct-chat-warning .right>.direct-chat-text:after, .direct-chat-warning .right>.direct-chat-text:before {
    border-left-color: #000000 !important;
}
.contacts-list-msg {
    width:80%;
    white-space:nowrap;
    text-overflow: ellipsis;
}
</style>


              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h5 id="titleC"></h5>

                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="" class="badge bg-gray count" data-original-title="" id="count"></span>
                    <button type="button" class="btn btn-box-tool contacts" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts" id="contacts">
                      <i class="fa fa-comments"></i></button>
                  </div>
                  <input type="hidden" id="userOrDr" value="1">
                  <input type="hidden" id="mid" value="">
                </div>
                <div class="box-body message0" id="message0">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages message" id="message">
                  </div>
                  <!-- Contacts are loaded here -->
                  <div class="direct-chat-contacts">
                    <ul class="contacts-list">
                    </ul>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <form action="#" method="post">
                    <div class="input-group">
                      <input type="text" name="message" placeholder="Escriba su mensaje..." class="form-control textbody" autocomplete="off">
                      <span class="input-group-btn">
                            <button type="button" class="btn btn-secondary btn-flat" onclick="send();">Enviar</button>
                          </span>
                    </div>
                  </form>
                </div>
                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->



<script type="text/javascript">
 var dr = "{{ session()->get('utype') }}";
       $(function(){
                      //Valid to know who sends the data and if you are a patient or doctor
                     
                      if(window.location.href == "{{ url('clinicHistory/index') }}" || dr != "doctor"){
                        $('.modal-chat').on('show.bs.modal', function (e) {
                          $(".contacts").css("display", "none");

                          var data = $(this).find(".midfield").val();
                          get(data);
                        })
                      }
                      else{
                            if(!$("#mid").val()){
                            var data = 0;
                         }
                            else{
                            var dat = $("#mid").val();
                            }
                        get(data);
                      }
       })
          var timer = null;
          function get(data){   

              $.ajax({
                 type: "GET",                 
                 url: "{{ url('Conversations/messages') }}/" + data,  
                 success: function(result){

                  if(result[1].length == 0){
                    $(".contacts").css("display", "none");
                    $("#userOrDr").val(result[1].length);
                  }
                  if(result[1].length > 0){
                    $(".contacts-list").html("");
                        if(result[2].length > 0){
                          for(var x = 0; x < result[2].length; x++){ 
                            var mo = moment(result[2][x]['created_at']).fromNow();
                            $(".contacts-list").append('<li><a href="#" onclick="searchM('+ result[2][x]['id'] +');"><img class="contacts-list-img" src="'+ result[2][x]['profile_photo'] +'" alt="User Image"><div class="contacts-list-info"><span class="contacts-list-name">'+ result[2][x]['nameu'] +'<small class="contacts-list-date pull-right">'+ mo +'</small></span><span class="contacts-list-msg">'+ result[2][x]['name'] +'</span></div></a></li>');
                          }
                         }
                           else{
                              $(".contacts-list").append('<li>No hay ninguna conversación iniciada por paciente</li>');
                           }
                   }
                      if(result[0].length == 0){
                        $(".count").html("0");
                        $(".count").attr("data-original-title", "0 mensajes");
                        $(".direct-chat-messages").text('');
                        $(".direct-chat-messages").append('<div align="center" class="nullm">No se ha empezado ninguna conversación</div>');
                      }
                  else{
                   $(".direct-chat-messages").html("");
                     for(var z = 0; z < result[0].length; z++){ 
                      var title = result[0][z]['namec'];
                        var mo = moment(result[0][z]['created_at']).fromNow();
                            if(result[0][z]['profile_photo'] == "@php echo $photo; @endphp"){
                            $(".direct-chat-messages").append('<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-timestamp pull-left">'+ mo +'</span></div><img class="direct-chat-img" src="'+ result[0][z]['profile_photo'] +'" alt="Imagen de usuario"><div class="direct-chat-text">'+ result[0][z]['text_body'] +'</div></div>');
                        }else{
                          $(".direct-chat-messages").append('<div class="direct-chat-msg other"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+ result[0][z]['name'] +'</span><span class="direct-chat-timestamp pull-right">'+ mo +'</span></div><img class="direct-chat-img" src="'+ result[0][z]['profile_photo'] +'" alt="Imagen de usuario"><div class="direct-chat-text">'+ result[0][z]['text_body'] +'</div></div>');
                        }
                          $("#mid").val(result[0][z]['id_record']);
                     }
                     $("#titleC").text(title);
                           //Auto-scroll
                            var altura = (document.getElementById("message").scrollHeight + $(".message").height());
                         $(".message").animate({scrollTop:altura+"px"});
                        count();

                      if ($('.modal-chat').is(':hidden')) {
                                  clearTimeout(timer);
                        }
                        else{ 
                                 timer = setTimeout(function(){ repeat(data); },10000);
                             }
                    }
                },
                    error: function(error, status) {
                       // window.location.href = "{{ url('') }}";
                    }
              });
           }

   function count(data){
     var count = $(".message .direct-chat-msg.other").length;
     $(".count").html(count);
         if(count == 1){
             $(".count").attr("data-original-title", count + " nuevo mensaje");
               } 
         else{
              $(".count").attr("data-original-title", count + " nuevos mensajes");
            }
          }

     function repeat(data){
      get(data);
       }

     function searchM(data){
      clearTimeout(timer);
        get(data);
        $('.contacts').click();
        $("#mid").val(data);
     }  

     function send(){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    //Valido para saber de que página envía los datos y si es paciente o doctor

                    if(window.location.href == "{{ url('clinicHistory/index') }}" || dr != "doctor"){
                    var data = { 
                                  "doc"       : $(".modal.in .middr").val(),
                                  "table"     : $(".modal.in .mtable").val(),
                                  "id_record" : $(".modal.in .midfield").val(),
                                  "name_mess" : $(".modal.in .mname").val(),
                                  "textbody"  : $(".modal.in .textbody").val()
                                };

                              }else {
                                if($("#mid").val().length > 0){

                                   var data = { 
                                        "id_record" : $("#mid").val(),
                                        "textbody"  : $(".textbody").val()
                                      };
                                    }else{
                                       var bool = 1;
                                    }
                              }
                    //fin validación y datos          
                        if(bool == 1){
                            alert("No tienes habilitado poder iniciar conversaciones por ahora.");
                              $(".textbody").val("");
                              $(".textbody").focus();
                        }else{
                           $.ajax({     
                             type: "POST",                 
                             url: "{{ url('Conversations/sendMessages') }}",  
                              data: data, 
                              dataType: 'json',                
                             success: function(result2)             
                             {
                              $(".textbody").val("");
                              $(".textbody").focus();
                              if($(".nullm").length > 1){
                                $(".direct-chat-messages").html("");
                              }
                              for(var y = 0; y < result2.length; y++){ 
                              var mo = moment(result2[y]['created_at']).fromNow();
                                  if(result2[y]['profile_photo'] == "@php echo $photo; @endphp"){
                                    $(".direct-chat-messages").append('<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+ result2[y]['name'] +'</span><span class="direct-chat-timestamp pull-left">'+ mo +'</span></div><img class="direct-chat-img" src="'+ result2[y]['profile_photo'] +'" alt="Imagen de usuario"><div class="direct-chat-text">'+ result2[y]['text_body'] +'</div></div>');
                                  }else{
                                    $(".direct-chat-messages").append('<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+ result2[y]['name'] +'</span><span class="direct-chat-timestamp pull-right">'+ mo +'</span></div><img class="direct-chat-img" src="'+ result2[y]['profile_photo'] +'" alt="Imagen de usuario"><div class="direct-chat-text">'+ result2[y]['text_body'] +'</div></div>');
                                  }
                                $("#mid").val(result2[y]['id_record']);
                               }
                              //Auto-scroll
                            var altura = (document.getElementById("message").scrollHeight + $(".message").height());
                              $(".message").animate({scrollTop:altura+"px"});
                              }
                         });
                         }
     }

                           $('.textbody').keypress(function(e) {
                          var keycode = (e.keyCode ? e.keyCode : e.which);
                              if (keycode == '13') {
                                  send();
                                  e.preventDefault();
                                  return false;
                              }
                           });
</script>          
