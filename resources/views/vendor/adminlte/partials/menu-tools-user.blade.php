 @if(session()->get('utype') != "doctor")
      <style type="text/css">
            .accordion-toggle {
            position: relative;
          }
          .accordion-toggle::before,
          .accordion-toggle::after {
            content: '';
            display: block;
            position: absolute;
            top: 50%;
            left: -18px;
            width: 12px;
            height: 4px;
            margin-top: -2px;
            background-color: #585858;
            -webkit-transform-origin: 50% 50%;
            -ms-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
            -webkit-transition: all 0.25s;
            transition: all 0.25s;
          }
          .accordion-toggle::before {
            -webkit-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            transform: rotate(-90deg);
            opacity: 0;
          }
          .accordion-toggle.collapsed::before {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
            opacity: 1;
          }
          .accordion-toggle.collapsed::after {
            -webkit-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            transform: rotate(-90deg);
          }
          .control-sidebar-dark {
            color: #333;
          }
          .tit{
            border-top-color: black;
            font-size: 13px !important;
          }
      </style>

                          <aside class="control-sidebar control-sidebar-dark" style="overflow: hidden;">
                              <!-- Create the tabs -->
                              <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-check-circle"></i></a></li>
                                <li><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab"><i class="fa fa-file-text"></i></a></li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                <!-- Home tab content -->
                                <div class="tab-pane active" id="control-sidebar-home-tab">
                                  <h4 class="control-sidebar-heading">Tratamiento Activo</h4>
                                    <ul class="control-sidebar-menu">
                                 @foreach($medicationAll as $created_at => $medic)
                                              <li>{{ $created_at }}</li>
                                              @foreach($medic as $mediac)
                                             @if($mediac->active == 'Confirmed')
                                                  <li>
                                                    <a class="pointer"><i class="menu-icon bg-green" style="font-size: 11px;">Ini</i>
                                                    <div class="menu-info"><h4 class="control-sidebar-subheading">{{ $mediac->name_medicine }}</h4>
                                                      <p>{{ $mediac->frequency_days }} día(s), {{ $mediac->posology }}</p></div>
                                                    </a>
                                                  </li>
                                              @endIf
                                       @endforeach
                                  @endforeach     
                                        </ul>         

                                </div>
                                <div id="control-sidebar-theme-demo-options-tab" class="tab-pane"><div>
                                  <h4 class="control-sidebar-heading">Todos los tratamientos</h4>
                                       <ul class="control-sidebar-menu">
                                          
                                        @foreach($medicationAll as $created_at => $medic)
                                              <li style="color: white;">{{ $created_at }}</li>
                                           @foreach($medic as $medi)
                                              @if($medi->active == 'Not Confirmed')
                                              <li>
                                                <a class="pointer"><i class="menu-icon bg-red" style="font-size: 11px;">No</i>
                                                <div class="menu-info"><h4 class="control-sidebar-subheading" style="font-size: 11px;">{{ $medi->name_medicine }}</h4>
                                                  <p>{{ $medi->frequency_days }} día(s), {{ $medi->posology }}</p></div>
                                                </a>
                                              </li>
                                              @endif

                                              @if($medi->active == 'Finished')
                                              <li>
                                                <a class="pointer"><i class="menu-icon bg-yellow" style="font-size: 11px;">Fin</i>
                                                <div class="menu-info"><h4 class="control-sidebar-subheading">{{ $medi->name_medicine }}</h4>
                                                  <p>{{ $medi->frequency_days }} día(s), {{ $medi->posology }}</p></div>
                                                </a>
                                              </li>
                                              @endif
                                           @endforeach
                                         @endforeach 
                                        </ul> 
                                </div></div>
                                <!-- /.tab-pane -->
                                <!-- Stats tab content -->
                              </div>
                            </aside>
 @endif
