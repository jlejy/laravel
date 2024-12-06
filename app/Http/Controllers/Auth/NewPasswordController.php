<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Users_Password_Resets;
use Illuminate\Support\Facades\DB;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'user_id' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'user_id' => 'required|email',
            'user_pass' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->input('user_id');
        $resetModel = Users_Password_Resets::where('email', $email)->first();

        if(empty($resetModel))
        {
            return back()->withErrors(['user_id' => '해당 아이디로 가입된 내역이 없습니다.']);
        }
        else
        {
            $userModel = User::where('user_id', $email)->first();
            $userModel->user_pass = Hash::make($request->user_pass);
            $userModel->save();
//            //토큰 삭제
////            $resetModel->delete();
//            DB::beginTransaction();
//            try {
//                $resetModel->delete();
//                DB::commit();
//            } catch (\Exception $e) {
//                DB::rollBack();
//                return redirect()->back()->widthErrors(['message' => 'Error: ' . $e->getMessage()], 500);
//            }
            return redirect()->route('login')->with('status', '비밀번호가 변경되었습니다.!');
        }


        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
//        $status = Password::broker('happy_member')->reset(
//            $request->only('user_id', 'user_pass', 'token'),
//            function ($user) use ($request) {
//                $user->forceFill([
//                    'user_pass' => Hash::make($request->user_pass),
//                    'remember_token' => Str::random(60),
//                ])->save();
//
//                event(new PasswordReset($user));
//            }
//        );
//        $status = Password::reset(
//            $request->only('user_id', 'user_pass', 'token'),
//            function ($user) use ($request) {
//                $user->forceFill([
//                    'user_pass' => Hash::make($request->user_pass),
//                    'remember_token' => Str::random(60),
//                ])->save();
//
//                event(new PasswordReset($user));
//            }
//        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'user_id' => [trans($status)],
        ]);
    }
}
