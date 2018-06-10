<?php if(isset($message)): ?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif ?>

<?=$form('form')->html?>
    <?=$form('name')->label($_('name'))->row?>

    <?=$form('submit')->value($_('sign in'))->html?>
<?=$form('/form')->html?>