<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $url;
    public $email;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $url, $email, $token)
    {
        //
        $this->name = $name;
        $this->url = $url;
        $this->email = $email;
        $this->token = $token;
    }

//    public function build()
//    {
//        return $this->subject('Password Reset Request')
//            ->view('emails.password_reset')
//            ->with(['userName' => $this->user
//        ]);
//    }

    /**
     * Get the message envelope.
     */
    /*
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '비밀번호 재설정',
        );
    }
    */

    /**
     * Get the message content definition.
     */
    /*
    public function content(): Content
    {
        return new Content(
//            view: 'view.name',
        view: 'resetPassword'
        );
    }
    */

    public function build()
    {
        return $this->subject('비밀번호 재설정 요청')->html('
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="header" style="padding: 25px 0; text-align: center;">
                        <a href="http://localhost:8000" style="color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none;">eloun</a>
                    </td>
                </tr>
                <tr>
                    <td class="body" style="background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 2px; margin: 0 auto; padding: 0; width: 570px;">
                            <tr>
                                <td class="content-cell" style="padding: 32px;">
                                    <h1 style="color: #3d4852; font-size: 18px; font-weight: bold; text-align: left;">Hello!</h1>
                                    <p style="font-size: 16px; line-height: 1.5em; text-align: left;">You are receiving this email because we received a password reset request for your account.</p>
                                    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 30px auto; text-align: center;">
                                        <tr>
                                            <td align="center">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                                    <tr>
                                                        <td>
                                                            <a href="' . url('reset-password/' . $this->token . '?email=' . $this->email) . '" class="button button-primary" style="background-color: #2d3748; border-radius: 4px; color: #fff; text-decoration: none; padding: 8px 18px;">Reset Password</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="font-size: 16px; line-height: 1.5em; text-align: left;">This password reset link will expire in 60 minutes.</p>
                                    <p style="font-size: 16px; line-height: 1.5em; text-align: left;">If you did not request a password reset, no further action is required.</p>
                                    <p style="font-size: 16px; line-height: 1.5em; text-align: left;">Regards,<br>eloun</p>
                                    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                        <tr>
                                            <td>
                                                <p style="line-height: 1.5em; font-size: 14px;">If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <span class="break-all"><a href="' . url('reset-password/' . $this->token . '?email=' . $this->email) . '" style="color: #3869d4;">' . url('reset-password/' . $this->token . '?email=' . $this->email) . '</a></span></p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td class="content-cell" align="center" style="padding: 32px;">
                                    <p style="color: #b0adc5; font-size: 12px; text-align: center;">© 2024 eloun. All rights reserved.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>');



//        return $this->subject('비밀번호 재설정 요청')
//            ->html("
//                <p>{$this->name}님,</p>
//                <p>비밀번호 재설정을 요청하셨습니다. 아래 링크를 클릭하여 비밀번호를 재설정하세요:</p>
//                <p><a href='{$this->url}' style='color: blue;'>비밀번호 재설정하기</a></p>
//                <p>만약 이 요청을 본인이 하지 않았다면, 이 이메일을 무시해 주세요.</p>")
//        ;

    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
