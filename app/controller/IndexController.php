<?php
class IndexController
{
    private $db;

    public function __construct() {
        $this->db = Db::connect();
    }

    public function index()
    {
        $view = new View();
        $posts = Post::all();
        $view->render('index', [
            "posts" => $posts
        ]);
    }
    public function view($id = 0)
    {
        $view = new View();
        $view->render('view', [
            "post" => Post::find($id),
            "comment" => Comment::find( $id )
        ]);
    }
    public function newPost()
    {
        $data = $this->_validate($_POST);
        $uploadImage = $this->uploadImage( $data );
        $imageName = ($uploadImage) ? $uploadImage : null;

        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $sql = 'INSERT INTO post (content, date, image) VALUES (:content, now(), :image) ';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue('content', $data['content'] );
            $stmt->bindValue( 'image', $imageName );
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }
    public function delete( $id = 0){
        if( $id ){
            $sql = 'DELETE FROM post WHERE id = :id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 'id', $id );
            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }

    private function uploadImage( $data ) {
        $target_dir    = App::config('uploads_folder');
        $target_file   = $target_dir . basename( $_FILES["image"]["name"] );
        $uploadOk      = false;

        $imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
        $check = getimagesize( $_FILES["image"]["tmp_name"] );

        $uploadOk = ( $check ) ? true : $uploadOk;

        if ( $imageFileType != "jpg" &&  $imageFileType != "jpeg" ) {
            $uploadOk = false;
        }

        if ( ! $uploadOk  ) {
            return false;
        } else {
            if ( move_uploaded_file( $_FILES["image"]["tmp_name"], $target_file ) ) {
                return basename( $_FILES["image"]["name"] );
            } else {
                return false;
            }
        }
    }

    public function newComment()
    {

        $data = $this->_validate($_POST);
        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $sql = 'INSERT INTO comments (content, post_id) VALUES (:content,:post_id) ';
            $stmt = $this->db->prepare($sql);
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