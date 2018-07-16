<?php
namespace App\Api\Controllers;

use App\Api\Transformers\ApiUserTransformer;
use App\Models\Api\ApiUser;
use Illuminate\Http\Request;
use JWTAuth;
class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register(Request $request)
    {
        $newUser = [
            'name' => $request->post('name'),
            'password' => bcrypt($request->post('password'))
        ];

        $user = ApiUser::create($newUser);
        $token = JWTAuth::fromUser($user);
        return [
            'token'     =>  $token,
        ];
    }

    public function authenticate(Request $request)
    {
        $payload = [
            'name' => $request->post('name'),
            'password' => $request->post('password')
        ];
        try {
            if (!$token = JWTAuth::attempt($payload)) {
                return response()->json(['error' => 'token_not_provided'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => '不能创建token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function AuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // the token is valid and we have found the user via the sub claim

        return $this->item($user,new ApiUserTransformer());
        return response()->json(compact('user'));
    }

    /*****************************************************第二种方式**************************************************/
    public function login(Request $request)
    {
        $credentials = [
            'email'         =>  $request->post('email'),
            'password'      =>  $request->post('password'),
        ];

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}