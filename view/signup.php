<?php $this->start('default') ?>
    <h1><?=$_('sign up')?></h1>

    <?=$form('form')->html?>
    <?=$form('email')->label($_('email'))->row?>
    <?=$form('name')->label($_('name'))->row?>
    <?=$form('password')->label($_('password'))->row?>

    <?=$form('submit')->value($_('sign up'))->html?>
    <?=$form('/form')->html?>
<?php $this->end() ?>