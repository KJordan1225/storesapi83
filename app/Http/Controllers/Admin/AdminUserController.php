<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\UserTransformer;
use App\Transformers\UserProfileTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Validation\Rules\Password;
use Validator;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fractal = new Manager();

        if (! $users = User::all()) {
            throw new NotFoundHttpException('Users not found.');
        }
        
        $resource = new Collection($users, new UserTransformer());

        return response()->json($fractal->createData($resource)->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email|max:30',
            'name' => 'required|string|min:3|max:30',
            'password' => 'required|string',
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
        ]);        

        if ($validator->fails()) {
            return response()->json([
                'validation errors' => $validator->errors()
            ]);
        }

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

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
    
            $user->assignRole('customer');

        } catch( NotFoundHttpException $e) {
            throw new NotFoundHttpException('User not created');
        }
        
        // Maybe send a mail to the user about account creation and option to reset password.
        $response = [
            'message' => 'User created successfully' ,
            ' id' => $user->id
        ];

        $fractal = new Manager();
        $resource = new Item($user, new UserTransformer());
        return response()->json($fractal->createData($resource)->toArray());
    }

    private function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $user = User::find($id)) {
            throw new NotFoundHttpException( 'User not found with id = ' . $id) ;
        }
        
        $fractal = new Manager();
        $resource = new Item($user, new UserTransformer());
        return response()->json($fractal->createData($resource)->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
