<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\UserProfile;


class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user();
        } catch (JWTException $th) {
            throw $th;
        }
        
        $fractal = new Manager();
        $resource = new Collection($user, new UserTransformer());
        return response()->json($fractal->createData($resource)->toArray());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $rules = [
            'first_name' => 'required|string|max:30|min:3',
            'last_name' => 'required|string|max:30|min:3',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'dob' => 'required|date',
            'queversary' => 'required|date',
            'phone_type' => 'required|in:mobile,landline', 
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            if (! $user = auth()->user()) {
                throw new NotFoundHttpException('User not found');
            };

            $user->userProfile()->updateOrCreate(['user_id' => $user->id],
                [    
                    'first_name' => $request->first_name,
                    'last_name' =>$request->last_name,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                    'phone_number' => $request->phone_number,
                    'phone_type' => $request->phone_type,
                    'dob' => $request->dob,
                    'queversary' => $request->queversary,
                ]
            );    
        } catch (JWTException $th) {
            throw $th;
        }

        $response = [
            'message' => 'User profile updated successfully',
            'id' => $user->id,
        ];
            
        return response()->json($response, 201) ;

        
    }

    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Taken care of by index function
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        // Taken care of by store function

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        try {
            $user = auth()->user();
            $user->delete();
        } catch (JWTException $th) {
            throw $th;
        }

        $response = [
            'message' => 'User profile deleted successfully. User logged out.',
            'id' => $user->id,
        ];
            
        return response()->json($response, 201);
        
        auth()->logout();
    }
}
