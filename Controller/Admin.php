<?php  declare(strict_types=1);
namespace UserPack\Controller;

use Prim\AbstractController;
use Prim\View;
use UserPack\Model\UserModel;

class Admin extends AbstractController
{
    private UserModel $userModel;

    public function __construct(View $view, array $options,
                                UserModel $userModel)
    {
        parent::__construct($view, $options);

        $this->userModel = $userModel;
    }

    public function list(int $page = 1): void
    {
        $this->render('admin/list', 'UserPack', [
            'users' => $this->userModel->getAllUsers()
        ]);
    }

    public function show(int $user_id): void
    {
        if (isset($_POST['submit_update_user'])) {
            $post = $_POST;

            unset($post['id']);
            unset($post['submit_update_user']);

            $this->userModel->updateUser($post, $_POST['id']);
        }

        $this->render('admin/show', 'UserPack', [
            'user' => $this->userModel->getUser($user_id)]
        );
    }
}
