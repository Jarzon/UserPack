<?php if(isset($message)): ?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif ?>

<?=$form('form')->html?>
    <?=$form('password1')->label($_('password'))->row?>
    <?=$form('password2')->label($_('reenter password'))->row?>

    <?=$form('submit')->value($_('set new password'))->html?>
<?=$form('/form')->html?>