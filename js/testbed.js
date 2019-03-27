agGrid.LicenseManager.setLicenseKey("Juniper_Networks_MultiApp_1Devs13_March_2019__MTU1MjQzNTIwMDAwMA==df4f6eade78b642104cba2cf8ccdd0b3");
agGrid.initialiseAgGridWithAngular1(angular);
var module = angular.module("app", ["agGrid"]);
module.controller("drwiseController", function($scope, $filter, $http){
    var gDate;
    var mgruid;
    function getMainMenuItems(params) {
            return [ 'pinSubMenu', 'separator', 'resetColumns' ];
    }

    //editmgr = true;
    if(isManager == 't'){
        var editmgr = true;
    }
    else {
        var editmgr = false;
    }



    var columnDefsexcluded = [
    {headerName: 'Team', field: 'team' ,filter: 'agTextColumnFilter',width:160,pinned:'left',hide:true},
    {headerName: 'Domain', field: 'domain' ,filter: 'agTextColumnFilter',width: 210,pivot: true,pinned:'left',suppressSizeToFit: true},
    {headerName: 'Domain Owner', field: 'domain_owner' ,filter: 'agTextColumnFilter',width:140,pivot: true,suppressSizeToFit: true},
    {headerName: '<span>&Sigma;</span> Scripts', field: 'tot_script' ,filter: 'agTextColumnFilter',width:75,cellRenderer:function(params){
        if(params.value=='' || params.value==null)
            return 0;
        else
         return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_script_name.json' target='_blank'>"+params.value+"</a>";
    }},
     {headerName: '<span>&Sigma;</span>DBP Not Present', field: 'no_dbp',hide:true ,filter: 'agTextColumnFilter',width:110,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_no_dbp'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: '<span>&Sigma;</span> Prefer Params', field: 'tot_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_all_dbp'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-Prefer Params : To be fixed', field: 'fail_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_pp_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-Prefer Params', field: 'pass_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_pp_pass'target='_blank'>"+params.value+"</a>";
    }},
    //
    {headerName: '<span>&Sigma;</span> JPG Params', field: 'tot_jpg' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_tot'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-JPG Params : To be fixed', field: 'jpg_fp' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-JPG Params', field: 'jpg_pp' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_pass'target='_blank'>"+params.value+"</a>";
    }},


//
    {headerName: '<span>&Sigma;</span> Normal Params', field: 'tot_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_all_p'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-Normal Params : To be fixed', field: 'fail_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_p_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-Normal Params', field: 'pass_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_p_pass'target='_blank'>"+params.value+"</a>";
    }},
        {headerName: 'Hardware Pending', field: 'hw_pending' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return 0;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_hw_pending.json'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Overall Status', field: 'status' ,filter: 'agTextColumnFilter',width:75,pivot: true,hide:true},
    {headerName: 'Exclusion Reason', field: 'exld_cmnt' ,filter: 'agTextColumnFilter',width:75,pivot: true,hide:true},
    {headerName: 'Last Sync (IST)', field: 'time' ,filter: 'agTextColumnFilter',width:170,pivot: true}
]


var columnDefstoday = [
    {headerName: 'Team', field: 'team' ,filter: 'agTextColumnFilter',width:160,pinned:'left',hide:true},
    {headerName: 'Domain', field: 'domain' ,filter: 'agTextColumnFilter',width: 210,pivot: true,pinned:'left',suppressSizeToFit: true},
    {headerName: 'Domain Owner', field: 'domain_owner' ,filter: 'agTextColumnFilter',width:140,pivot: true,suppressSizeToFit: true},
    {headerName: '<span>&Sigma;</span> Scripts', field: 'tot_script' ,filter: 'agTextColumnFilter',width:75,cellRenderer:function(params){
        if(params.value=='' || params.value==null)
            return 0;
        else
         return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_script_name.json'target='_blank'>"+params.value+"</a>";
    }},
     {headerName: '<span>&Sigma;</span>DBP Not Present', field: 'no_dbp',hide:true ,filter: 'agTextColumnFilter',width:110,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_no_dbp'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: '<span>&Sigma;</span> Prefer Params', field: 'tot_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_all_dbp'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-Prefer Params : To be fixed', field: 'fail_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_pp_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-Prefer Params', field: 'pass_pp' ,filter: 'agTextColumnFilter',width:110,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_pp_pass'target='_blank'>"+params.value+"</a>";
    }},
    //
    {headerName: '<span>&Sigma;</span> JPG Params', field: 'tot_jpg' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_tot'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-JPG Params : To be fixed', field: 'jpg_fp' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-JPG Params', field: 'jpg_pp' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_jpg_pass'target='_blank'>"+params.value+"</a>";
    }},


