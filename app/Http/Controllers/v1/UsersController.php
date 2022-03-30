<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\Http\Resources\v1\UserCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index()
    {
        return new UserCollection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data['name'] = $request->name;
        if(isset($request->password) && !empty($request->password)){
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $products = DB::table('products')
            ->where('user_id', '=', $user->id)
            ->select('id')->get()->toArray();
        if(count($products) > 0)
            return response(['success' => 0 , 'message' => 'Ops This user have products'] , \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        if($user->delete())
            return response(['success' => 1 , 'message' => 'Deleted Successfully'], \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        return response('Something went Wrong' , \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
    }

    public function login(LoginUserRequest $request){
        $email = trim($request->email);
        if(!Auth::attempt(['email' => $email , 'password' => $request->password])){
            return response([
                "message" => "invalid password and email"
            ] , \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('sager')->plainTextToken;

        $cookie = cookie('jwt' , $token , 60 * 24);

        return response([
            'message' => "Success",
            'user' => new UserResource($user)
        ])->withCookie($cookie);
    }

    public function logout(){
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'Logout successfully'
        ])->withCookie($cookie);
    }

    public function register(StoreUserRequest $request)
    {

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password)
        ]);

        return new UserResource($user);
    }

}
