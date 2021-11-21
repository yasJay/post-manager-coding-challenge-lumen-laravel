<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\MediumApiHelper;
use App\Models\User;
use App\Models\Image;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // Get all posts of current user
    public function index(Request $request) {

        $validator = Validator::make($request->all(), [
            'access_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized, Access token not found'], 500);
        }

        try {

            $data = MediumApiHelper::getUser($request->all());
            $dataObj = json_decode($data);

            $user = User::where('username', $dataObj->data->username)->first();

            $posts = Post::with('user')->where('user_id', $user->id)->get();
            return response()->json($posts, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // Create post for current user and if publishStatus is posted post to medium API
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'access_token'  => 'required',
            'title'         => 'required',
            'content'       => 'required',
            'publishStatus' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 500);
        }

        try {

            $accessToken = $request->input('access_token');

            $data = MediumApiHelper::getUser($request->all());
            $dataObj = json_decode($data);

            $user = User::where('username', $dataObj->data->username)->first();

            if ($user != null) {

                $post = Post::create([
                    'user_id'        => $user->id,
                    'title'          => $request->input('title'),
                    'content'        => $request->input('content'),
                    'tags'           => $request->input('tags'),
                    'publishStatus'  => 'draft'
                ]);

                // if publishStatus is posted submit to meduim API
                if ($request->input('publishStatus') == 'posted') {
                    $requestSubmit = new Request([
                        'id'           => $post->id,
                        'access_token' => $accessToken,
                    ]);
                    $submitResponse = $this->submit($requestSubmit);
                    return response()->json($submitResponse->original->data, 201);
                }
        
                return response()->json($post, 201);

            } else {
                return response()->json(['error' => 'User not available.'], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // View post by user and post id
    public function view(Request $request) {

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'id'           => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 500);
        }

        try {

            $data = MediumApiHelper::getUser($request->all());
            $dataObj = json_decode($data);

            $user = User::where('username', $dataObj->data->username)->first();

            if ($user == null) {
                return response()->json(['error' => 'User not available.'], 500);
            }

            $post = Post::where('id', $request->input('id'))->where('user_id', $user->id)->first();

            if ($post == null) {
                return response()->json(['error' => 'Post not available.'], 500);
            }

            return response()->json($post, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // Submit local post to medium with draft status
    public function submit(Request $request) {

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'id'           => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 500);
        }

        try {

            $data = MediumApiHelper::getUser($request->all());
            $dataObj = json_decode($data);

            $user = User::where('username', $dataObj->data->username)->first();

            if ($user == null) {
                return response()->json(['error' => 'User not available.'], 500);
            }

            $post = Post::where('id', $request->input('id'))->where('user_id', $user->id)->first();

            if ($post == null) {
                return response()->json(['error' => 'Post not available.'], 500);
            }

            if ($post->publishStatus == 'posted') {
                return response()->json(['error' => 'Post already submitted.'], 500);
            }

            $tags = [];
            foreach (explode(",",$post->tags) as $tag) {
                $tags[] = $tag;
            }

            $postArray = array (
                "title" => $post->title,
                "contentFormat" => "html",
                "content" => $post->content,
                "tags" => explode(",",$post->tags),
                "publishStatus" => "draft"
            );
            
            $dataPost = MediumApiHelper::submitPost($request->input('access_token'), $user->medium_user_id, $postArray);
            $dataObj = json_decode($dataPost);

            Post::where('id', $request->input('id'))->where('user_id', $user->id)->update([
                'medium_post_id' => $dataObj->data->id,
                'url'            => $dataObj->data->url,
                'publishStatus'  => 'posted'
            ]);

            return response()->json($dataObj, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function uploadImage(Request $request) {

        $validator = Validator::make($request->all(), [
            'access_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorized, Access token not found'], 500);
        }

        try {
            
            $data = MediumApiHelper::uploadImage($request->all());
            $dataObj = json_decode($data);

            $image = new Image;
            $image->url = $dataObj->data->url;
            $image->md5 = $dataObj->data->md5;
            $image->save();

            return response()->json($image, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    
    
}
