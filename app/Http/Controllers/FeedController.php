<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\PostComment;
use App\Postlike;
use App\PostComments;
use Image;
use App\User;
use App\UserRelation;

class FeedController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

    /**
     * The function creates a new post with either text or photo content, and returns an error message
     * if any required data is missing or if the file type is not supported.
     *
     * @param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. It contains information such as the request method,
     * headers, and input data.
     *
     * @return an array.
     */
    public function create(Request $request)
    {

        $array = ['error' => ''];
        $allowedTypes = ['image/png', 'image/jpeg', 'image/png'];
        $type = $request->input('type');
        $body = $request->input('body');
        $photo = $request->file('photo');

        if ($type) {
            switch ($type) {
                case 'text':
                    if (!$body) {
                        $array['error'] = 'Texto não enviado!';
                        return $array;
                    }
                    break;

                case 'photo':
                    if ($photo) {
                        if (in_array($photo->getClientMimeType(), $allowedTypes)) {
                            $filename = md5(time() . rand(0, 9999)) . '.jpg';
                            $desthPath = public_path('/media/uploads');
                            $img = Image::make($photo->path())
                                ->resize(800, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })
                                ->save($desthPath . '/' . $filename);

                            $body = $filename;
                        } else {
                            $array['error'] = 'Arquivo não Suportado.';
                            return $array;
                        }
                    } else {
                        $array['error'] = 'Arquivo não enviado.';
                        return $array;
                    }

                    break;
                default:
                    $array['error'] = 'Tipo de postagem inexistente';
                    return $array;
                    break;
            }

            if ($body) {
                $newPost = new Post();
                $newPost->id_user = $this->loggedUser['id'];
                $newPost->type = $type;
                $newPost->created_at = date('Y-m-d H:i:s');
                $newPost->body = $body;
                $newPost->save();
            }
        } else {
            $array['error'] = 'Dados nõa enviados.';
            return $array;
        }

        return $array;
    }

    public function read(Request $request)
    {
        //Get api/feed(Page)

        $array = ['error' => ''];

        $page = intval($request->input('page'));
        $perPage = 2;

        //1 , pegar  a lista de usuários que eu sigo.(incluindo eu msm)
        $user = [];
        $userList = UserRelation::where('user_from', $this->loggedUser['id']);
        foreach ($userList as $userItem) {
            $user[] = $userItem['user_to'];
        }

        $users[] = $this->loggedUser['id'];


        //2 , Pegar os post ordenados pela data
        $postList = Post::where('id_user', $users)
            ->orderBy('created_at', 'desc')
            ->offset($page * $perPage)
            ->limit($perPage)
            ->get();

        $total = Post::where('id_user', $users)->count();
        $pageCount = ceil($total / $perPage);



        //3 , Preencher as Informções adicionais
        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);


        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;




        return $array;
    }


    private function _postListToObject($postList, $loggedId)
    {

        /* The foreach loop is iterating over each item in the  array. For each item, it checks
       if the 'id_user' value is equal to the . If it is, it sets the 'mine' key in the
        array to true. Otherwise, it sets the 'mine' key to false. This is used to
       determine if the post belongs to the logged-in user or not. */

        foreach ($postList as $postKey => $postItem) {
            if ($postItem['id_user'] == $loggedId) {
                $postList[$postKey]['mine'] = true;
            } else {
                $postList[$postKey]['mine'] = false;
            }

            /* This code is retrieving the user information for each post in the post list. */
            $userInfo = User::find($postItem['id_user']);
            $userInfo['avatar'] = url('media/avatars/' . $userInfo['avatar']);
            $userInfo['cover'] = url('media/covers/' . $userInfo['cover']);
            $postList[$postKey]['user'] = $userInfo;


            $likes = Postlike::where('id_post', $postItem['id'])->count();
            $postList[$postKey]['likeCount'] = $likes;

            $isLiked = Postlike::where('id_post', $postItem['id'])
                ->where('id_user', $loggedId)
                ->count();
            $postList[$postKey]['liked'] = ($isLiked > 0) ? true : false;

            $comments = PostComment::where('id_post', $postItem['id'])->get();
            foreach ($comments as $commentKey => $comment) {
                $user = User::find($comment['id_user']);
                $user['avatar'] = url('media/avatars/' . $user['avatar']);
                $user['cover'] = url('media/covers/' . $user['cover']);
                $comments[$commentKey]['user'] = $user;
            }
            $postList[$postKey]['comments'] = $comments;
        }

        return $postList;
    }
}
