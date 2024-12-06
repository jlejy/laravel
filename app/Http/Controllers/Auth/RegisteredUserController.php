<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            //유효성 검증
            $request->validate([
                'user_name' => 'required|string|max:255',
                'user_id' => 'required|string|email|unique:happy_member|max:255',
                'user_pass' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            //Laravel에서 HTTP 요청으로 들어온 모든 데이터를 배열로 반환하는 메소드
            Log::info('Received request data:', $request->all());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // 유효성 검사 오류를 로그에 기록
            Log::error('Validation failed:', $e->validator->errors()->toArray());
            Log::info('Request data:', $request->all()); // 추가 로그
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
//        $request->validate([
//            'user_name' => 'required|string|max:255',
////            'user_id' => 'required|string|lowercase|email|max:255|unique:'.User::class,
//            'user_id' => 'required|email|unique:happy_member,user_id|max:255', // user_id로 검증
//            'user_pass' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'user_id' => $request->user_id,
            'user_pass' => Hash::make($request->user_pass),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
