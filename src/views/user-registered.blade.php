@component('mail::message')
# @lang('email-verification::email_verification.email.subject')

@lang('email-verification::email_verification.email.message')

@component('mail::button', ['url' => route('auth.email.verification', $token)])
@lang('email-verification::email_verification.email.button')
@endcomponent

@lang('email-verification::email_verification.email.footer'),<br>
{{ config('app.name') }}
@endcomponent
