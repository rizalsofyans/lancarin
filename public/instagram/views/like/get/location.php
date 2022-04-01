<?php if(!empty($result)){
foreach ($result as $key => $value) {
	if($value->location->pk != 0){
?>
    
    <a href="https://www.instagram.com/explore/locations/<?=$value->location->pk?>" target="_blank" class="activity-option-location">
        <div class="pure-checkbox grey" style="padding: 0 25px 0 0; position: relative; top: -3px;">
            <input type="checkbox" name="add_location[]" id="cb_location_select_<?=$value->location->pk?>" class="filled-in chk-col-red" value="<?=$value->location->pk."|".$value->location->name?>">
            <label class="p0 m0" for="cb_location_select_<?=$value->location->pk?>">&nbsp;</label>
        </div>
        <span><?=$value->location->name?></span>
    </a>

<?php }}}else{?>
    <div class="dataTables_empty"></div>
<?php }?>