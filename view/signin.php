<div class="box">
    <?php if($message): ?>
        <div class="alert"><?=$_('wrong username or password')?></div>
    <?php endif ?>
    <form method="POST">
        <?php foreach ($forms as $form):?>
            <?php if($form['type'] == 'checkbox' || $form['type'] == 'radio'): ?>
                <?php foreach ($form['html'] as $checkbox):?>
                    <label><?=$checkbox['input']?> <?=$_($checkbox['label'])?></label>
                <?php endforeach;?>
            <?php else: ?>
                <label><?=$_($form['label'])?> <?=$form['html']?></label>
            <?php endif; ?>
        <?php endforeach;?>

        <input type="submit" name="submit_signin" value="<?=$_('sign in')?>">
    </form>

    You don't have an account? <a href="/signup"><?=$_('sign up')?></a>!
</div>