<div id="modal-filemanager" class="modal" role="dialog">
    <div class="modal-dialog modal-md">
        <form action="javascript:void(0)" data-type-message="text" role="form" class="form-horizontal actionForm" role="form" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title"><i class="fa fa-laptop" aria-hidden="true"></i> <?=lang("file_manager")?></div>
            </div>
            <div class="modal-body file-manager-content file-manager-content-popup file-manager-loader2 file-manager-scrollbar">
                <!--Ajax Load Files-->
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    
                </div>
                <input name="submit_popup" id="submit_popup" type="submit" value="<?=lang('add')?>" data-transfer="<?=$id?>" class="btn btn-primary file-manager-add-file" data-dismiss="modal" >
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
        </form>
    </div>
</div>
