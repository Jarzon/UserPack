<div class="box">
    <?php if(isset($message)): ?>
        <div class="<?=$message[0]?>"><?=$_($message[1])?></div>
    <?php endif ?>

    <?=$form('form')->html?>
        <?=$form('name')->label($_('name'))->row?>
        <?=$form('password')->label($_('password'))->row?>
        <?=$form('remember')->label($_('remember me'))->row?>

        <?=$form('submit')->value($_('sign in'))->html?>
    <?=$form('/form')->html?>

    You don't have an account? <a href="/signup"><?=$_('sign up')?></a>!
</div>