<?php $this->start('default') ?>
    <h1 class="alignCenter"><?=$user->name?></h1>

    <form action="/users/update/<?=$user->id?>" method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?=$user->name?>" required>

        <input type="submit" name="submit_update_user" value="Update">
    </form>
<?php $this->end() ?>