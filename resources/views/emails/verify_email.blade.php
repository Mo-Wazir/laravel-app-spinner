@component('mail::message')
# Verify Your Email Address

Hello Dear {{ $data['name'] }},<b>

Thank you for signing up with us! Before you can start exploring our platform, we need to verify your email address.<br>
Kindly make use of the One Time Password below<br>

# {{ $data['otp'] }}

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
