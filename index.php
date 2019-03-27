  <?php
  $page = "domainview";
  $pageTitle = "Params Status";
  $pageJS = [ "testbed.js" ];
  include('header.php');
  ?>
  <!-- End Header -->
  <?php
   // Date Calcuation work week today minus 7 days
  $from_date = date('Y-m-d', strtotime("now"));
  $start_date = date('m-d-Y', strtotime("now"));
  $end_date = date('m-d-Y', strtotime("-7 days"));
  // date('d.m.Y',strtotime("-1 days"));
  ?>

<style>

.label-grey{
  background: #d3d3d3;
  color:#000 !important;
}

.label-yellow{
  background: #ffc107f2;
}

.label-red{
  background: #e9271ea6;
}

.label-green{
  background: #a2e715;
}
.label-peach{
background: #f08080;
}
.label-peachpuff{
background: #ffdab9;
}
</style>
<div class="content hp100" id="testbed-div" ng-controller="drwiseController">
<div class="pull-right">
  <span class="label label-red">Fail</span>
  <span class="label label-green">Pass</span>
  <span class="label label-yellow">No scripts</span>
</div>

 
<div class="container tablewrap hp100 mt10">
	<ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#sum-grid">Summary</a></li>
            <li><a data-toggle="tab" href="#acx-grid">ACX</a></li>
            <li><a data-toggle="tab" href="#bbe-grid">BBE</a></li>
            <li><a data-toggle="tab" href="#ex-grid">EX</a></li>
            <li><a data-toggle="tab" href="#km-grid">K&M</a></li>
            <li><a data-toggle="tab" href="#lex-grid">LEGACY-EX</a></li>
            <li><a data-toggle="tab" href="#lqfx-grid">LEGACY-QFX</a></li>
            <li><a data-toggle="tab" href="#mmx-grid">MMX</a></li>
            <li><a data-toggle="tab" href="#qfx-grid">QFX</a></li>
            <li><a data-toggle="tab" href="#rpd-grid">RPD</a></li>
            <li><a data-toggle="tab" href="#serv-grid">SERVICES</a></li>
            <li><a data-toggle="tab" href="#tptx-grid">TPTX</a></li>
	           <li><a data-toggle="tab" href="#ci-grid">CI/RIAD</a></li>	
            <li><a data-toggle="tab" href="#excl-grid">Excluded</a></li>
            <li><a data-toggle="tab" href="#home">Read Me</a></li>


	</ul>
	<div class="tab-content";">
      <!--<div class="tabletype">New PRs</div>-->
      <div ag-grid="gridOptionsum" class="ag-theme-balham grid-view hp100 tab-pane fade in active" id="sum-grid"></div>
      <div ag-grid="gridOptionacx" class="ag-theme-balham grid-view hp100 tab-pane fade" id="acx-grid"></div>
      <div ag-grid="gridOptionbbe" class="ag-theme-balham grid-view hp100 tab-pane fade" id="bbe-grid"></div>
      <div ag-grid="gridOptionex" class="ag-theme-balham grid-view hp100 tab-pane fade" id="ex-grid"></div>
      <div ag-grid="gridOptionkm" class="ag-theme-balham grid-view hp100 tab-pane fade" id="km-grid"></div>
      <div ag-grid="gridOptionlegex" class="ag-theme-balham grid-view hp100 tab-pane fade" id="lex-grid"></div>
      <div ag-grid="gridOptionlegqfx" class="ag-theme-balham grid-view hp100 tab-pane fade" id="lqfx-grid"></div>
      <div ag-grid="gridOptionmmx" class="ag-theme-balham grid-view hp100 tab-pane fade" id="mmx-grid"></div>
      <div ag-grid="gridOptionqfx" class="ag-theme-balham grid-view hp100 tab-pane fade" id="qfx-grid"></div>
      <div ag-grid="gridOptionrpd" class="ag-theme-balham grid-view hp100 tab-pane fade" id="rpd-grid"></div>
      <div ag-grid="gridOptionserv" class="ag-theme-balham grid-view hp100 tab-pane fade" id="serv-grid"></div>
      <div ag-grid="gridOptiontptx" class="ag-theme-balham grid-view hp100 tab-pane fade" id="tptx-grid"></div>
      <div ag-grid="gridOptionci" class="ag-theme-balham grid-view hp100 tab-pane fade" id="ci-grid"></div>
      <div ag-grid="gridOptionexcluded" class="ag-theme-balham grid-view hp100 tab-pane fade" id="excl-grid"></div>
<div id="home" class="ag-theme-balham  hp100 tab-pane fade">
        <div class="maincontent"><div class="container">
          <h1>Params-Status Tool</h1> <blockquote>
          </br><b>Additional Info:-</b>
