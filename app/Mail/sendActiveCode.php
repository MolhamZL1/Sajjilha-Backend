<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendActiveCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $title ,public $content)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
 public function content(): Content
{
    $appName = 'سجّلها';

    $html = '
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>'.e($this->title).'</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @media only screen and (max-width: 600px) {
                .container {
                    width: 100% !important;
                    padding: 0 10px !important;
                }
            }
        </style>
    </head>
    <body style="margin:0; padding:0; background-color: #f4f4f4; font-family: Tahoma, Arial, sans-serif; direction:rtl; text-align:right;">

        <div style="width:100%; padding:20px 0;">
            <div class="container" style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.06); overflow:hidden;">

                <!-- الهيدر -->
                <div style="background:linear-gradient(135deg,#4c6fff,#2ac4ff); padding:18px 24px; color:#ffffff;">
                    <h1 style="margin:0; font-size:20px;">'.$appName.'</h1>
                    <p style="margin:4px 0 0; font-size:13px; opacity:0.9;">
                        إدارة حسابك في '.$appName.' بسهولة وأمان
                    </p>
                </div>

                <!-- المحتوى الرئيسي -->
                <div style="padding:24px 24px 16px;">
                    <h2 style="margin:0 0 12px; font-size:18px; color:#222;">'.e($this->title).'</h2>

                    <p style="margin:0 0 12px; font-size:14px; color:#555; line-height:1.8;">
                        إليك رمز التفعيل الخاص بحسابك في تطبيق '.$appName.':
                    </p>

                    <!-- صندوق الكود في النص -->
                    <div style="
                        margin:16px auto 20px;
                        max-width:260px;
                        background-color: #f7f9ff;
                        border:1px dashed #16A34A;
                        border-radius:12px;
                        padding:14px 10px;
                        text-align:center;
                    ">
                        <span style="
                            display:inline-block;
                            font-size:22px;
                            letter-spacing:4px;
                            font-weight:bold;
                            color:#2b3a80;
                        ">
                            '.e($this->content).'
                        </span>
                    </div>

                    <p style="margin:0 0 8px; font-size:13px; color:#555; line-height:1.8;">
                        يرجى إدخال هذا الرمز في التطبيق لإكمال تفعيل حسابك.
                    </p>
                </div>

                <!-- ملاحظة وتحية -->
                <div style="padding:0 24px 24px;">
                    <p style="margin:0 0 8px; font-size:13px; color:#777;">
                        إذا لم تطلب هذه العملية، يمكنك تجاهل هذا البريد الإلكتروني بأمان.
                    </p>

                    <p style="margin:16px 0 0; font-size:13px; color:#999;">
                        مع التحية،<br>
                        فريق <strong>'.$appName.'</strong>
                    </p>
                </div>

                <!-- الفوتر -->
                <div style="background-color:#fafafa; padding:12px 24px; border-top:1px solid #eee; text-align:center; font-size:11px; color:#aaa;">
                    '.$appName.' &mdash; جميع الحقوق محفوظة.
                </div>

            </div>
        </div>
    </body>
    </html>';

    return new Content(
        htmlString: $html,
    );
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
