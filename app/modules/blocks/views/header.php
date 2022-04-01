<div class="header">
    <div class="navbar-header" id="navbar-header">
        <a href="<?=cn("dashboard")?>" class="navbar-brand navbar-brand-big"><img src="<?=get_option("website_logo_black", BASE.'assets/img/logo-black.png')?>"></a> 
        <a href="<?=cn("dashboard")?>" class="navbar-brand navbar-brand-small"><img src="<?=get_option("website_logo_mark", BASE.'assets/img/logo-mark.png')?>"></a> 
    </div>
    <a href="#" class="nav-link nav-menu-main menu-toggle is-active hidden-sm hidden-lg hidden-md" id="menu-toggle"><i class="ft-menu"></i></a>
    <ul class="nav navbar-nav navbar-right header-menu">
		<?php if(session('tmp_uid')==1){?>
        <li>
            <a class="menu-button p15" href="<?=cn("profile/login_as_admin")?>">
                <div class="btn btn-danger">Back to Admin</div>
            </a>
        </li>
        <?php }?>
        <?php if(get_payment()){?>
        <li>
            <a class="menu-button p15" href="<?=cn("pricing")?>">
                <div class="btn btn-primary"><?=lang('upgrade_now')?></div>
            </a>
        </li>
        <?php }?>
        <?php if(get_role()){?>
        <li>
            <a  class="menu-settings" href="<?=cn("settings/general")?>"><i class="ft-settings"></i></a>
        </li>
        <?php }?>

        
        <?php 
        $lang_default = get_default_language();
        if(!empty($lang_default)){
        ?>
        <li class="dropdown-lang">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="<?=$lang_default->icon?>" style="font-size: 16px;"></span> 
                    
                    <i class="dropdown-down-icon ft-chevron-down"></i>
                </a> 
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if(!empty($languages)){
                        foreach ($languages as $key => $value) {
                    ?>
                        <a class="dropdown-item actionItem" href="<?=cn('set_language')?>" data-redirect="<?=current_url()?>" data-id="<?=$value->code?>"><i class="<?=$value->icon?>"></i> <?=$value->name?></a>
                    <?php }}?>
                </div>
            </div>
        </li>
        <?php }?>

        <li class="dropdown-user">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-circle" src="<?=BASE?>assets/img/default-avatar.png"> <span class="user-name"><?=get_field(USERS, session("uid"), "fullname")?></span> <i class="dropdown-down-icon ft-chevron-down"></i></a> 
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="<?=cn('profile')?>"><i class="ft-user"></i> <?=lang('profile')?></a>
					<?php if(session('tmp_uid')==1){?>
					<a class="dropdown-item visible-xs" href="<?=cn("profile/login_as_admin")?>"><i class="ft-chevron-left"></i> Back to Admin</a>
					<?php }?>
					<?php if(get_payment()){?>
					<a class="dropdown-item visible-xs" href="<?=cn("pricing")?>"><i class="ft-package"></i> <?=lang('upgrade_now')?></a>
					<?php }?>
                    <a class="dropdown-item" href="<?=cn('auth/logout')?>"><i class="ft-log-out"></i> <?=lang('logout')?></a>
                    <?php 
                    $lang_default = get_default_language();
                    if(!empty($lang_default)){
                    ?>
                    <div class="language-mobile">
                        <div class="name"><i class="fa fa-language"></i> Language</div>
                        <?php if(!empty($languages)){
                        foreach ($languages as $key => $value) {
                        ?>
                            <a class="actionItem <?=$lang_default->code == $value->code?"bg-primary":""?>" href="<?=cn('set_language')?>" data-redirect="<?=current_url()?>" data-id="<?=$value->code?>"><i class="<?=$value->icon?>"></i> <?=$value->name?></a>
                        <?php }}?>
                    </div>
                    <?php }?>
                </div>
            </div>
        </li>
    </ul>
</div>

