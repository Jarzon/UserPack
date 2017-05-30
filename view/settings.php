<h1><?=$_('settings')?></h1>
<?php if(isset($message)): ?>
    <div class="alert"><?=$_($message)?></div>
<?php endif ?>
<form method="POST">
    <?=$forms->generateForms()?>

    <input type="submit" name="submit_settings" value="Save">
</form>
