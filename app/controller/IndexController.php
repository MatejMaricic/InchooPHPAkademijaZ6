<?php
class IndexController
{
    public function index()
    {
        $view = new View();
        $posts = Post::all();
        $comments = Comment::all();
        $view->render('index', [
            "posts" => $posts,
            "comments" => $comments
        ]);
    }
    public function view($id = 0)
    {
        $view = new View();
        $view->render('view', [
            "post" => Post::find($id),
            "comment" => Comment::all()
        ]);
    }
    public function newPost()
    {
        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content,date) VALUES (:content,now()) ';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }

    public function newComment()
    {

        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO comment (content, post_id) VALUES (:content,:post_id) ';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->bindValue('post_id', $data['post_id']);
            $stmt->execute();
            header('Location: ' . App::config('url') . 'Index/view/' . $data['post_id']);

        }
    }

    /**
     * @param $data
     * @return array|bool
     */
    private function _validate($data)
    {
        $required = ['content'];
        //validate required keys
        foreach ($required as $key) {
            if (!isset($data[$key])) {
                return false;
            }
            $data[$key] = trim((string)$data[$key]);
            if (empty($data[$key])) {
                return false;
            }
        }
        return $data;
    }

}