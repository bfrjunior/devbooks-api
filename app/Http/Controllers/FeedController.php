<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use Image;

class FeedController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

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
}