</br></br> &nbsp; <b>Preferred Params </b>:- Params which are preferred in fusion for execution, fusion use these params as priority for the mentioned domains.
</br>&nbsp; <b>Normal Params</b> :- Params which are used by fusion in case of fallback or when preferred params are not mentioned.
</br> &nbsp; Excluded Tab:- The domains which are in building state or due to some issues are excluded shown in Excluded tab in </br>http://jdi-reg-tools.juniper.net/params_status_fusion/ page.
</br> &nbsp; -These are excluded on request of users with managers approval in mail.
</br> &nbsp; Use ctrl+c for copying content.Treat page as a excel for copying.
</br> &nbsp; Sync your params on all the location for accuracy in results.
</br> &nbsp; <b>NOTE: Normal params can not be added as preferred params in fusion. If you try to add then also it will not be accepted by fusion as </br>preferred params in backend.(You can update but it is not considered).</b>
</br></br> &nbsp; 
</br> 
</br> 
</br><b>What to use :-</b>
</br> &nbsp;Follow step 1 when you have to refresh only failure after fixing issues.
</br> &nbsp;Follow step 2 when you have changed mapping in fusion and need full refresh of domain.
</br> 
</br> 
</br> 
</br><b>Step 1: How to refresh only failed params:-</b>
</br> &nbsp;Login to :- jdi-reg-tools server
</br> &nbsp;Path: /homes/nidanaz/params_refresh/
</br> &nbsp;Command : perl domain_fail_refresh.pl {domain-name} &
</br> &nbsp;Output: updated results will be displayed in the page. (http://jdi-reg-tools.juniper.net/params_status_fusion/)
</br> 
</br> 
</br><b>Step 2:How to refresh full domain  with profile mapping from fusion:-</b>
</br>  &nbsp; Login to :- jdi-reg-tools server
</br>  &nbsp; Path: /homes/nidanaz/params_refresh/
</br>  &nbsp; Command : perl domain_full_refresh.pl  {domain-name} &
</br>  &nbsp; Output: updated results will be displayed in the page. (http://jdi-reg-tools.juniper.net/params_status_fusion/)
</br> 
</br> 
</br> 
</br><b>Cron Frequency:-</b>
</br>  &nbsp;Complete script profile and domain refresh will take place once in a week ( On Weekend )
</br>  &nbsp;Fail Domain refresh will take place on daily basis.
</br>  &nbsp;Every domain refresh will take place from 1st Jan once domains are stable.
</br> 
</br>
</br> 
</br><b>How tool works:-</b>
</br>   &nbsp;First all domains are fetched from all the regression function in fusion ( JDI-REG-MMX,JDI-REG-TPTX.. etc )
</br>   &nbsp;Scripts are fetched which are mapped on that domain.
</br>   &nbsp;Then respective params and preferred params are fetched.
</br>   &nbsp;Tool then performs params-find according TOBY or JT and displays in this page. <b>NOTE:</b>If jpg params exist then tool didn't count or check for normal params.
</br>   &nbsp;Kindly ensure your params are synced across all location using cvs up to reduce false failure across servers.
</br>   &nbsp;Please ensure full execution of refreshing script to avoid further permission issues.
</br> 
</br>
</br><b>How to crosscheck profile-domain mapping:</b>
</br> &nbsp; First select your team in fusion tool
</br> &nbsp; Opt for script_profiles in  adv search
</br> &nbsp; select the domain you are checking mapping
</br> &nbsp; submit. 
</br> &nbsp; you will get list of scripts mapped to domain crosscheck the domain compatibility of your script.
</br>

</br><b>Enhancements:-</b>
</br>         &nbsp;Diff of the scripts between two consectuive week. It will help in understanding the reason for domain going in RED if it was Green in Previous week.
</br>         &nbsp;Display the script list against every function which are not mapped to any domain.
</br>         &nbsp;Execution logs will be provided for the debugging ( Params match result logs )
</br> 
</br><b>Whom To contact for Issues:-</b>
</br>nidanaz@juniper.net , saga@juniper.net, hemants@juniper.net - For Issues related to data, refresh and web page.
</br>namratha@juniper.net - Project Manager
</br>

 </blockquote>
        </div>
      </div>
       </div>

</div>
</div>


    <!-- -loader -->
    <div class="page-loader">
      <i class="icon close">&times;</i>
      <p class="still-working">Few more Seconds. I am working on it&hellip;</p>
    </div>
    <div class="info">
      <div class="alert alert-success" style="display:none;">
        <strong>Success!</strong> Records Saved Successfully.
      </div>
      <div class="alert alert-danger" style="display:none;">
        <strong>Failure!</strong> Please retry again.
      </div>
    </div>
    <!-- Message Modal -->
    <div id='messageModal' class='modal fade' role='dialog'>
      <div class='modal-dialog'>
        <!-- Modal content-->
        <div class='modal-content'>
          <div class='modal-header' id='modal_header'>
            <button type='button' class='close' data-dismiss='modal'>&times;</button>
            <h4 class='modal-title'>Message</h4>
          </div>
          <div class='modal-body' id='modal_body'>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-outline-secondary' data-dismiss='modal'>Ok</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Footer -->
<?php
include('footer.php');
?>
