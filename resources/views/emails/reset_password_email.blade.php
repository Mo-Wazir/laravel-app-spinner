@component('mail::message')
# Reset Your Password

Hello {{ $data['name'] }},

You are receiving this email because we received a password reset request for your account.<br>
Make Use of the One Time Password below<br>
# {{ $data['token'] }}


If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