//
    {headerName: '<span>&Sigma;</span> Normal Params', field: 'tot_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_all_p'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Fail-Normal Params : To be fixed', field: 'fail_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_p_fail'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Pass-Normal Params', field: 'pass_np' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return params.value;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_p_pass'target='_blank'>"+params.value+"</a>";
    }},
    {headerName: 'Hardware Pending', field: 'hw_pending' ,filter: 'agTextColumnFilter',width:75,pivot: true,cellRenderer:function(params){
        if(params.value=='' || params.value==null || params.value=='0')
            return 0;
        else
            return "<a href='http://jdi-reg-tools.juniper.net/params_status_fusion/dbp_logs/JDI-REG-"+params.data.team+"/"+ params.data.domain+"_hw_pending.json'target='_blank'>"+params.value+"</a>";
    }},
    
    {headerName: 'Overall Status', field: 'status' ,filter: 'agTextColumnFilter',width:75,pivot: true,hide:true},
    {headerName: 'Last Sync (IST)', field: 'time' ,filter: 'agTextColumnFilter',width:170,pivot: true}
]

      var columnDefssum = [
    {headerName: 'Team', field: 'team' ,filter: 'agTextColumnFilter',width:180,pinned:'left'},
    {headerName: '<span>&Sigma;</span> Domains', field: 'domain' ,filter: 'agTextColumnFilter',width:75,pivot: true,suppressSizeToFit: true},
    {headerName: 'Pass Domains', field: 'pass_dom' ,filter: 'agTextColumnFilter',width:75,pivot: true,suppressSizeToFit: true},
    {headerName: 'Fail Domains', field: 'fail_dom' ,filter: 'agTextColumnFilter',width:75,pivot: true,suppressSizeToFit: true},
    {headerName: '<span>&Sigma;</span> Scripts', field: 'tot_script' ,filter: 'agTextColumnFilter',width:150},
    {headerName: '<span>&Sigma;</span> No DBP', field: 'no_dbp' ,filter: 'agTextColumnFilter',width:150,hide:true},
    {headerName: '<span>&Sigma;</span> Prefer Params', field: 'tot_pp' ,filter: 'agTextColumnFilter',width:150,pivot: true},
    {headerName: '<span>&Sigma;</span> Fail-Prefer Params', field: 'fail_pp' ,filter: 'agTextColumnFilter',width:150,pivot: true},
    {headerName: '<span>&Sigma;</span> Pass-Prefer Params', field: 'pass_pp' ,filter: 'agTextColumnFilter',width:130,pivot: true},
    {headerName: '<span>&Sigma;</span> JPG Params', field: 'tot_jpg' ,filter: 'agTextColumnFilter',width:150,pivot: true},
    {headerName: '<span>&Sigma;</span> Fail-JPG Params', field: 'jpg_fp' ,filter: 'agTextColumnFilter',width:150,pivot: true},
    {headerName: '<span>&Sigma;</span> Pass-JPG Params', field: 'jpg_pp' ,filter: 'agTextColumnFilter',width:130,pivot: true},
    {headerName: '<span>&Sigma;</span> Normal Params', field: 'tot_np' ,filter: 'agTextColumnFilter',width:150,pivot: true},
    {headerName: '<span>&Sigma;</span> Fail-Normal Params', field: 'fail_np' ,filter: 'agTextColumnFilter',width:130,pivot: true},
    {headerName: '<span>&Sigma;</span> Pass-Normal Params', field: 'pass_np' ,filter: 'agTextColumnFilter',width:130,pivot: true},
    {headerName: '<span>&Sigma;</span> Hardware Pending Params', field: 'hw_pending' ,filter: 'agTextColumnFilter',width:130,pivot: true}
 
    ]

    $scope.gridOptionacx = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,
       filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
	    headerHeight: 60,
        onColumnResized: function(params) {
            console.log(params);
        },
    animateRows:true,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab']
        },
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updateprs&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
	.success(function(data, status, headers, config){
             })
            .error(function(data, status, headers, config){
      console.log(data);
    });
     },
     rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },

        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };

    $scope.gridOptionkm= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
	    headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
	     })
	    .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: { 
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };

        $scope.gridOptionbbe= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,
       filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
        filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };


        $scope.gridOptionex= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



