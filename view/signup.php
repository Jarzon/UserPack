<h1><?=$_('sign up')?></h1>
<?php if(isset($message)): ?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif ?>

<?=$form('form')->html?>
    <?=$form('email')->label($_('email'))->row?>
    <?=$form('name')->label($_('name'))->row?>
    <?=$form('password')->label($_('password'))->row?>

    <?=$form('submit')->value($_('sign up'))->html?>
<?=$form('/form')->html?>