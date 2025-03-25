@component('mail::message')
# Welcome to {{ config('app.name') }}

Hi {{ $user->name }},

Thank you for registering! Please verify your email address by clicking the button below:

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Thanks,<br>
Admin {{ config('app.name') }}
@endcomponent
