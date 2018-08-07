<?php $this->start('default') ?>
    <?=$form('form')->html?>
    <?=$form('password1')->label($_('password'))->row?>
    <?=$form('password2')->label($_('reenter password'))->row?>

    <?=$form('submit')->value($_('set new password'))->html?>
    <?=$form('/form')->html?>
<?php $this->end() ?>