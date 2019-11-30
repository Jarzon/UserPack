<?php $this->start('default') ?>
    <?=$form('form')->html?>
        <div><?=$form('name')->label($_('name'))->row?></div>
        <div><?=$form('password')->label($_('password'))->row?></div>
        <div class="forgotPassword"><a href="/reset"><?=$_('forgot password')?></a></div>
        <div><?=$form('remember')->label($_('remember me'))->row?></div>

    <?=$form('submit')->value($_('sign in'))->html?>
    <?=$form('/form')->html?>

    <?=$_('no account?')?> <a href="/signup"><?=$_('sign up')?></a>!
<?php $this->end() ?>
