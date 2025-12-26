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
      label: "20202021 Fund Out Flow",
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
      data: [1068
        ,990
        ,2569
        ,663
        ,781
        ,1128
        ,805
        ,1904
        ,3277
        ,2727
        ,3391
        ,4080
        ],
    },{
      label: "20212022 Fund Out Flow",
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
      data: [4202
        ,2291
        ,1945
        ,2828
        ,1887
        ,1461
        ,1811
        ,2321
        ,3509
        ,3273
        ,3013
        ,4556
        ],
    }],
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
          max: 6000,
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
