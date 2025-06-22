<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>Morris.js Bar and Stacked Chart With Codeigniter</title>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</head>
<body>
<div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
</body>
<script>
window.onload = function () {
var pageURL = window.location.href;
var lastURLSegment = pageURL.substr(pageURL.lastIndexOf('/') + 1);
alert(lastURLSegment);
{
                $.post("data.php?userid="+lastURLSegment,

                function (data)
                {
                    
                     var name = [];
                    var marks = [];

                    for (var i in data) {
                        name.push(data[i].mydate);
                        marks.push(data[i].result);
                    }

                    var chartdata = {
                        labels: name,
                        datasets: [
                            {
                                label: 'Daily Netprofit',
                                backgroundColor: '#305882',
                                
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: marks
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'line',
                        data: chartdata
                    });
                });
            }

var chart = new CanvasJS.Chart("chartContainer", {


  animationEnabled: true,
  theme: "light2",
  title:{
    text: "Simple Line Chart"
  },
  axisY:{
    includeZero: false
  },
  data: [{        
    type: "line",
        indexLabelFontSize: 16,
    dataPoints: [
      { y: 450 },
      { y: 414},
      { y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
      { y: 460 },
      { y: 450 },
      { y: 500 },
      { y: 480 },
      { y: 480 },
      { y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
      { y: 500 },
      { y: 480 },
      { y: 510 }
    ]
  }]
});
chart.render();

}
</script>
</html>



