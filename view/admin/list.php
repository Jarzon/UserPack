<?php $this->start('default') ?>
    <h1 class="alignCenter"><?=$_('users')?></h1>

    <table class="table">
        <thead>
        <tr class="grey">
            <th>id</th>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?=$user->id?></td>
                <td><a href="/admin/users/edit/<?=$user->id?>"><?=$user->name?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php $this->end() ?>