$scope.gridOptionlegex= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };


$scope.gridOptionlegqfx= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



$scope.gridOptionmmx= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



$scope.gridOptionci= {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };


$scope.gridOptionqfx = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



$scope.gridOptionrpd = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };

    $scope.gridOptionrpd = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };


$scope.gridOptionserv = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };


$scope.gridOptionexcluded = {
        columnDefs:  columnDefsexcluded,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



$scope.gridOptiontptx = {
        columnDefs: columnDefstoday,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 60,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.status;
            return  status == 'PASS';
        },
        'params_fail': function(params) {
            var status = params.data.status;
            return  status == 'FAIL';
        }
    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };



    $scope.gridOptionsum= {
        columnDefs: columnDefssum,
        rowData:null,
       enableSorting:true,    filter: 'agTextColumnFilter',

    // pass in additional parameters to the text filter
    filterParams: {apply: true, newRowsAction: 'keep'},
enableFilter: true,
        suppressContextMenu: true,
        enableRangeSelection: true,
        enableColResize: true,
        animateRows:true,
        headerHeight: 50,
        suppressColumnMoveAnimation:true,
        getMainMenuItems: getMainMenuItems,
        suppressMenuHide:true,
        defaultColDef:{
            menuTabs:['columnsMenuTab', 'filterMenuTab', 'generalMenuTab'],
        },
       
 
        onCellValueChanged: function(event) {
            $http({
                url:'api/index.php?action=updatescope&uid='+gUid,
                method:'POST',
                data: {'data': event.data}
            })
            .success(function(data, status, headers, config){
         })
        .error(function(data, status, headers, config){
      console.log(data);
    });
        },rowClassRules: {
        // row style function
        'script_zero': function(params) {
            var tot_script = params.data.tot_script;
            return  tot_script < 1;
        },
        'params_pass': function(params) {
            var status = params.data.fail_dom;
            var team = params.data.team;
            return  status == '0' & team != 'Total';
        },
        'params_fail': function(params) {
            var status = params.data.fail_dom;
            var team = params.data.team;
            return  status != '0' & team != 'Total';
        },
        'params_total': function(params) {
            var team = params.data.team;
            return  team == 'Total';
        }

    },
        excelStyles: [
        {
            id:'header',
            interior: {
                color: "#083268", pattern: 'Solid'
            },
            font: {
                color: "white"
            }
        }
        ],
        onGridReady: function(params) {
        }
    };




    function getdata(){
        showPageLoader();
        $http.get('api/index.php?action=getjsonmanager&uid='+gUid).success(function(data) {
            //debugger;
            $scope.gridOptionsum.api.setRowData(data.summary); 
            $scope.gridOptionsum.api.sizeColumnsToFit();
            $scope.gridOptionacx.api.setRowData(data.acx); 
            $scope.gridOptionacx.api.sizeColumnsToFit();
            $scope.gridOptionbbe.api.setRowData(data.bbe); 
            $scope.gridOptionbbe.api.sizeColumnsToFit();
            $scope.gridOptionex.api.setRowData(data.ex); 
            $scope.gridOptionex.api.sizeColumnsToFit();
            $scope.gridOptionkm.api.setRowData(data.km); 
            $scope.gridOptionkm.api.sizeColumnsToFit();
            $scope.gridOptionlegex.api.setRowData(data.legacyex); 
            $scope.gridOptionlegex.api.sizeColumnsToFit();
            $scope.gridOptionlegqfx.api.setRowData(data.legacyqfx); 
            $scope.gridOptionlegqfx.api.sizeColumnsToFit();
            $scope.gridOptionmmx.api.setRowData(data.mmx); 
            $scope.gridOptionmmx.api.sizeColumnsToFit();
            $scope.gridOptionqfx.api.setRowData(data.qfx); 
            $scope.gridOptionqfx.api.sizeColumnsToFit();
            $scope.gridOptionrpd.api.setRowData(data.rpd); 
            $scope.gridOptionrpd.api.sizeColumnsToFit();
            $scope.gridOptionserv.api.setRowData(data.services); 
            $scope.gridOptionserv.api.sizeColumnsToFit();
            $scope.gridOptiontptx.api.setRowData(data.tptx); 
            $scope.gridOptiontptx.api.sizeColumnsToFit();

            $scope.gridOptionci.api.setRowData(data.ci); 
            $scope.gridOptionci.api.sizeColumnsToFit();
            $scope.gridOptionexcluded.api.setRowData(data.excluded); 
            $scope.gridOptionexcluded.api.sizeColumnsToFit();

            hidePageLoader();

    $('#exportNew').click(function(){
        var params = {
        fileName: 'pr_tracker.csv'
    };
      $scope.gridOptionstoday.api.exportDataAsCsv(params);
    });

$('#exportScope').click(function(){
        var params = {
        fileName: 'pr_tracker.csv'
      };
      $scope.gridOptionstoday.api.exportDataAsCsv(params);
    }); 
     });
    }



    function getfulldata(){
        showPageLoader();
        $http.get('api/index.php?action=getjsonfull&from_date='+gDate+'&field='+fIeld).success(function(data) {
        //$http.get('api/sample.json?action=getjson&from_date='+gDate+'&uid='+gUid).success(function(data) {
            
            
            $scope.gridOptionsum.api.setRowData(data.summary); 
            $scope.gridOptionsum.api.sizeColumnsToFit();
            $scope.gridOptionacx.api.setRowData(data.acx); 
            $scope.gridOptionacx.api.sizeColumnsToFit();
            $scope.gridOptionbbe.api.setRowData(data.bbe); 
            $scope.gridOptionbbe.api.sizeColumnsToFit();
            $scope.gridOptionex.api.setRowData(data.ex); 
            $scope.gridOptionex.api.sizeColumnsToFit();
            $scope.gridOptionkm.api.setRowData(data.km); 
            $scope.gridOptionkm.api.sizeColumnsToFit();
            $scope.gridOptionlegex.api.setRowData(data.legacyex); 
            $scope.gridOptionlegex.api.sizeColumnsToFit();
            $scope.gridOptionlegqfx.api.setRowData(data.legacyqfx); 
            $scope.gridOptionlegqfx.api.sizeColumnsToFit();
            $scope.gridOptionmmx.api.setRowData(data.mmx); 
            $scope.gridOptionmmx.api.sizeColumnsToFit();
            $scope.gridOptionqfx.api.setRowData(data.qfx); 
            $scope.gridOptionqfx.api.sizeColumnsToFit();
            $scope.gridOptionrpd.api.setRowData(data.rpd); 
            $scope.gridOptionrpd.api.sizeColumnsToFit();
            $scope.gridOptionserv.api.setRowData(data.services); 
            $scope.gridOptionserv.api.sizeColumnsToFit();
            $scope.gridOptiontptx.api.setRowData(data.tptx); 
            $scope.gridOptiontptx.api.sizeColumnsToFit();
            $scope.gridOptionci.api.setRowData(data.ci); 
            $scope.gridOptionci.api.sizeColumnsToFit();
            $scope.gridOptionexcluded.api.setRowData(data.excluded); 
            $scope.gridOptionexcluded.api.sizeColumnsToFit();
            hidePageLoader();
        });
    }

    $(document).ready(function(){
        $('#daterange').daterangepicker({
            //opens: 'left',
            maxDate: 'now',
            ignoreReadonly: true,
            useCurrent: false

        });
        $("#daterange").on("dp.change", function (e) {
            gDate = $('#daterange').val();
            mUid = "";
            fIeld = $('#field').val();
	    mUid = $('#mgruid').val();
	
            getdata();
        });
        gDate = $('#daterange').val();
        mUid = "";
        mUid = $('#mgruid').val();
        fIeld = $('#field').val();
	getdata();
    });
    
    
    
    // when they are submitting for all
    
         $(document).ready(function() {
                    $("#fetchall").click( function (e) {
                    gDate = $('#daterange').val();
                    mUid = $('#mgruid').val();
                    fIeld = $('#field').val();
		    getfulldata();
               });
           });
    
          //when they enter manager name
         $(document).ready(function() {
                    $("#go").click( function (e) {
                    gDate = $('#daterange').val();
                    fIeld = $('#field').val();
		    mUid = $('#mgruid').val();
                    getdata();
               });
         });
    
    });
    
