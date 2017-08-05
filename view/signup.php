<h1><?=$_('sign up')?></h1>
<?php if($message): ?>
    <div class="alert"><?=$_('wrong username or password')?></div>
<?php endif ?>
<?php
if(ENV == 'prod') {
    echo 'Sign up is disabled for now.<br>
    L\'inscription est désactivé pour le moment.';
    return;
}
?>
<form method="POST">
    <?php foreach ($forms as $form):?>
        <label>
            <?=$_($form['label'])?>
            <?=$form['html']?>
        </label>
    <?php endforeach;?>

    <input type="submit" name="submit_signup" value="Sign in">
</form>