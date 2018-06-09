<?php if(isset($message)): ?>
    <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
<?php endif ?>

<?=$form('form')->html?>
    <div><?=$form('name')->label($_('name'))->row?></div>
    <div><?=$form('password')->label($_('password'))->row?></div>
    <a href="/reset"><?=$_('forgot password')?></a>
    <div><?=$form('remember')->label($_('remember me'))->row?></div>

    <?=$form('submit')->value($_('sign in'))->html?>
<?=$form('/form')->html?>

<?=$_('no account?')?> <a href="/signup"><?=$_('sign up')?></a>!
