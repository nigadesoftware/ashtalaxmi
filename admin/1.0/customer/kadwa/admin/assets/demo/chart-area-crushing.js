// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
var ctx = document.getElementById("myAreaChartCrushing");
var myAreaChartCrushing = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Nov","Dec","Jan","Feb","Mar","Apr"],
    datasets: [{
      label: "20212022 Crushing",
      lineTension: 0.3,
      backgroundColor: "green",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: [5103.712
        ,77486.756
        ,73019.642
        ,81923.338
        ,67346.926
        ,69461.869
        ,28529.808
        ],
    },{
      label: "20202021 Crushing",
      lineTension: 0.3,
      backgroundColor: "yellow",
      borderColor: "red",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: [10581.133
        ,81266.985
        ,84495.861
        ,79853.763
        ,68884.701
        ,68102.128
        ,50973.085
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
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 100000,
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
