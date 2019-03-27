var option = '',
  textColor = 'rgba(255, 255, 255, 0.85)';
// option = '-white';
// if( option == '-white'){
//   textColor = '#083268';
// }

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

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd
} 

if(mm<10) {
    mm='0'+mm
} 
current_date =yyyy +'-'+mm+'-'+dd;
// console.log(today);
title="";
// url="json/infra.json?current_date="+current_date;
url="api/index.php?action=infrafailures";
showPageLoader();

// Ajax Request Api Call
/*
Commented because reddomain JSON obj is part of the infra.json
var getRedomain = function(){
  $.get("api/index.php?action=reddomain", function(data, status){
    data= JSON.parse(data)
    title=data['title'];

    tabledata=data['data'];

    var table='<table class="domain-table table"><tr><th colspan=2 class="thead">'+title+'</th></tr>';
    $.each(tabledata, function(index, elements){
      table+='<tr>';
      $.each(elements, function(k, v) {
        table+='<td>'+k+'</td><td>'+v+'</td>';
      });
      table+='</tr>';
    });
    table+='</table>';
    $('.domain-box').append(table); 
  });
}
*/
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

      title = element['name'];
      data = element['data'];
      category = element['category'];
      var rowdiv = "<div class='row boxdiv'></div>";
      var div = '';
      div += '<div class="col-md-3 col-sm-4 child" id="child-' + index + '">';
      div += '  <div class="row mb15 child-row' + option + '">';
      // if( typeof(element.reddomain) !== 'undefined' ){
      //   div += '    <div class="col-md-7"><div class="chart" id="'+index+'"></div></div>';
      //   div += '    <div class="col-md-5 domain-box"> <div></div> </div>';
      // }
      // else{
        div += '    <div class="col-md-12"><div class="chart" id="'+index+'"></div></div>';
      // }
      div += '  </div>';
      div += '</div>';
      $('#regression-chart').append(div);                        
      generateStackedchart(title,category,data,index);

      // Begin Red Domain
        // if( typeof(element.reddomain) !== 'undefined' ){
        //   var rd = element.reddomain,
        //       rdtitle = rd['title'],
        //       rdtabledata = rd['data'];

        //   var table = '<table class="domain-table table"><tr><th colspan=2 class="thead">'+rdtitle+'</th></tr>';
        //   $.each(rdtabledata, function(index, elements){
        //     table+='<tr>';
        //     $.each(elements, function(k, v) {
        //       table+='<td>'+k+'</td><td>'+v+'</td>';
        //     });
        //     table+='</tr>';
        //   });
        //   table+='</table>';
        //   $('#child-' + index + ' .domain-box').append(table); 
        // }
      // End Red Domain
    });
    
    // Add 3 div per row
    /*$('div.regression-chart > div').each(function(i) {
      if( i % 3 == 0 ) {
        $(this).nextAll().andSelf().slice(0,3).wrapAll('<div class="row"></div>');
      }
    });*/
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
            borderColor: "#999",
            borderWidth: 0,
            //spacingBottom: 40,
            backgroundColor:'transparent',
            style: {
                    fontFamily: '"Droid Sans", sans-serif'
            },

        },
        title: {
            text: title,
            style: {
                     color: textColor
            }
        },
        xAxis: {
            categories: category,
            gridLineColor: '#666666',
            lineColor:'#666666',
            tickColor:'#666666',
            labels: {
              rotation:320,
              formatter: function () {
                      return '<span style="fill: ' + textColor + ';">' + this.value + '</span>';
              }
          },

        },
        yAxis: {
            gridLineColor: textColor,
            gridLineWidth:0.3,
            gridLineWOpacity:0.5,
            min: 0,
            title: {
                text: ''
            },
             // labels: {
             //  style: {
             //        color: textColor
             //      }
             //  },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'normal',
                    color: textColor
                     // color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
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
            pointFormat: '{series.name}: {point.y}'
        },
        plotOptions: {
          column: {

            stacking: 'normal',

              // dataLabels: {
              //     // enabled: true,
              //     style: {
              //         fontWeight: 'normal',
              //         color: textColor
              //     }
              //     // color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
              // }
          },
          series: {
            // colorByPoint: true,
            borderWidth: 0,
            //   // dataLabels: {
            //   //     enabled: true,
            //   //     format: '{point.y:.1f}%',
            //   //     style: {
            //   //         fontWeight: 'normal',
            //   //         color: textColor
            //   //     }
            //   // },
            pointWidth: 20,
            groupPadding: 0
          },


        },
        // colors: ['#3BBEE3', '#0AB050', '#92D050', '#B8B400', '#FF9900', '#FF0000' ,'#B40404','#8A0808','#61380B'],
        // colors: ['#9276EC', '#846BD5', '#705CB9', '#5C4B9D', '#483A7C', '#34295B', '#21193A', '#140F24' ],
        // colors: ['#17BDD9', '#119FB9', '#0C849A', '#076477', '#044452', '#003641', '#012B34', '#001216' ],
        colors: ['#3BBEE3', '#076477', '#B8B400', '#0C849A', '#92D050', '#FF9900', '#17BDD9', '#076477', '#3BBEE3','#0AB050'],
        // colors: ['rgb(124, 181, 236)'],
        // colors:['#ff8000','#00ff00', '#0000ff', '#ff00bf'],
        // colors:['rgb(238, 85, 34)','rgb(183, 242, 255)','rgb(102, 238, 102)','rgb(86, 156, 221)'],
        series: data
           
        };        

    $('#'+id).highcharts(options);

   
 }


