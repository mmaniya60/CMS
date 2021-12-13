<?php

    class Post{

        public function fetch_all(){
            global $db;

            $query = $db->prepare("SELECT * FROM post ORDER BY post_id DESC");
            $query->execute();

            return $query->fetchAll();
        }


        public function fetch_data($id, $slug){
            global $db;

            $query = $db->prepare("SELECT * FROM post WHERE post_id = ? AND movie_slug = ?");
            $query->bindValue(1, $id);
            $query->bindValue(2, $slug);
            $query->execute();

            return $query->fetch();

        }

        public function row_count($id, $slug){
            global $db;

            $query = $db->prepare("SELECT * FROM post WHERE post_id = ? AND movie_slug = ?");
            $query->bindValue(1, $id);
            $query->bindValue(2, $slug);
            $query->execute();

            return $query->rowCount();

        }

        public function fetch_join_data($post_id){
            global $db;

            $query = $db->prepare("SELECT post.post_id, post.movie_title, post.movie_year, post.movie_description, post.posted_on, post.movie_image,
                                genre.genre_id, post.genre_id, genre.genres
                                FROM post LEFT JOIN genre ON post.genre_id = genre.genre_id
                                WHERE post.post_id = ?;");
            
            $query->bindValue(1, $post_id);
            $query->execute();

            return $query->fetch();

        }

        
        public function fetch_search_term($search, $offset, $limit){
            global $db;

            $query = $db->prepare("SELECT * FROM post
                                WHERE movie_title LIKE '%{$search}%'
                                ORDER BY post_id DESC
                                LIMIT {$offset}, {$limit}");
            
            $query->execute();
            return $query->fetchAll();
        }

        public function fetch_user($id){
            global $db;

            $query = $db->prepare("SELECT * FROM user WHERE user_id = ?");
            $query->bindValue(1, $id);
            $query->execute();

            return $query->fetch();
        }
    }

    function slug($text){
        $text = preg_replace('~[^\\pL\d]+~u','-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);

        if(empty($text)){
            return 'n-a';
        }

        return $text;
    }
?>
