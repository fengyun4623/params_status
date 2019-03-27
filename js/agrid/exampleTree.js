// ,cellRenderer: LineCellRenderer
var columnDefs = [

    {headerName: "Function", cellRenderer: 'group'},
    {headerName: "Testbed", field: 'testbed', width: 150},
    {headerName: "Owner", field: 'owner', width: 150},
          {
            headerName: "Infra Failures",
            children: [
                {
                   headerName: "PARAMS",
                    field: 'params',
                    width: 170,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "LINKS DOWN",
                    field: 'linkdown', 
                    width: 200,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "JPG",
                    field: 'jpg', 
                    width: 100,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "ABORTS",
                    field: 'aborts', 
                    width: 150,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "ISSU",
                    field: 'issu', 
                    width: 100,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "BSDs Down",
                    field: 'bsds', 
                    width: 220,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "Unknown Errors",
                    field: 'unknownerrors', 
                    width: 220,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "IXIA",
                    field: 'ixia', 
                    width: 120,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: "Others",
                    field: 'others', 
                    width: 120,
                    filter: 'text',
                    filterParams: { apply: true }
                }
            ]
        },
             {
            headerName: "Activities",
            children: [
                {
                    headerName: 'Debugs',
                    field: 'debugs',
                    width: 170,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: 'PRs to Verify',
                    field: 'prv', 
                    width: 250,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: 'PRs to Provide Info',
                    field: 'prinfo', 
                    width: 250,
                    filter: 'text',
                    filterParams: { apply: true }
                },
                {
                    headerName: 'ETRANS Pending',
                    field: 'etrans', 
                    width: 250,
                    filter: 'text',
                    filterParams: { apply: true }
                }
            ]
        }
];

var rowData = [
    {group: 'MX',
        testbeds: [
        {testbed: 'Testbed 1',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 2',owner:'owner', params: '2', linkdown: '10', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 3',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 4',owner:'owner', params: '2', linkdown: '21', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 5',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 6',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },

    ]},
    {group: 'PTX',
        testbeds: [
        {testbed: 'Testbed 1',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 2',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 3',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 4',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 5',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },
        {testbed: 'Testbed 6',owner:'owner', params: '2', linkdown: '1', jpg: '3' , aborts :'0', issu :'1', bsds :'2', unknownerrors : '3',ixia:'5',others:'7', debugs :'3', prv :'3', prinfo:'5', etrans :'4' },

    ]}
];

var maxLines = 5;
var gridOptions = {
    columnDefs: columnDefs,
    rowData: rowData,
    debug: true,
    enableSorting: true,
    enableFilter: true,
    // rowHeight: 4+(maxLines*19),
    getNodeChildDetails: getNodeChildDetails,
    onGridReady: function(params) {
        params.api.sizeColumnsToFit();
        params.api.expandAll();
    }
};

function getNodeChildDetails(rowItem) {
    if (rowItem.group) {
        return {
            group: true,
            // open C be default
            expanded: rowItem.group === 'Group C',
            // provide ag-Grid with the children of this group
            children: rowItem.testbeds,
            // this is not used, however it is available to the cellRenderers,
            // if you provide a custom cellRenderer, you might use it. it's more
            // relavent if you are doing multi levels of groupings, not just one
            // as in this example.
            field: 'group',
            // the key is used by the default group cellRenderer
            key: rowItem.group
        };
    } else {
        return null;
    }
}

function onFilterChanged(value) {
    gridOptions.api.setQuickFilter(value);
}

// setup the grid after the page has finished loading
document.addEventListener('DOMContentLoaded', function() {
    var gridDiv = document.querySelector('#testbed-grid');
    new agGrid.Grid(gridDiv, gridOptions);
});


 function LineCellRenderer(params) {
        var width = params.colDef.width - 10; // 6 gives us a little padding
        var debug = false;
        if (params.rowIndex === 1) debug = true;
        font = 'normal 14px';

        var result = GetWidthHeight(params.value,width, font, debug);
        var out = "";
        
        for (var i=0; i < result.OutputLines.length-1; i++) {
            out += '<div style="width: '+width+'px">'+result.OutputLines[i]+'</div>\n';
        }
        out += '<div style="overflow: hidden; text-overflow: ellipsis; width: '+width+'px;">'+result.OutputLines[i]+'</div>';
        return out;
    }

  function GetTextWidth(text, font) {
        // re-use canvas object for better performance
        var canvas = this.canvas || (this.canvas = document.createElement("canvas"));
        var context = canvas.getContext("2d");
        context.font = font;
        var metrics = context.measureText(text);
        return parseInt(metrics.width*1.15); // don't ask why I'm adding 15% to the width... it just works. If you can figure out how to make it exact, please let me know.
    }

  function GetTextWidth(text, font) {
        // re-use canvas object for better performance
        var canvas = this.canvas || (this.canvas = document.createElement("canvas"));
        var context = canvas.getContext("2d");
        context.font = font;
        var metrics = context.measureText(text);
        return parseInt(metrics.width*1.15); // don't ask why I'm adding 15% to the width... it just works. If you can figure out how to make it exact, please let me know.
    }

function GetWidthHeight(string, AllowedWidth, font, debug) {
        var words = new Array();
        if(string)
            words = string.split(" "); // split on spaces
        var LineWidth = 0, CharCounter = 0, StartCounter = 0, MaxWidth = 0;
        var DivText = "";
        var NumLines = 1;
        var OutputLines = [];

        for (var i=0; i < words.length; i++) {
            var text = words[i];
            var ThisWidth = GetTextWidth(text, font);
            //if (debug) console.log(text + " = " + ThisWidth + "px");
            if (LineWidth + ThisWidth > AllowedWidth) {
                // end this line, begin a new one.
                if (LineWidth > MaxWidth) MaxWidth = LineWidth;
                var LineOut = string.substr(StartCounter, CharCounter);
                i--; // go back one word since this word needs to start on a new line
                if (debug) {
                    //console.log("LINE OUT: " + LineOut);
                    //console.log("LINE PIXEL WIDTH: " + LineWidth);
                }
                LineWidth = ThisWidth;
                StartCounter = StartCounter + CharCounter;
                OutputLines[NumLines-1] = LineOut;
                if (NumLines == maxLines) {
                    OutputLines[NumLines-1] = string.substr(StartCounter-CharCounter);
                    break;
                }
                CharCounter = 0;
                NumLines++;
            } else {
                LineWidth += ThisWidth;
                CharCounter += text.length + 1;
                if (i == words.length-1) {
                    OutputLines[NumLines-1] = string.substr(StartCounter);
                }
            }
        }
        if (MaxWidth === 0) MaxWidth = AllowedWidth;
        if (OutputLines.length === 0) OutputLines.push(string);
        return {
            OutputLines: OutputLines,
            NumLines: NumLines,
            MaxWidth: MaxWidth
        };
    }



