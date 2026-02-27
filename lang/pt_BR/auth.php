<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | Mensagens de autenticação usadas nos controllers
    |
    */

    // Laravel Default
    'failed' => 'Essas credenciais não foram encontradas em nossos registros.',
    'password' => 'A senha informada está incorreta.',
    'throttle' => 'Muitas tentativas de login. Tente novamente em :seconds segundos.',
    
    // Login Validation
    'login_required' => 'Informe seu e-mail ou usuário.',
    'login_email' => 'Digite um e-mail válido.',
    'login_not_found_email' => 'Nenhuma conta encontrada com este e-mail.',
    'login_not_found_username' => 'Nenhuma conta encontrada com este usuário.',
    'account_banned' => 'Sua conta foi banida.',

    
    // Password Validation
    'password_required' => 'Informe sua senha.',
    'password_min' => 'A senha deve ter pelo menos :min caracteres.',
    'incorrect_password' => 'Senha incorreta.',
    
    // Account Status
    'account_pending' => 'Sua conta está pendente de aprovação. Contate o suporte.',
    'account_inactive' => 'Sua conta está inativa. Contate o suporte.',
    'account_rejected' => 'Sua conta foi rejeitada. Contate o suporte.',
    'account_status_error' => 'Sua conta está :status. Contate o suporte.',
    
    // Password Reset
    'reset_link_sent' => 'Link de recuperação enviado para o seu e-mail!',
    'reset_link_error' => 'Erro ao enviar e-mail. Tente novamente.',
    'email_required' => 'Informe seu e-mail.',
    'email_invalid' => 'Digite um e-mail válido.',
    'email_not_found' => 'Nenhuma conta encontrada com este e-mail.',
    'reset_email_subject' => 'Redefinir Senha',
    
    // Reset Password
    'token_invalid' => 'Token inválido. Solicite um novo link.',
    'token_expired' => 'Token inválido ou expirado.',
    'user_not_found' => 'Usuário não encontrado.',
    'password_reset_success' => 'Senha alterada com sucesso! Você já pode fazer login.',
    'password_changed_email_subject' => 'Senha Alterada',
    'new_password_required' => 'Informe a nova senha.',
    'new_password_min' => 'A nova senha deve ter pelo menos :min caracteres.',
    'new_password_confirmed' => 'As senhas não conferem.',
    'new_password_same_as_current' => 'A nova senha não pode ser igual à senha atual.',
    'password_changed_success' => 'Senha alterada com sucesso! Faça o login novamente.',

    
    // General
    'dashboard' => 'Dashboard',
    'logout_success' => 'Você saiu da sua conta.',
    'login_required_access' => 'Você deve estar logado para acessar a área administrativa. Por favor, faça o login para continuar.',

];