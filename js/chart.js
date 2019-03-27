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

// //----------------Create Tab------------------
// var tabs='<ul class="nav nav-tabs" id="tabs">';
//     tabs+='<li><a href="#function">Function View</a></li>';
//     tabs+='<li><a href="#index">Domain View</a></li>';
//     tabs+='</ul>';
//     $('#regression-tab').append(tabs);
//     $('#tabs li:first').addClass('active');
//      $("#tabs a").click(function(e){
//         // alert();
//         e.preventDefault();
//         $(this).tab('show');
//         // activeindex=$(this).attr('href');
//         // generateStackedchart(title,category,data,index);
//     });

//----------------Ajax Call-------------------   
    title="";
    // url="api/index.php?action=infrafailures";
    url="json/chart-data.json";
    showPageLoader();

// -------Ajax Request Api Call------------------
    var request = $.ajax({
            url: url,
            type:"GET",
            dataType: "JSON"
      });

     request.done(function(data) {
      hidePageLoader();
      // console.log(data);
      
                if(data!=""){
                    //----------------Create Tab------------------
                    var tabs='<ul class="nav nav-tabs" id="tabs">';
                        $.each(data, function(index, element){
                            // console.log(index);
                          title=element['title'];  
                        tabs+='<li><a href="#'+index+'">'+title+'</a></li>';
                        });
                        tabs+='</ul>';
                        $('#regression-tab').append(tabs);
                        $('#tabs li:first').addClass('active');
                         $("#tabs a").click(function(e){
                            // alert();
                            $('#regression-chart').empty();
                            e.preventDefault();
                            $(this).tab('show');
                            activeindex=$(this).attr('href');
                            getChartdata(activeindex,data);
                        });
                        activeindex=$('#tabs .active a').attr('href');//get active tab href
                        getChartdata(activeindex,data);
                    
                    
                }else{

                }
    });

    request.fail(function(jqXHR, textStatus) {
      alert( "Request failed: " + textStatus );
    });

function getChartdata(activeindex,data){
    activeindex=activeindex.split('#');
    // console.log(activeindex);
    // Loop, create markup and append, then call chart function
    $.each(data[activeindex[1]]['data'], function(index, element){
        // console.log(element);
        title=element['name'];
        data=element['data'];
        category=element['category'];
        var div='<div class="col-lg-3 col-md-3 mb15" id="chart-box"><div class="row box" style="padding: 10px;margin-left: 0px;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);"><div class="chart" id="'+index+'" style="height: 320px; margin: 0 auto"></div></div> </div>';
        $('#regression-chart').append(div);
        generateStackedchart(title,category,data,index);
    });
}


//------------Fuction for generate Chart--------------------

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
                stacking: 'normal'
                // dataLabels: {
                //     enabled: true,
                //     // color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                // }
            }
        },
        colors: ['#3BBEE3', '#0AB050', '#92D050', '#B8B400', '#FF9900', '#FF0000' ,'#B40404','#8A0808','#61380B'],
        series: data
           
        };        

    $('#'+id).highcharts(options);

   
 }

