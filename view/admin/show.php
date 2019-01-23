<?php $this->start('default') ?>
    <h1 class="alignCenter"><?=$user->name?></h1>

    <form action="/admin/users/edit/<?=$user->id?>" method="POST">
        <?php foreach ($user as $key => $value): ?>
            <div class="listForm">
                <label for="<?=$key?>"><?=$key?></label>
                <input type="text" id="<?=$key?>" name="<?=$key?>" value="<?=$value?>">
            </div>
        <?php endforeach; ?>

        <input type="submit" name="submit_update_user" value="Update">
    </form>
<?php $this->end() ?>