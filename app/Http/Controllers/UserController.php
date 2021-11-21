<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\MediumApiHelper;
use App\Models\User;

class UserController extends Controller
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

            $user = User::firstOrCreate(
                ['username' => $dataObj->data->username],
                [
                    'medium_user_id' => $dataObj->data->id,
                    'username'       => $dataObj->data->username,
                    'name'           => $dataObj->data->name,
                    'url'            => $dataObj->data->url,
                    'imageUrl'       => $dataObj->data->imageUrl
                ]
            );

            return response()->json($user, 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    
}
