<?php $this->start('default') ?>
    <h1 class="alignCenter"><?=$_('users')?></h1>

    <div class="box">
        <h3 class="alignCenter">List of users</h3>
        <table class="table">
            <thead>
            <tr class="grey">
                <td>Name</td>
                <td>DELETE</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><a href="/users/edit/<?=$user->id?>"><?=$user->name?></a></td>
                    <td><a href="/users/delete/<?=$user->id?>">delete</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php $this->end() ?>