<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\Users_Password_Resets;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //64자리의 랜덤 문자열 생성
        $token = Str::random(64);
        $request->validate(['user_id' => 'required|email']);
        $email = $request->input('user_id');

        $userModel = User::where('user_id', $email)->first();
        if(empty($userModel)){
            return $this->returnFailed('가입된 아이디가 없습니다.');
        }
        else
        {
            $passwordModel = Users_Password_Resets::where('email', $email)->first();
            date_default_timezone_set("Asia/Seoul");

            if(empty($passwordModel))
            {
                Users_Password_Resets::create([
                    'email'=>$email,
                    'token'=>$token,
                    'created_at'=>date('Y-m-d H:i:s')]);
            }
            else
            {
                $passwordModel::where('email', $email)->update([
                    'token'=>$token,
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }

//            $url = route('password.reset', $token);
            $url = env('APP_URL') . "/reset-password/{$token}?email={$email}";
            Mail::to($email)->send(new PasswordReset($userModel->name, $url, $email, $token));
//            return $this->returnSuccess('비밀번호 재설정 메일을 보냈어요.');
//            return response()->json(['success' => true, 'message' => '비밀번호 재설정 메일을 보냈어요.'], 200);
//            $msg = "비밀번호 재설정 메일을 보냈어요.";
//            $res = array();
//            $res['status'] = 'SUCCESS';
//            $res['code'] = 200;
//            if(!empty($data))$res['data'] = $data;
//            if(!empty($msg))$res['msg'] = $msg;
//            return response()->json($res);


        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
//        $status = Password::sendResetLink(
//            $request->user_id
//        );
        //2024.11.26 추가
//        $status = Password::sendResetLink(
////            ['user_id' => $request->input('user_id')] // user_id 사용
//            $request->only('user_id')
//        );

//        if ($status == Password::RESET_LINK_SENT) {
//            return back()->with('status', __($status));
//        }
//
//        throw ValidationException::withMessages([
//            'user_id' => [trans($status)],
//        ]);
    }
}
