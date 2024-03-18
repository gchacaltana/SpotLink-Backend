<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Services\TokenService;
use App\Exceptions\AuthException;
use App\Exceptions\TokenException;
use App\Helpers\StringHelper;
use App\Services\CacheService;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.bearer');
    }

    /**
     * Método para obtener la URL de un token (short link)
     */
    public function getUrlByToken(string $token)
    {
        try {
            $this->validateAuthUser();
            StringHelper::validateTokenFormat($token);
            $url = CacheService::getToken($token);
            if (StringHelper::validateUrl($url)) {
                return response()->json([
                    'url' => $url
                ]);
            }
            $link = Link::where('token', $token)->first();
            if ($link) {
                return response()->json([
                    'url' => $link->url
                ]);
            }
            return response()->json([
                'message' => 'Link not found'
            ], 404);
        } catch (AuthException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        } catch (TokenException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para crear un short link
     * @param Request $request
     */
    public function create(Request $request)
    {
        try {
            $this->validateAuthUser();
            $request->validate([
                'url' => 'required|string|url'
            ]);
            $url = $request['url'];
            $token = TokenService::generateToken(env('SU_APP_ID'));
            $link = Link::create([
                'url' => $url,
                'token' => $token,
                'user_id' => auth()->user()->id
            ]);
            CacheService::saveToken($token, $url);
            return response()->json($link, 200);
        } catch (AuthException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
