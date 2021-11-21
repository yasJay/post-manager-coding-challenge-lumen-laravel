<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    
    /**
     * /posts [POST]
     */
    public function testShouldReturnAllPosts() {

        $parameters = [
            'access_token' => env('ACCESS_TOKEN')
        ];

        $this->post("api/v1/posts", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                [
                    'id',
                    'user_id',
                    'medium_post_id',
                    'title',
                    'content',
                    'tags',
                    'publishStatus',
                    'url',
                    'created_at',
                    'updated_at'
                ]
            ]    
        );
        
    }

    /**
     * /post create [POST]
     */
    public function testShouldCreatePost() {

        $parameters = [
            'access_token' => env('ACCESS_TOKEN'),
            'title'         => 'This is a Test Title',
            'content'       => 'Test Content',
            'tags'          => 'test, post',
            'publishStatus' => 'draft'
        ];

        $this->post("api/v1/post/create", $parameters, []);
        $this->seeStatusCode(201);
        $this->seeJsonStructure();
        
    }

    /**
     * /post view [POST]
     */
    public function testShouldReturnPost() {

        $parameters = [
            'access_token' => env('ACCESS_TOKEN'),
            'id'           => '1'
        ];

        $this->post("api/v1/post/view", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure();
        
    }

    /**
     * /post submit [POST]
     */
    public function testShouldSubmitPost() {

        $parameters = [
            'access_token' => env('ACCESS_TOKEN'),
            'id'           => '36'
        ];

        $this->post("api/v1/post/submit", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure();
        
    }


}
