  <!-- Live Chat Widget powered by https://keyreply.com/chat/ -->
<!-- Advanced options: -->
<!-- data-align="left" -->
<!-- data-overlay="true" -->
<script data-align="right" data-overlay="false" id="keyreply-script" src="//lancarin.com/assets/js-landingpage/widgetschat.js" data-color="#3F51B5" data-apps="JTdCJTIyd2hhdHNhcHAlMjI6JTIyNjI4MTkwNTY3OTczOSUyMiwlMjJmYWNlYm9vayUyMjolMjI0NDQ2ODUwNzkyNzI0OTAlMjIlN0Q="></script>
<style>
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    cursor:pointer;
}
</style>
<?php
$report = block_report();
$report_lists = json_decode($report->report_lists);
?>
<div class="wrap-content container tab-list box-report">
   <div class="card">
      <div class="card-header ">
					<div class="card-title">
                        <i class="ft-bar-chart-2" aria-hidden="true"></i> Dashboard
              <button type="button" class="btn btn-danger btn-sm" style="float:right;margin-left:5px;" data-toggle="modal" data-target="#myModal">
  Info </button>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informasi</h4>
      </div>
      <div class="modal-body text-khusus">
        <b>Join Grup WhatsApp Eksklusif :</b> Hubungi CS Kami melalui <b>Live Chat</b> atau WhatsApp <b>+62</b><br><br>
        <b>Join Grup Facebook Support Lancarin : <a href="https://www.facebook.com/groups/" style="font-weight:bold;">Klik di sini</a></b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    </div>
				</div>
      </div>
    <div class="row">
      <?php if(!empty($rss['news'])):?>
      <div class="col-md-6">
          <div class="card">
              <div class="card-header p0">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="http://blog.lancarin.com/category/news/" class=""><i class="fa fa-newspaper-o"></i> News</a></li>
                </ul>
              </div>
              <div class="card-block p0">
                <div class="tab-content p15 news-content">
                  <ul class="list-group">
                  <?php foreach($rss['news'] as $item):?>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-md-3" style="width: 25%;float: left;">
                          <span class="badge " ><?=$item['date'];?></span>
                        </div>
                        <div class="col-md-9 text-right" style="width: 75%;float: left;">
                          <span class=" " ><?=$item['text'];?></span>
                        </div>
                      </div>
                    </li>
                    <?php endforeach;?>
                  
                  </ul>
                </div>
              </div>
          </div>
      </div>
      <?php endif;?>
      <?php if(!empty($rss['tutorial'])):?>
      <div class="col-md-6">
          <div class="card">
              <div class="card-header p0">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="http://blog.lancarin.com/category/tutorial/" class=""><i class="fa fa-book"></i> Tutorial</a></li>
                </ul>
              </div>
              <div class="card-block p0">
                <div class="tab-content p15 tutorial-content">
                  <ul class="list-group">
                  <?php foreach($rss['tutorial'] as $item):?>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-md-3" style="width: 25%;float: left;">
                          <span class="badge " ><?=$item['date'];?></span>
                        </div>
                        <div class="col-md-9 text-right" style="width: 75%;float: left;">
                          <span class=" " ><?=$item['text'];?></span>
                        </div>
                      </div>
                    </li>
                  <?php endforeach;?>
                  
                  </ul>
                </div>
              </div>
          </div>
      </div>
      <?php endif;?>
  </div>
    <div class="row">
        <div class="col-md-12">
            <?php if(!empty($report_lists)){?>
            <div class="card">
                <div class="card-header p0">
                    <ul class="nav nav-tabs">
                    	<?php 
                    	if(!empty($report_lists)){
                    		foreach ($report_lists as $key => $name) {
                    	?>
                        <li class="<?=$key==0?"active":""?>"><a href="<?=cn("dashboard/report/".$name)?>" data-content="report-content" data-result="html" class="actionItem"><i class="fa fa-<?=$name?>"></i> <?=ucfirst(lang(strtolower($name)))?></a></li>
                        <?php }}?>
                    </ul>
                </div>
                <div class="card-block p0">
                    <div class="tab-content p15 report-content">
                		<?=$report->data?>
                    </div>
                </div>
            </div>
            <?php }else{?>
            <div class="ml15 mr15 bg-white dataTables_empty"></div>
            <?php }?>
        </div>
    </div>
</div>
