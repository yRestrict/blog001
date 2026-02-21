<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | Authentication messages used in controllers
    |
    */

    // Laravel Default
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    
    // Login Validation
    'login_required' => 'Please enter your email or username.',
    'login_email' => 'Please enter a valid email address.',
    'login_not_found_email' => 'No account found with this email.',
    'login_not_found_username' => 'No account found with this username.',
    
    // Password Validation
    'password_required' => 'Please enter your password.',
    'password_min' => 'The password must be at least :min characters.',
    'incorrect_password' => 'Incorrect password.',
    
    // Account Status
    'account_pending' => 'Your account is pending approval. Please contact support.',
    'account_inactive' => 'Your account is inactive. Please contact support.',
    'account_rejected' => 'Your account has been rejected. Please contact support.',
    'account_status_error' => 'Your account is :status. Please contact support.',
    
    // Password Reset
    'reset_link_sent' => 'Reset link sent to your email!',
    'reset_link_error' => 'Error sending email. Please try again.',
    'email_required' => 'Please enter your email.',
    'email_invalid' => 'Please enter a valid email.',
    'email_not_found' => 'No account found with this email.',
    'reset_email_subject' => 'Reset Password',
    
    // Reset Password
    'token_invalid' => 'Invalid token. Please request a new link.',
    'token_expired' => 'Invalid or expired token.',
    'user_not_found' => 'User not found.',
    'password_reset_success' => 'Password changed successfully! You can now log in.',
    'password_changed_email_subject' => 'Password Changed',
    'new_password_required' => 'Please enter your new password.',
    'new_password_min' => 'The new password must be at least :min characters.',
    'new_password_confirmed' => 'Passwords do not match.',
    
    // General
    'dashboard' => 'Dashboard',
    'logout_success' => 'You have been logged out.',
    'login_required_access' => 'You must be logged in to access the admin area. Please login to continue.',

];