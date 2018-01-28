<?php

return [
    // How many days is a verification token valid for?
    'valid_for' => 7,

    // Where to redirect user in case of successful verification
    'redirect_on_success' => '/home',

    // Where to redirect user in case of unsuccessful verification or in case
    // the user's account/email is not verified
    'redirect_on_failure' => '/login',
];
