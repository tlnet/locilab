<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;

class AnimalController extends Controller
{
    public function animalGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = Animal::with('owner', 'resenhas')->where('user_id', $user->id)->paginate($request->per_page ?? 20);
        if($id) $data = Animal::with('owner', 'resenhas')->where('id',$id)->where('user_id', $user->id)->first();
        return response()->json($data, 200);
    }

    public function animalPost(Request $request)
    {
        \DB::beginTransaction();
        $user = user_token();

        try{
            $create_animal = collect($request->all())->put('user_id', $user->id)->forget(['owner', 'resenha_animals']);
            $animal = Animal::create($create_animal->toArray());
            $create_owner = collect($request->owner)->put('user_id', $user->id)->put('animal_id', $animal->id);
            Owner::create($create_owner->toArray());

            collect($request->resenha_animals)->map(function($query) use($user, $animal){
                $new_query = $query;
                $new_query['user_id'] = $user->id;
                $new_query['animal_id'] = $animal->id;
                if(isset($query['photo']) && strpos($query['photo'], ';base64')){
                    $path = 'app/public/user_'.$user->id.'/animal_'.$animal->id.'/';
                    $originalPath = storage_path($path);
                    if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);
                    $base64 = $query['photo'];
                    //obtem a extensão
                    $extension = explode('/', $base64);
                    $extension = explode(';', $extension[1]);
                    $extension = '.'.$extension[0];
                    //gera o nome
                    $name = \Str::random(20).$extension;
                    //obtem o arquivo
                    $separatorFile = explode(',', $base64);
                    $file = $separatorFile[1];
                    //envia o arquivo
                    \Storage::put(str_replace('app/', '', $path).$name, base64_decode($file));
                    $new_query['photo_path'] = str_replace('app/public', 'storage', $path).$name;
                }
                unset($new_query['photo']);

                ResenhaAnimal::create($new_query);
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Animal::with('owner', 'resenhas')->find($animal->id), 200);
    }

    public function animal(Request $request)
    {
        \DB::beginTransaction();
        $user = user_token();

        try{
            $create_animal = collect($request->all())->put('user_id', $user->id)->forget(['owner', 'resenha_animals']);
            $animal = Animal::create($create_animal->toArray());
            $create_owner = collect($request->owner)->put('user_id', $user->id)->put('animal_id', $animal->id);
            Owner::create($create_owner->toArray());

            collect($request->resenha_animals)->map(function($query) use($user, $animal){
                $new_query = $query;
                $new_query['user_id'] = $user->id;
                $new_query['animal_id'] = $animal->id;
                if(isset($query['photo']) && strpos($query['photo'], ';base64')){
                    $path = 'app/public/user_'.$user->id.'/animal_'.$animal->id.'/';
                    $originalPath = storage_path($path);
                    if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);
                    $base64 = $query['photo'];
                    //obtem a extensão
                    $extension = explode('/', $base64);
                    $extension = explode(';', $extension[1]);
                    $extension = '.'.$extension[0];
                    //gera o nome
                    $name = \Str::random(20).$extension;
                    //obtem o arquivo
                    $separatorFile = explode(',', $base64);
                    $file = $separatorFile[1];
                    //envia o arquivo
                    \Storage::put(str_replace('app/', '', $path).$name, base64_decode($file));
                    $new_query['photo_path'] = str_replace('app/public', 'storage', $path).$name;
                }
                unset($new_query['photo']);

                ResenhaAnimal::create($new_query);
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Animal::with('owner', 'resenhas')->find($animal->id), 200);
    }
}
