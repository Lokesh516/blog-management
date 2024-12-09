<?php
class CommentsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getComments()
    {
        $query = $this->db->prepare("SELECT * FROM comments");
        $query->execute();
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

    public function deleteComment($id) {
        $query = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        $query->bind_param("i", $id);
        return $query->execute();
    }
}
?>
