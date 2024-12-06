<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            //비밀번호 재설정 라우트가 존재하는지 확인하고
            'canResetPassword' => Route::has('user_pass.request'),
            //세션에서 status 메세지를 가져와 뷰에 전달한다.
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        //user_id와 user_pass 필드를 배열로 반환하여 log를 남김
        Log::info('Attempting login', ['credentials' => $request->only('user_id', 'user_pass')]);

        //사용자가 로그인 정보를 인증하는 메소드
        $request->authenticate();

        //인증이 성공하면 세션을 새로 고치기 위해 호출됨
        $request->session()->regenerate();

        //사용자를 로그인 후에 dashboard로 리다이렉트 시킨다.
        return redirect()->intended(route('dashboard', absolute: false));
    }

//    public function authenticate()
//    {
//        $credentials = $this->only('user_id', 'user_pass');
//
//        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
//            throw ValidationException::withMessages([
//                'user_id' => __('These credentials do not match our records.'),
//            ]);
//        }
//    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
