// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Apr"
        ,"May"
        ,"Jun"
        ,"Jul"
        ,"Aug"
        ,"Sep"
        ,"Oct"
        ,"Nov"
        ,"Dec"
        ,"Jan"
        ,"Feb"
        ,"Mar"
        ],
    datasets: [{
      label: "20202021 Sale Rate",
      lineTension: 0.3,
      backgroundColor: "green",
      borderColor: "red",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      fill:false,
      data: [3010
        ,2655
        ,2941
        ,3126
        ,3229
        ,3198
        ,3123
        ,3121
        ,3113
        ,3106
        ,2902
        ,2897
        ],
    },{
      label: "20212022 Sale Rate",
      lineTension: 0.3,
      backgroundColor: "light",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      fill:false,
      data: [3110
        ,3094
        ,3134
        ,2988
        ,3037
        ,3403
        ,3339
        ,3232
        ,3269
        ,3232
        ,3208
        ,3199
        ],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
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
          max: 4000,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});
