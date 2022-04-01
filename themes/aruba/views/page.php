<?=Modules::run(get_theme()."/header")?>

<div class="container" style="padding-top: 30px; margin-bottom: 50px;">
    <div class="custom-page">
        <h3 class="title"></h3>

        <div class="cp-content">
            <?=$result->content?>
        </div>
    </div>
</div>

<?=Modules::run(get_theme()."/footer")?>