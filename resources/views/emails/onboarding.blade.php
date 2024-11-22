@component('mail::message')
# Bem-vindo à Nossa Plataforma

Sua conta foi criada com sucesso. Abaixo estão os detalhes do seu login:

**Email:** {{ $email }}
**Senha:** {{ $senha }}

Por favor, altere sua senha após fazer login pela primeira vez.

@component('mail::button', ['url' => $url])
Faça Login Agora
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
