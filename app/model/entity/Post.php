<?php
class Post
{
    private $id;
    private $content;
    private $date;
    private $commentCount;
    private $image;

    public function __construct($id, $content, $date, $commentCount, $image)
    {
        $this->setId($id);
        $this->setContent($content);
        $this->setDate($date);
        $this->setCommentCount($commentCount);
        $this->setImage($image);

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
    public static function all()
    {
        $list = [];
        $db = Db::connect();
        $statement = $db->prepare("select post.id,post.content, post.date, post.image, (SELECT COUNT(*) FROM comments WHERE post_id = post.id) as commentCount from post ORDER BY id DESC
");
        $statement->execute();
        foreach ($statement->fetchAll() as $post) {
            $list[] = new Post($post->id, $post->content, $post->date, $post->commentCount, $post->image);
        }
        return $list;
    }
    public static function find($id)
    {
        $id = intval($id);
        $db = Db::connect();
        $statement = $db->prepare("select post.id,post.content, post.date, post.image, (SELECT COUNT(*) FROM comments WHERE post_id = post.id) as commentCount from post where id = :id ORDER BY id DESC");
        $statement->bindValue('id', $id);
        $statement->execute();
        $post = $statement->fetch();
        return new Post($post->id, $post->content, $post->date, $post->commentCount, $post->image);
    }
}