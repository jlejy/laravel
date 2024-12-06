<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'email'],
            'user_pass' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     * 사용자의 로그인 시도를 처리하는 함수
     */
    public function authenticate(): void
    {
        //로그인 시도 횟수가 제한을 초과했는지 확인하는 메소드
        //요청이 너무 많으면, 이를 제한하기 위해 Rate Limiter가 동작
        $this->ensureIsNotRateLimited();

        //user_id가 $this->user_id와 일치하는 첫번째 사용자 레코드를 db에서 찾는다.
        //first()는 첫번째 레코드를 반환, 결과가 없으면 null 반환
        $user = User::where('user_id', $this->user_id)->first();

        //사용자가 로그인 시 필요한 자격 증명을 담는 배열
        //user_pass는 일반적으로 평문 비밀번호로 저장되지만, 실제로는 해시된 상태로 저장되므로,
        //Hash::check()와 같은 함수로 비교할 수 있다.
        $credentials = [
            'user_id' => $this->user_id,
            'user_pass' => $this->user_pass
        ];

        Log::info('Attempting login', ['credentials' => $credentials]);

        if(empty($user))
        {
            RateLimiter::hit($this->throttleKey());

            //인증 실패 메세지 반환
            throw ValidationException::withMessages([
                'user_id' => trans('auth.failed'),
            ]);
        }

        //attempt로 자격 증명해서 성공 시 true, 실패 시 false를 반환
//        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
        if (!Auth::attempt(['user_id' => $this->user_id, 'password' => $this->user_pass], $this->boolean('remember'))) {

            Log::error('Login failed: incorrect password'
                , ['user_id' => $this->user_id]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_id' => trans('auth.failed'),
            ]);
        }

        //remember 옵션을 활성화할지 여부를 결정하는 Boolean 값.
        // true면 사용자의 로그인 상태를 지속하는 쿠키가 설정됨
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'user_id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('user_id')).'|'.$this->ip());
    }
}
