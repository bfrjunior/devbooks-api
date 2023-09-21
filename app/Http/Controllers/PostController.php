<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\Postlike;
use App\PostComment;

class PostController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

    public function like($id)
    {
        $array = ['error' => ''];

        //1 verificar se o post existe
        $postExists = Post::find($id);
        if ($postExists) {
            $isLiked = Postlike::where('id_post', $id)
                ->where('id_user', $this->loggedUser['id'])
                ->count();

            if ($isLiked > 0) {
                $pl = Postlike::where('id_post', $id)
                    ->where('id_user', $this->loggedUser['id'])
                    ->first();

                $pl->delete();
                $array['isLiked'] = false;
            } else {
                $newPosLike = new Postlike();
                $newPosLike->id_post = $id;
                $newPosLike->id_user = $this->loggedUser['id'];
                $newPosLike->created_at = date('Y-m-d H:i:s');
                $newPosLike->save();

                $array['isLiked'] = true;
            }

            $likeCount = Postlike::where('id_post', $id)
                ->count();
            $array['likeCount'] = $likeCount;
        } else {
            $array['error'] = 'Post não existe';
            return $array;
        }


        //2 verificar like

        return $array;
    }

    public function comment(Request $request, $id)
    {
        $array = ['error' => ''];

        $txt = $request->input('txt');

        $postExists = Post::find($id);
        if ($postExists) {
            if ($txt) {
                $newComment = new PostComment();
                $newComment->id_post = $id;
                $newComment->id_user = $this->loggedUser['id'];
                $newComment->created_at = date('Y-m-d H:i:s');
                $newComment->body = $txt;
                $newComment->save();
            } else {
                $array['error'] = 'Não enviou mensagem.';
                return $array;
            }
        } else {
            $array['error'] = 'Post não existe!';
            return $array;
        }

        return $array;
    }
}
