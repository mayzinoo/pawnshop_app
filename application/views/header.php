
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>Power Accounting System</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">ကုန္က်န္စာရင္း
                            <i class="fa fa-book"></i>
                            <span class="badge bg-theme"><?php echo $totalstocklist->num_rows(); ?></span>
                        </a>
                        <ul class="dropdown-menu extended tasks-bar">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have <?php echo $totalstocklist->num_rows(); ?> pending tasks</p>
                            </li>
                            <?php foreach($stocklist->result() as $list):?>
                                <li>
                                    <p>ေဘာင္ခ်ာနံပါတ္ - <?php echo $list->voucher; ?></p>
                                </li>
                            <?php endforeach;?>
                            <li class="external">
                                <a href="Main/data_list/stockList">See All Lists</a>
                            </li>
                        </ul>
                    </li>
                    <!-- settings end -->
                    <!-- inbox dropdown start-->
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">အေပါင္ဆံုးစာရင္း
                            <i class="fa fa-book"></i>
                            <span class="badge bg-theme"><?php echo $unabletoredeem->num_rows(); ?></span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have <?php echo $unabletoredeem->num_rows(); ?> new messages</p>
                            </li>
                            <?php foreach($unabletoredeem->result() as $list):?>
                                <li>
                                    <p>ေဘာင္ခ်ာနံပါတ္ - <?php echo $list->voucher; ?></p>
                                </li>
                            <?php endforeach;?>
                            <li>
                                <a href="Main/data_list/unabletoredeemList">See all Lists</a>
                            </li>
                        </ul>
                    </li>
                    <!-- inbox dropdown end -->
                    <!-- inbox dropdown start-->
                    
                    <!-- inbox dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-menu">
              <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="Main/logout">Logout</a></li>
              </ul>
            </div>
        </header>