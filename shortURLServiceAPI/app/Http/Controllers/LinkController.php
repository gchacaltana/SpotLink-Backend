<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Services\TokenService;
use App\Exceptions\AuthException;
use App\Exceptions\TokenException;
use App\Helpers\StringHelper;
use App\Services\CacheService;

/**
 * @OA\Tag(
 *     name="API Link",
 *     description="API para acortar y redirigir URLs"
 * )
 */
class LinkController extends Controller
{
    /**
     * Método para obtener la URL de un token (short link)
     * @OA\Get (
     *     path="/api/v1/links/{token}",
     *     tags={"API Link"},
     *     @OA\Parameter(
     *        name="token",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *         type="string"
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="http://www.example.com")
     *        )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="Link not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Link not found")
     *          )
     *      )
     * )
     */
    public function getUrlByToken(string $token)
    {
        try {
            StringHelper::validateTokenFormat($token);
            $url = CacheService::getToken($token);
            if (!is_null($url)) {
                if (StringHelper::validateUrl($url)) {
                    return response()->json([
                        'url' => $url
                    ]);
                }
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
     * @OA\Post (
     *     path="/api/v1/links",
     *     tags={"API Link"},
     *     security={{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="http://www.example.com")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="url", type="string"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="created_at", type="string"),
     *             @OA\Property(property="updated_at", type="string")
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *          )
     *      )
     * )
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

    /**
     * Método para obtener los short links de un usuario
     * @OA\Get (
     *     path="/api/v1/links",
     *     tags={"API Link"},
     *     security={{ "Authorization": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="url", type="string"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="created_at", type="string"),
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *          )
     *      )
     * )
     */
    public function getLinksByUser()
    {
        try {
            $this->validateAuthUser();
            $links = Link::where('user_id', auth()->user()->id)->get(['id', 'url', 'token', 'created_at']);
            return response()->json($links, 200);
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
