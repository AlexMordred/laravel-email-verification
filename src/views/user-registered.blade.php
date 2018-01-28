@component('mail::message')
# Email Verification

Please, verify your email by clicking the button below.

@component('mail::button', ['url' => route('auth.email.verification', $token)])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
