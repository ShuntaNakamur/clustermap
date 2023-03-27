<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\TwoFactorAuthPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{

    public function validateEmail(Request $request){
        $validatedData = $request->validate([
            'email' =>["required",'email:rfc','email:dns','max:225',]
        ]);
        $digits = '';
        for($i = 0 ; $i < 10 ; $i++) {
            $digits .= strval(rand(0, 9));
        }
        $chars = '';
        for($i = 0; $i < 10; $i++){
            $chars .= chr(mt_rand(97, 122));
        }
        $length=rand(5,8);
        $random_password=substr(str_shuffle($digits.=$chars), 0, $length);
        $user = User::updateOrInsert([
                'email' => $validatedData['email'],
        ],
        [
                'tfa_token' => (string)$random_password,
                'tfa_expiration' => now()->addMinutes(10)
        ]);
        \Mail::to($user->first())->send(new TwoFactorAuthPassword($random_password));


        // $token = $user->first()->createToken('auth_token')->plainTextToken;


        return response()->json([

                'result'=>$user->first()->id
        ]);
    }


    public function validateUser(Request $request) {  // ２段階目の認証
        $user = User::find($request->id);

        $result = false;
        $expiration = new Carbon($user->tfa_expiration);
        if($user->tfa_token  === $request->token && $expiration > now()) {


            $user->save();
            $result =  $user->createToken('auth_token')->plainTextToken;;

        }
        return response()->json([

            'result' => $result,
        ]);




    }

    public function updateUser(Request $request){
        $user=$request->user();
        $update=array();
        if($request->has('image')){
            $user = $request->user();
            $path="/Users/nakamurashunta/my-app/public/storage/$user->image_path";
            File::delete($path);
            $publicPath = $request->file('image')->store('public');
            $image=substr($publicPath,7,);
            $update['image_path']=$image;

        }
        if($request->has('name')){
            $update['name']=$request->name;
        }
        if($request->has('birth')){
            $update['birth']=$request->birth;
        }
        if($request->has('comment')){
            $update['comment']=$request->comment;
        }
        print($user->email);
        print($user->id);
        User::updateOrInsert([
            'email' => $user->email,

        ],
        $update
        );


    }

    public function getUser(Request $request){
        $id=$request->id;


        $user = User::find($id);
        $birth=new Carbon($user->birth);
        $age=$birth->diffInYears(now());


        return response()->json([
            'name' => $user->name,
            'age'=>$age,
            'comment'=>$user->comment,

           ]);
    }

    public function get_image(Request $request){
        $id=$request->id;
        $filename=User::select("image_path")->find($id);
        $image=$filename->image_path;
        $path="/Users/nakamurashunta/my-app/public/storage/$image";
        $result=File::exists($path);
        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file)->header("Content-Type", $type);



        // $id=$request->user_id;
        // $filename=User::select("image_path")->find($id);
        // $image=$filename->image_path;
        // $mimeType = Storage::mimeType("public/storage/$image");

        // return Response::download("public/storage/$image");
    }

    public function delete_users(Request $request){
        $user = $request->user();
        $path="/Users/nakamurashunta/my-app/public/storage/$user->image_path";
        File::delete($path);
        User::find($user->id)->delete();
    }

}
