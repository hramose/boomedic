 @if(session()->get('utype') == "doctor")

                          <aside class="control-sidebar control-sidebar-dark">
                              <!-- Create the tabs -->
                              <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                                <li class="active"><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab"><i class="fa fa-wrench"></i></a></li><li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

                                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                <!-- Home tab content -->
                                <div class="tab-pane" id="control-sidebar-home-tab">
                                  <h3 class="control-sidebar-heading">Recent Activity</h3>
                                  <ul class="control-sidebar-menu">
                                    <li>
                                      <a href="javascript:void(0)">
                                        <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                        <div class="menu-info">
                                          <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                          <p>Will be 23 on April 24th</p>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <i class="menu-icon fa fa-user bg-yellow"></i>

                                        <div class="menu-info">
                                          <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                          <p>New phone +1(800)555-1234</p>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                        <div class="menu-info">
                                          <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                          <p>nora@example.com</p>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                        <div class="menu-info">
                                          <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                          <p>Execution time 5 seconds</p>
                                        </div>
                                      </a>
                                    </li>
                                  </ul>
                                  <!-- /.control-sidebar-menu -->

                                  <h3 class="control-sidebar-heading">Tasks Progress</h3>
                                  <ul class="control-sidebar-menu">
                                    <li>
                                      <a href="javascript:void(0)">
                                        <h4 class="control-sidebar-subheading">
                                          Custom Template Design
                                          <span class="label label-danger pull-right">70%</span>
                                        </h4>

                                        <div class="progress progress-xxs">
                                          <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <h4 class="control-sidebar-subheading">
                                          Update Resume
                                          <span class="label label-success pull-right">95%</span>
                                        </h4>

                                        <div class="progress progress-xxs">
                                          <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <h4 class="control-sidebar-subheading">
                                          Laravel Integration
                                          <span class="label label-warning pull-right">50%</span>
                                        </h4>

                                        <div class="progress progress-xxs">
                                          <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                        </div>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="javascript:void(0)">
                                        <h4 class="control-sidebar-subheading">
                                          Back End Framework
                                          <span class="label label-primary pull-right">68%</span>
                                        </h4>

                                        <div class="progress progress-xxs">
                                          <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                        </div>
                                      </a>
                                    </li>
                                  </ul>
                                  <!-- /.control-sidebar-menu -->

                                </div><div id="control-sidebar-theme-demo-options-tab" class="tab-pane active"><div><h4 class="control-sidebar-heading">Layout Options</h4><div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="fixed" class="pull-right"> Fixed layout</label><p>Activate the fixed layout. You can't use fixed and boxed layouts together</p></div><div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="layout-boxed" class="pull-right"> Boxed Layout</label><p>Activate the boxed layout</p></div><div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="sidebar-collapse" class="pull-right"> Toggle Sidebar</label><p>Toggle the left sidebar's state (open or collapse)</p></div><div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-enable="expandOnHover" class="pull-right"> Sidebar Expand on Hover</label><p>Let the sidebar mini expand on hover</p></div><div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-controlsidebar="control-sidebar-open" class="pull-right"> Toggle Right Sidebar Slide</label><p>Toggle between slide over content and push content effects</p></div></div></div>
                                <!-- /.tab-pane -->
                                <!-- Stats tab content -->
                                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                                <!-- /.tab-pane -->
                                <!-- Settings tab content -->
                                <div class="tab-pane" id="control-sidebar-settings-tab">
                                  <form method="post">
                                    <h3 class="control-sidebar-heading">General Settings</h3>

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Report panel usage
                                        <input type="checkbox" class="pull-right" checked="">
                                      </label>

                                      <p>
                                        Some information about this general settings option
                                      </p>
                                    </div>
                                    <!-- /.form-group -->

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Allow mail redirect
                                        <input type="checkbox" class="pull-right" checked="">
                                      </label>

                                      <p>
                                        Other sets of options are available
                                      </p>
                                    </div>
                                    <!-- /.form-group -->

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Expose author name in posts
                                        <input type="checkbox" class="pull-right" checked="">
                                      </label>

                                      <p>
                                        Allow the user to show his name in blog posts
                                      </p>
                                    </div>
                                    <!-- /.form-group -->

                                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Show me as online
                                        <input type="checkbox" class="pull-right" checked="">
                                      </label>
                                    </div>
                                    <!-- /.form-group -->

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Turn off notifications
                                        <input type="checkbox" class="pull-right">
                                      </label>
                                    </div>
                                    <!-- /.form-group -->

                                    <div class="form-group">
                                      <label class="control-sidebar-subheading">
                                        Delete chat history
                                        <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                                      </label>
                                    </div>
                                    <!-- /.form-group -->
                                  </form>
                                </div>
                                <!-- /.tab-pane -->
                              </div>
                            </aside>
 @endif
