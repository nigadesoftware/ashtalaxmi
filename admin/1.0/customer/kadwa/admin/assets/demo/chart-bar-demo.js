// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Oct","Nov","Dec","Jan","Feb","Mar","Apr"],
    datasets: [{
      label: "20202021 Recovery",
      backgroundColor: "rgba(40,167,69,1)",
      borderColor: "rgba(40,167,69,1)",
      data: [3.95
        ,10.04
        ,10.88
        ,11.55
        ,12.44
        ,12.05
        ,12.23
        ],
    },{
      label: "20212022 Recovery",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: [0
        ,10.1
        ,11.44
        ,11.49
        ,11.69
        ,12.41
        ,12.11
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
          maxTicksLimit: 12
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 15,
          maxTicksLimit: 12
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
