# Lumen PHP Framework
## Post Manager Documentation - Coding Challenge

##### Repository contains the post manager API build for the coding challenge of Lumen/ Laravel

### Overview

This API consists of events,
- Get user details and create user
- Create post
- Create post and submit to Medium
- Submit post to Medium
- Get all posts
- Get post to view
- Upload images to Medium

### Installation

- Clone the repository
- Run *composer install* in project root
- Config the .env file with database credentials(*DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD*), *SSL_CERT* with the location of ssl certificate it using in local environment, *ACCESS_TOKEN* with Medium access token and this is used for run tests so if tests are running configure this attribute.
- Run *php artisan migrate*
- Run *php -S localhost:8000 -t public*
- You can now access the server at *[http://localhost:8000](http://localhost:8000 "http://localhost:8000")*
- Test cases can be checked by running "*php vendor/phpunit/phpunit/phpunit*" (in windows)

### API

| Endpoint  | Parameters  | Method | Description |
| :------------ |:---------------:| -----:|-----:|
| http://localhost:8000/api/v1/user | access_token - Medium Access Token | POST | Get user details from Medium and create user in application|
|http://localhost:8000/api/v1/posts | access_token - Medium Access Token | POST | Get user's all posts in application|
|http://localhost:8000/api/v1/post/create | access_token - Medium Access Token, title - Post title, content - Post content, tags - comma seperated tags for post, publishStatus - posted or draft | POST | Create a post in application. If the publishStatus is posted, It will create and publish the post with draft status in users Medium account. |
|http://localhost:8000/api/v1/post/view | access_token - Medium Access Token, id - post id in application | POST | Get post details by post id in application.|
|http://localhost:8000/api/v1/post/submit | access_token - Medium Access Token, id - post id in application | POST | Submit existing post in application to medium with draft status.|
|http://localhost:8000/api/v1/image | access_token - Medium Access Token, image - Image file | POST | Upload image to medium and get image URL and details to create in application.|








