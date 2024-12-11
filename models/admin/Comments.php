<?php
class CommentsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getComments()
    {
        $query = $this->db->prepare("SELECT 
                                       com.*,
                                       pos.image,
                                       pos.title
                                    FROM 
                                       comments AS com
                                    INNER JOIN 
                                        posts AS pos 
                                    ON 
                                        com.post_id = pos.id
                                    ORDER BY 
                                        com.id DESC;
                                    ");
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
