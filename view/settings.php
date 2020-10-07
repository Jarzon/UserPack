<?php declare(strict_types=1);
/**
 * @var $this \Prim\View
 * @var $_ callable
 * @var $form \UserPack\Form\UserForm
 */
$this->start('default') ?>
    <h1><?=$_('settings')?></h1>

    <?=$form('form')->html?>
        <div class="listeForm">
            <?=$form('email')->label($_('email'))->row?>
        </div>

        <?=$form('submit')->value($_('save'))->html?>
    <?=$form('/form')->html?>
<?php $this->end() ?>
