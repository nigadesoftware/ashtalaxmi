// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
var ctx = document.getElementById("myHorizontalBarChart");
var myLineChart = new Chart(ctx, {
  type: 'horizontalBar',
  data: {
    labels: ["Truck"
      ,"Tractor"
      ,"Jugad"
      ,"Bulluckcart"
      ],
    datasets: [{
      label: "20202021 Crushing",
      backgroundColor: "rgba(40,167,69,1)",
      borderColor: "rgba(40,167,69,1)",
      data: [69506.267
        ,134777.124
        ,156679.714
        ,41908.946
        ],
    },{
      label: "20212022 Crushing",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: [72025.255
        ,154378.712
        ,176968.588
        ,40785.101
        ],
    }
    
  ],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 5
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 100000,
          maxTicksLimit: 5
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});
