// var data={
//             "param-status":{
//                 "name": "Param Status",
//                 "category":['Oct-Wk1', 'OCT-Wk2','OCT-Wk2','NOV-Wk4'],
//                 "data": [{
//                     "name": "MMX",
//                     "data": [5, 3, 4,7]       
//                  },{
//                     "name": "Services",
//                     "data": [2, 2, 3,7]               
//                   }, {
//                     "name": "TPTX",
//                     "data": [3, 4, 4,7]               
//                   }, {
//                     "name": "RPD",
//                     "data": [3, 4, 4,7]               
//                   }, {
//                     "name": "MNGBLT",
//                     "data": [3, 4, 4,7]               
//                   }, {
//                     "name": "BBE",
//                     "data": [3, 4, 4,7]                }]
//             },
//             "link-status":{
//                 "name": "Link Status",
//                 "category":['Oct-Wk3', 'OCT-Wk4','OCT-Wk5','NOV-Wk4'],
//                 "data": [{
//                     "name": "MMXx",
//                     "data": [5, 3, 5,8]
                    
//                 }, {
//                     "name": "Service",
//                     "data": [6, 8, 3,8]
//                 }, {
//                     "name": "TPTXx",
//                     "data": [8, 4, 4,8]
//                 }, {
//                     "name": "RPDD",
//                     "data": [9, 4, 4,8]
//                 }, {
//                     "name": "MNGBLTT",
//                     "data": [4, 3, 4,8]
//                 }, {
//                     "name": "BBEE",
//                     "data": [3, 4, 4,8]
//                 }]
//             },
//             "ixia-issues":{
//                 "name": "IXIA Issues",
//                 "category":['Oct-Wk3', 'OCT-Wk4','OCT-Wk5','NOV-Wk4'],
//                 "data": [{
//                     "name": "MMXx",
//                     "data": [1,2,3,6]
//                 }, {
//                     "name": "Service",
//                     "data": [2,2,2,4]
//                 }, {
//                     "name": "TPTXx",
//                     "data": [8, 4, 4.3]
//                 }, {
//                     "name": "RPDD",
//                     "data": [9, 4, 4.9]
//                 }, {
//                     "name": "MNGBLTT",
//                     "data": [4, 3, 4.2]
//                 }, {
//                     "name": "BBEE",
//                     "data": [3, 4, 4,1]
//                 }]
//             },
//             "jpg-issues":{
//                 "name": "JPG Issues",
//                 "category":['Oct-Wk3', 'OCT-Wk4','OCT-Wk5','NOV-Wk4'],
//                 "data": [{
//                     "name": "MMXx",
//                     "data": [1,2,3,4]
//                 }, {
//                     "name": "Service",
//                     "data": [2,2,2,4]
//                 }, {
//                     "name": "TPTXx",
//                     "data": [8, 4, 4,4]
//                 }, {
//                     "name": "RPDD",
//                     "data": [9, 4, 4,4]
//                 }, {
//                     "name": "MNGBLTT",
//                     "data": [4, 3, 4]
//                 }, {
//                     "name": "BBEE",
//                     "data": [3, 4, 4,4]
//                 }]
//             }
//         };
title="";
// url="api/index.php?action=infrafailures";
url="json/infra.json";
        showPageLoader();

// Ajax Request Api Call
    var request = $.ajax({
            url: url,
            type:"GET",
            dataType: "JSON"
      });

         request.done(function(data) {
          hidePageLoader();
                    if(data!=""){
                        // Loop, create markup and append, then call chart function
                        $.each(data, function(index, element){
                            title=element['name'];
                            data=element['data'];
                            category=element['category'];
                            var div='<div class="col-lg-4 col-md-4 mb15"><div class="row box" style="padding: 10px;margin-left: 0px;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);"><div class="col-lg-7 col-md-7"><div class="chart" id="'+index+'" style="height: 320px; margin: 0 auto"></div></div><div class="col-lg-5 col-md-5 domain-box"> <table class="domain-table table"><tr><th colspan=2 style="font-size:13px;color:black   ;">Red Domain:OCT-Wk4</th></tr><tr><td>Platforms-MMX</td><td>5</td></tr><tr><td>ACX-Protocol</td><td>5</td></tr><tr><td>Protocols-TPTX</td><td>5</td></tr><tr><td class="cell-green">Platforms-TPTX</td><td class="cell-green">0</td></tr><tr><td>ACX-Platform</td><td>5</td></tr><tr><td>Services</td><td>5</td></tr><tr><td>KERNL-MNGBLT</td><td>5</td></tr><tr><td>Protocols-MMX</td><td>5</td></tr></table></div></div> </div>';
                            $('#regression-chart').append(div);
                            generateStackedchart(title,category,data,index);
                            
                        });
                    }else{

                    }
      });

         request.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
      });



// generateStackedchart(title,category,data,id);

function generateStackedchart(title,category,data,id){

        var options = {
         chart: {
            type: 'column',
            borderRadius:5,
            borderColor: "#ccc",
            borderWidth: 0,
            spacingBottom: 40,
            style: {
                    fontFamily: '"Droid Sans", Helvetica, Arial, sans-serif'
            }
        },
        title: {
            text: title
        },
        xAxis: {
            categories: category
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                     color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
               //  x: -10,
               //  y:25,
               //  backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
               //  borderColor: '#CCC',
               //  borderWidth: 1,
               //  borderRadius:2,
               //  align: 'right',
               //  itemMarginBottom: 4,
               //  verticalAlign: 'bottom',
               //  backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
               //  shadow: false,
               //  squareSymbol: true,
               //  symbolHeight: 10,
               // symbolPadding: 10,
               // symbolRadius: 2,
               // symbolWidth: 10,
               enabled:false

            },
        credits: {
                enabled: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {

                // stacking: 'none'
                // dataLabels: {
                //     enabled: true,
                //     // color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                // }
            },
             series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },
        // colors: ['#3BBEE3', '#0AB050', '#92D050', '#B8B400', '#FF9900', '#FF0000' ,'#B40404','#8A0808','#61380B'],
        colors: ['#FF0000','#008000'],
        series: data
           
        };        

    $('#'+id).highcharts(options);

   
 }

