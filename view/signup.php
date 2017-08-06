<h1><?=$_('sign up')?></h1>
<?php if($message): ?>
    <div class="alert"><?=$_('wrong username or password')?></div>
<?php endif ?>
<form method="POST">
    <?php foreach ($forms as $form):?>
        <label>
            <?=$_($form['label'])?>
            <?=$form['html']?>
        </label>
    <?php endforeach;?>

    <input type="submit" name="submit_signup" value="<?=$_('sign up')?>">
</form>