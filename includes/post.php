<?php

    class Post{

        public function fetch_all(){
            global $db;

            $query = $db->prepare("SELECT * FROM post ORDER BY post_id DESC");
            $query->execute();

            return $query->fetchAll();
        }


        public function fetch_data($post_id){
            global $db;

            $query = $db->prepare("SELECT * FROM post WHERE post_id = ?");
            $query->bindValue(1, $post_id);
            $query->execute();

            return $query->fetch();

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

        
        public function fetch_search_term($search){
            global $db;

            $query = $db->prepare("SELECT * FROM post
                                WHERE movie_title LIKE '%{$search}%'
                                ORDER BY post_id DESC;");
            
            $query->execute();
            return $query->fetchAll();

        }



    }
?>