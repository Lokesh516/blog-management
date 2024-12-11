<?php
require_once 'C:\Apache24\htdocs\blog-management\models\admin\Comments.php';

class CommentController {
    private $model;

    public function __construct($db) {
        $this->model = new CommentsModel($db);
    }

    public function handleRequest($action, $data=null) {
        switch ($action) {
            case 'viewAll':
                $comments = $this->model->getComments();
                echo json_encode($comments);
                break;

            case 'delete':
                $success = $this->model->deleteComment($data['id']);
                echo json_encode(['success' => $success]);
                break;

            default:
                echo json_encode(['error' => 'Invalid Action']);
                break;
        }
    }
}
?>
