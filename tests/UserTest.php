<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    
    /**
     * /user create [POST]
     */
    public function testShouldCreateUser() {

        $parameters = [
            'access_token' => env('ACCESS_TOKEN')
        ];

        $this->post("api/v1/user", $parameters, []);
        $this->seeStatusCode(201);
        $this->seeJsonStructure();
        
    }



}
