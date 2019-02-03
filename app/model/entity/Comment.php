<?php
class Comment
{
    private $id;
    private $content;
    private $post_id;

    public function __construct($id, $content, $post_id)
    {
        $this->setId($id);
        $this->setContent($content);
        $this->setPost_Id($post_id);

    }

    /**
     * @return mixed
     */

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);
        if ($function === 'set') {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        } else if ($function === 'get') {
            return $this->__get(strtolower(substr($name, 3)));
        }
        return $this;
    }

    public static function find( $post_id )
    {
        $list = [];
        $post_id = intval($post_id);
        $db = Db::connect();
        $statement = $db->prepare("select * from comments where post_id = :post_id ORDER BY id DESC");
        $statement->bindValue('post_id', $post_id);
        $statement->execute();
        foreach ($statement->fetchAll() as $comment) {
            $list[] = new Comment($comment->id, $comment->content, $comment->post_id);
        }
        return $list;
    }
}