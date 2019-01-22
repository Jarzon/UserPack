<?php
return [
    UserPack\Controller\Admin::class => function($dic) {
        return [
            $dic->getAdminService()
        ];
    },
];