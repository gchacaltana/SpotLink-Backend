<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.bearer');
    }

    public function getInfoByToken(string $token)
    {
        try {
            $link = Link::where('token', $token)->first();
            if ($link) {
                return response()->json([
                    'url' => $link->url,
                    'token' => $link->token,
                    'user_id' => $link->user_id,
                ]);
            }
            return response()->json([
                'message' => 'Link not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $url = $request['url'];
            $token = substr(md5($url), 0, 7);
            $user_id = $request['user_id'];
            $link = Link::create([
                'url' => $url,
                'token' => $token,
                'user_id' => $user_id
            ]);
            return response()->json($link, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
