agGrid.LicenseManager.setLicenseKey("Juniper_Networks_Site_1Devs_31_October_2017__MTUwOTQwODAwMDAwMA==038f1a6883b4b4741e1d091fcf55b194");
agGrid.initialiseAgGridWithAngular1(angular);
var module = angular.module("app", ["agGrid"]);
module.controller('appController', function($scope, $filter, $http) {
  function getMainMenuItems(params) {
    return [ 'pinSubMenu', 'separator', 'resetColumns' ];
  }

  function logrenderer(params){
  // console.log(activeindex);

  html='';
  if(activeindex=='Ixia'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/ixialog/'+params.data[5]+'.log" target="_blank">'+params.value+'</a>'

  }
  else if(activeindex=='BSD'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/bsdlog/'+params.data[4]+'.log" target="_blank">'+params.value+'</a>'

  }
  else if(activeindex=='JPG'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/jpglog1/'+params.data[4]+'.log" target="_blank">'+params.value+'</a>'

  }else if(activeindex=='Agilent'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/agilentlog/'+params.data[3]+'.log" target="_blank">'+params.value+'</a>'

  }else if(activeindex=='Spirent'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/spirentlog/'+params.data[4]+'.log" target="_blank">'+params.value+'</a>'

  }else if(activeindex=='Switch'){
    html='<a href="https://systest.juniper.net/projects/ixia-sanity/switchlog/'+params.data[5]+'.log" target="_blank">'+params.value+'</a>'

  }
  return html;
}

function onBtExport() {
  var params = {
    fileName: activeindex+'.csv'
  };
  $scope.gridOptions.api.exportDataAsCsv(params);
}

// Record count function
function showCount(){
  strCounter = "<strong>Record Count: </strong>"+$scope.gridOptions.api.getModel().getRowCount();
  if(activeindex=='Ixia' || activeindex=='Agilent' || activeindex=='Spirent')
   cron='<span class="pull-right"><strong>Cron Frequency</strong> : 4:00 AM and 4:00 PM Daily</span>';
 else
   cron='<span class="pull-right"><strong>Cron Frequency </strong> : Hourly</span>';

 $("#recordsCount").html(strCounter+cron);
}
var mgrPassed = '',
statusPassed = '';
//-------------function for generetae Table--------------
function generateTable(activeindex,resourceData){
  // debugger;
  showhidediv(activeindex);
  activeindex=activeindex;
    // console.log(resourceData[activeindex[1]]);
    var link=resourceData[activeindex]['link'],
    title=resourceData[activeindex]['title'];
    if(link!=""){
      if(title=='Link-Status'){
       link = link+'?to=' + linkfinderDate + '&execMode=Both&GENERATE_REPORT=GENERATE_REPORT';
     }

      // create ag-grid table for Ixia,bsd,agilent,switches,spirent and bsd tabs
      if((title=='IXIA')|| (title=='Agilent') || (title=='Spirent')|| (title=='BSD')|| (title=='JPG')|| (title=='Switch')){
        $('.tab-content').html('<div id="resource-Grid-'+title+'" ag-grid="gridOptions" class="grid ag-grid-wrap"></div>');
        $scope.gridOptions = {
          columnDefs: [],
          rowData: null,
          debug: true,
          enableSorting: true,
          enableFilter: true,
          getMainMenuItems: getMainMenuItems,
          enableColResize: true,
          headerHeight:40,
          suppressContextMenu: true,
          defaultColDef:{
            menuTabs: [ 'columnsMenuTab', 'filterMenuTab', 'generalMenuTab' ]
          },
          overlayNoRowsTemplate: '<span style="padding: 10px; border: 2px solid #444; background: lightgoldenrodyellow;">Work In Progress</span>',
          getRowStyle: function(params) {
            // Columns to check for "Fail" condition
            // Ixia: [5 t0 10].
            // Agilent : [4,5].
            // Spirent : [4,7].
            // Bsd : [4 ,5,6].
            // Jpg :[4,5,6,7].
            // Switches: [5].


            switch( title){
              case 'IXIA':
              if (  params.data[6] === 'Fail' || params.data[7] === 'Fail' || params.data[11] === 'Fail' || params.data[8] === 'Fail' || params.data[9] === 'Fail' || params.data[10] === 'Fail') {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if ( params.data[11] === 'Pass' && params.data[6] === 'Pass' && params.data[7] === 'Pass' && params.data[8] === 'Pass' && params.data[9] === 'Pass' && params.data[10] === 'Pass' ) {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
              case 'Spirent':
              if (params.data[5] === 'Fail' || params.data[8] === 'Fail' ) {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if ( params.data[5] === 'Pass' &&  params.data[8] === 'Pass' ) {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
              case 'JPG':
              if (params.data[6] === 'Fail' || params.data[11] === 'Fail' || params.data[8] === 'Fail' || params.data[9] === 'Fail' || params.data[10] === 'Fail' ) {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if (params.data[6] === 'Pass'|| params.data[11] === 'Pass' && params.data[8] === 'Pass' && params.data[9] === 'Pass' && params.data[10] === 'Pass' ) {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
              case 'BSD':
              if ( params.data[7] === 'Fail'|| params.data[5] === 'Fail'|| params.data[6] === 'Fail') {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if ( params.data[7] === 'Pass'|| params.data[5] === 'Pass' || params.data[6] === 'Pass') {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
              case 'Agilent':
              if (params.data[4] === 'Fail' || params.data[5] === 'Fail' ) {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if ( params.data[4] === 'Pass' &&  params.data[5] === 'Pass' ) {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
              case 'Switch':
              if ( params.data[6] === 'Fail' ) {
                return{
                  'background-color': '#FF0000',
                  'color': '#000'
                };
              }
              else if ( params.data[6] === 'Pass' ) {
                return{
                  'background-color': '#92D050',
                  'color': '#000'
                };
              }
              break;
            }
            return null;
          },
          onGridReady: function(params) {
            params.api.sizeColumnsToFit();
          },
          getRowHeight: function(params) {
                var l = 1;
                $.each(params.data,function(){
                    if(this.length > l)
                    {
                        l = this.length;
                    }
                });
                return 30 * (Math.floor(l / 40) + 1) + 10;
            }

        };
        columnDefs = [];
        rowData = [];
// console.log(link);
$.getJSON(link,function(data){
  // console.log(data);
  hidePageLoader();
  // tabname=data['name'];
  // push Columndef Data to variable
  $.each(data['thead'], function(index, element){

    if(element=="Logs"){
      // console.log(element);

      columnDefs.push({headerName:element,field:''+index+'',cellRenderer:logrenderer,
       cellStyle: function(params) {
         if (params.value=='Pass' || params.value == 'Fail') {
          return {'text-align':'center'};
        } else {
          return {'text-align':'left'};
        }
      }
    });
    }else if(element=="Refresh"){
  // if(activeindex=='Spirent'){
    // columnDefs.push({headerName:element,field:''+index+'',hide:true});
    // }else{
      columnDefs.push({headerName:element,field:''+index+'',cellRenderer:function(params){
        html='';
        if(activeindex=='Ixia'){
          html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[5]+'" data-tab="ixia" type="button">Refresh</button>';
        }
        else if(activeindex=='BSD'){
         html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[4]+'" data-tab="bsd" type="button">Refresh</button>';
       }
       else if(activeindex=='JPG'){
        html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[4]+'" data-tab="jpg" type="button">Refresh</button>';

      }else if(activeindex=='Agilent'){
        html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[3]+'" data-tab="agilent" type="button">Refresh</button>';

      }else if(activeindex=='Spirent'){
        html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[4]+'" data-tab="spirent" type="button">Refresh</button>';

      }else if(activeindex=='Switch'){
        html='<div class="element-loader"></div><button class="btn btn-default btn-xs refresh-btn" data-hostname="'+params.data[5]+'" data-tab="switches" type="button">Refresh</button>';

      }
      return html;
    },
    cellStyle:{'text-align':'center'}
  });
// }
}
else if(element=="Filter"){
  columnDefs.push({headerName:element,field:''+index+'', width:70,hide:true});
}
else{
  columnDefs.push({headerName:element,field:''+index+'',
    cellStyle: function(params) {
     if (params.value=='Pass' || params.value == 'Fail') {
      return {'text-align':'center'};
    } else {
      return {'text-align':'left'};
    }
  }
});
}
});

// push Row Data to variable
$.each(data['data'], function(index, element){
  rdata={};
  $.each(element, function(indexs, elements){
    // console.log(index);
    rdata[indexs] = elements;           
  });
  rowData.push(rdata);
});

// $('#resource-div').height(calcDataTableHeight());
$('#tabContent').height(calcDataTableHeight()-10);

// $('#resource-wrap').height(calcDataTableHeight());
var eGridDiv = document.getElementById('resource-Grid-'+title+'');
new agGrid.Grid(eGridDiv, $scope.gridOptions);
$scope.gridOptions.api.setColumnDefs(columnDefs);
$scope.gridOptions.api.setRowData(rowData);
$scope.gridOptions.api.sizeColumnsToFit();
if(getQueryStringParam('manager'))
  filterMgr();
showCount();
});
showCount();
}
else if( title=='Routers' ){
  $('.tab-content').html('<div id="resource-Grid-'+title+'" ag-grid="gridOptions" class="grid ag-grid-wrap"></div>');
  $('#tabContent').height(calcDataTableHeight()-10);
  var columnDefs = [
  {headerName: "Name", field: "name", filter: "text"},
  {headerName: "Domain", field: "domain", filter: "text"},
  {headerName: "Owner", field: "owner", filter: "text"},
  {headerName: "Manager", field: "manager", filter: "text"},
  {headerName: "Status", field: "status", filter: "text", cellRenderer: getcolor}
  ]


  $scope.gridOptions = {
    columnDefs: columnDefs,
    rowData: [],
    enableSorting: true,
    enableFilter: true,
    getMainMenuItems: getMainMenuItems,
    onGridReady: function(params) {
      params.api.sizeColumnsToFit();
    }
  }
  function getcolor() {}
  getcolor.prototype.init = function(params) {
    if (params.value === "" || params.value === undefined || params.value === null || params.value === '|') {
      this.eGui = '';
    } else {
      var value = params.value,
                // pop = params.data[params.column.colId+"_lt"].replace(/,/g,'<br>');
                pop = params.data[params.column.colId+"_lt"];
                if (value == 'green') value = '<div class="circle green"></div>';
                if (value == 'na') value = '<div class="circle grey" data-content="Not Executed"></div>';
                if (value == 'red') value = '<div class="circle red" data-e="' + params.data.engineer + '" data-m="' + params.data.manager + '" data-d="' + params.data.domain + '" data-h="' + params.colDef.headerName + '" data-c="' + pop + '"></div>';
                this.eGui = value;
              }
            };
            getcolor.prototype.getGui = function() {
              return this.eGui;
            };

            var eGridDiv = document.getElementById('resource-Grid-'+title+'');
            new agGrid.Grid(eGridDiv, $scope.gridOptions);

            $.getJSON(link,function(data){
              $scope.gridOptions.api.setRowData(data.data);
            });
            hidePageLoader();
          }
          else{
            hidePageLoader();
        //Create Iframe
        var ifr = document.createElement('iframe')
        ifr.id='frameid';
        ifr.src =link;
        $(".tabContent").html(ifr);

      }
    }
    else{
      var text="<span class='col-md-12 text-green' style='text-align: center;font-size: 23px;margin-top: 20px;'>Work In Progress<span>";
      $(".tabContent").html(text);
    }

  }

  url="api/index.php?action=resourcejson";
  showPageLoader();
  var request = $.ajax({
    url: url,
    type: "GET",
    dataType: "JSON"
  });

  request.success(function(resourceData) {
    hidePageLoader();
    resourceData=resourceData;
    // console.log(resourceData);
         // -----------Loop, create and append Tabs-------------------
         var tabs='';
         $.each(resourceData, function(index, element){
          title=element['title'];
          tabs+='<button type="button" class="dn btn tab-btn mr10" data-tab="'+index+'">'+title+'</button>';
        });
         tabs+='<div class="pull-right" id="right-div"><input placeholder="Search&hellip;" type="text" class="search"/><button onclick="onBtExport()" class="pull-right btn btn-dark-blue ml15 export-btn"><img title="Export to CSV" src="img/excel-icon.png" alt="" height="20px"> Export</button></div>';
         tabs+='<div class="tab-content tabContent h100" id="tabContent"></div>';
         $('#resource-wrap').append(tabs);

         if( getQueryStringParam('source') == 'cms' ){
          var activeTab = getQueryStringParam('tab');
          if( activeTab != null)
            $('#resource-wrap .tab-btn[data-tab="'+ activeTab +'"]').addClass('activebtn').removeClass('dn');
        }
        else{
          $('#resource-wrap .tab-btn:first').addClass('activebtn');
          $('#resource-wrap .tab-btn').removeClass('dn');
        }
          activeindex=$('#resource-wrap .tab-btn.activebtn').attr('data-tab');//get active tab href
          $("#resource-wrap .tab-btn").click(function(e){
            showPageLoader();
            var $this = $(this);
            activeindex=$(this).attr('data-tab');
            $this.closest('div').find('>.activebtn').removeClass('activebtn').addClass('inactivebtn');
            $this.addClass('activebtn');
            generateTable(activeindex,resourceData);
            $('#frameid').height(calcDataTableHeight());

          });

          generateTable(activeindex,resourceData);
          $('#frameid').height(calcDataTableHeight());
        });

request.fail(function(jqXHR, textStatus) {
  alert( "Request failed: " + textStatus );
});


// Ag-grid filter function
$(document).on('input','.search',function(e){
  value=$(this).val();
  $scope.gridOptions.api.setQuickFilter(value);
  showCount();
});
function filterMgr(){
  mgrPassed = getQueryStringParam('manager');
  statusPassed = getQueryStringParam('status');
  var FilterComponent1 = $scope.gridOptions.api.getFilterInstance('3');
  FilterComponent1.selectNothing ();
  FilterComponent1.selectValue (mgrPassed);
  var FilterComponent2 = $scope.gridOptions.api.getFilterInstance('14');
  FilterComponent2.selectNothing ();
  FilterComponent2.selectValue ('f');
  $scope.gridOptions.api.onFilterChanged();
}
//Refresh button click function
$(document).on('click','.refresh-btn',function(e){
  var uid = getCookie('uid');
  var rowNode = $scope.gridOptions.api.rowModel.rowsToDisplay[$(this).closest('.ag-row').attr('row')];
  hostname=$(this).attr('data-hostname');
  tabname=$(this).attr('data-tab');
  url="http://jdiregression.juniper.net/sanitycheck/solosanity/refresh.php";
       // url="json/refresh.json";
       var t=$(this);
       $(this).addClass('dn');
       $(this).parent().find('.element-loader').css('display','block');
       $.ajax({
        url:url,
        type:"GET",
        data:{'hostname':hostname,"device":tabname, "uid": uid},
        dataType:"JSON",
        success:function(data){
          console.log(data);
          t.removeClass('dn');
          t.parent().find('.element-loader').css('display','none');
          if(data.result=='success'){
              //  Refresh current row
              var updatedNodes = [];
              rowNode.data=data['data'];
              updatedNodes.push(rowNode);
              newData=data['data'];
              $scope.gridOptions.api.refreshRows(updatedNodes);

            }
          }
        })

     });

// record cound show/hide
function showhidediv(activeindex){
  if(activeindex=='Link-status' || activeindex=='Params-status'){
    $('#recordsCount').css('display','none');
    $('#right-div').css('display','none');
  }
  else{
    $('#recordsCount').css('display','block');
    $('#right-div').css('display','block');
  }

}

  // disable ctrl+f 
  window.addEventListener("keydown",function (e) {
    if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) { 
      $('.search').focus();
      e.preventDefault();
    }
  });
});
