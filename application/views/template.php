<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="<?php echo base_url(); ?>">
    <link rel="shortcut icon" href="../images/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <!-- <title>Power|<?php echo $this->uri->segment(2)?></title> -->

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="js/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />


    <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css"> 
    <link rel="stylesheet" type="text/css" href="css/mystyle.css">    
 <!-- data table -->
 <script src="js/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="css/buttons.dataTables.min.css" />
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="js/jszip.min.js"></script>
    <script type="text/javascript" src="js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="js/buttons.print.min.js"></script>
    
    <script type="text/javascript">
            $(document).ready(function () {
                $('#center-content').DataTable({
                    "bPaginate": false,
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        className: 'exportExcel',
                        filename: '<?php echo $this->uri->segment(3)?>',
                        exportOptions: { modifier: { page: 'all'} }
                    },
                    {
                      extend: 'print',
                      text: window.printButtonTrans,
                      exportOptions: {
                         columns: ':visible'
                        }
                    }
                   /* {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'exportExcel',
                        filename: 'Test_Pdf',
                        
                        exportOptions: { modifier: { page: 'all'} }
                    }
                    */
                    ]
                });
            });
        </script>
<!-- end -->   
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">


    
    <script src="js/chart-master/Chart.js"></script>    
    <script src="js/myjs.js"></script>


  </head>


  <body>

  <section id="container" >
      <!--header end-->
      <?php $this->load->view("header");?>
      
      <!--sidebar start-->
      <?php $this->load->view("aside");?>
      <!--sidebar end-->      
      
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">

                                
                  <?php $this->load->view($content);?>
              
          </section>
      </section>

      <!--main content end-->
      <!--footer start-->
      
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <!-- <script src="js/jquery.js"></script> -->
    <script src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="js/fullcalendar/fullcalendar.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/jquery.sparkline.js"></script>


    <!--common script for all pages-->
    <script src="js/common-scripts.js"></script>
    
    <script type="text/javascript" src="js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="js/gritter-conf.js"></script>

    <!--script for this page-->
    <script src="js/sparkline-chart.js"></script>    
  <script src="js/zabuto_calendar.js"></script> 

  <script src="js/calendar-conf-events.js"></script> 
  




<script>
      //custom select box

      $(function(){
          $("select.styled").customSelect();
      });

  </script>
  
  <script type="application/javascript">
        $(document).ready(function () {
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });
        
            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url: "show_data.php?action=1",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });
        
        
        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>
  

  </body>
</html>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>   

<script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('#date').datepicker({
                    format: "yyyy-mm-dd"
                });
                 $('#startdate').datepicker({
                    format: "yyyy-mm-dd"
                }); 
                 $('#enddate').datepicker({
                    format: "yyyy-mm-dd"
                });            
            });
</script>