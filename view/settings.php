<h1><?=$_('settings')?></h1>
<?php if(isset($message)): ?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif ?>
<form method="POST">
    <?php foreach ($forms as $form):?>
        <label>
            <?=$_($form['label'])?>
            <?=$form['html']?>
        </label>
    <?php endforeach;?>

    <input type="submit" name="submit_settings" value="Save">
</form>
