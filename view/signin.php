<div class="box">
    <?php if($message): ?>
        <div class="alert"><?=$_('wrong username or password')?></div>
    <?php endif ?>
    <form method="POST">
        <label><?=$_('name')?>
            <input type="text" name="name" value="" required>
        </label>

        <label><?=$_('password')?>
            <input type="password" name="password" value="" required>
        </label>

        <label><?=$_('remember me')?>
            <input type="checkbox" name="remember" value="">
        </label>

        <input type="submit" name="submit_signin" value="<?=$_('sign in')?>">
    </form>

    You don't have an account? <a href="/signup"><?=$_('sign up')?></a>!
</div>