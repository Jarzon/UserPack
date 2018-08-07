<?php $this->start('default') ?>
    <h1><?=$_('settings')?></h1>
    <?php if(isset($message)): ?>
        <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
    <?php endif ?>

    <?=$form('form')->html?>
    <?=$form('mail')->label($_('email'))->row?>

    <?=$form('submit')->value($_('save'))->html?>
    <?=$form('/form')->html?>
<?php $this->end() ?>
