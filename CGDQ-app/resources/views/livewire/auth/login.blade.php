<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>


<div class="dark:bg-gray-900">
    <div class="flex justify-center h-screen">
        <div class=" lg:w-2/4">
        <div class="relative h-screen">
  <!-- Fondo oscuro -->
  <div class="absolute inset-0 bg-cover bg-center brightness-40" style="background-image: url('{{ asset('img/portada.png') }}')"></div>

  <!-- Contenido encima -->
  <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4">
    <h2 class="text-4xl font-bold text-white">Cementerio General de Quetzaltenango</h2>
    <p class="max-w-xl mt-3 text-white text-justify">
    Bienvenido al sistema de gestión digital del Cementerio General de Quetzaltenango.
    Esta plataforma moderniza la administración de nichos municipales, contratos, pagos y exhumaciones, garantizando un manejo eficiente, seguro y transparente de la información.
    </p>
  </div>
</div>

        </div>
        <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/5 p-10">
       
           
            <div class="flex-1">
                <div class="flex flex-col gap-4">
                    <div class="justify-center items-center flex">
                    <img class="w-40" src="img/logotipo.png" alt="">
                    </div>
                    <x-auth-header :title="__('Inicia sesión con tu cuenta')" :description="__('Ingrese su correo electrónico y contraseña a continuación para iniciar sesión')" />
                    <!-- Session Status -->
                    <x-auth-session-status class="text-center" :status="session('status')" />
                    <form wire:submit="login" class="flex flex-col gap-6">
                        <!-- Email Address -->
                        <flux:input wire:model="email" :label="__('Direccion de correo electronico')" type="email" required autofocus
                            autocomplete="email" placeholder="email@example.com" />
                        <!-- Password -->
                        <div class="relative">
                            <flux:input wire:model="password" :label="__('Contraseña')" type="password" required
                                autocomplete="current-password" :placeholder="__('Password')" />

                            @if (Route::has('password.request'))
                                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')"
                                    wire:navigate>
                                    {{ __('Olvidaste tu contraseña') }}
                                </flux:link>
                            @endif
                        </div>
                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="bg-green-500 w-full">{{ __('Ingresar') }}
                            </flux:button>
                        </div>
                    </form>
                    @if (Route::has('register'))
                        <!-- <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-500">
                            {{ __('Don\'t have an account?') }}
                            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
                        </div> -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
