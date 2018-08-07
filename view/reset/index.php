<?php $this->start('default') ?>
    <h1><?=$_('request reset password')?></h1>

    <?=$form('form')->html?>
    <?=$form('email')->label($_('email'))->row?>

    <?=$form('submit')->value($_('send'))->html?>
    <?=$form('/form')->html?>
<?php $this->end() ?>