<?php

use App\Models\User;
use App\Models\Person;
use App\Models\Gender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $lastname = '';
    public string $cui = '';       
    public string $gened_id = '';    
    public string $dob = '';
    public string $address = '';
    public string $phone = '';   
    public string $rol_id = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'cui' => ['required', 'digits:13','unique:'.Person::class],
            'gened_id' => ['required', 'digits:1'],
            'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'address' => ['required', 'string', 'max:255'],
            'rol_id' => ['required', 'digits:1'],
            'phone' => ['required', 'digits:8'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
            (DB::select('CALL register_user(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                (int) $validated['cui'],
                $validated['name'],
                $validated['lastname'],
                (int) $validated['gened_id'],
                (int) $validated['phone'],
                $validated['dob'],
                $validated['address'],
                $validated['email'],
                $validated['password'],
                (int) $validated['rol_id'],
            ]));
        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
         // event(new Registered(($user = User::create($validated))));
       // Auth::login($user);
       
    }
}; ?>


<div class="flex dark:bg-gray-900 justify-center items-center p-10">
    <div class=" flex flex-col justify-center text-center ">
        <div class=" flex justify-center items-center">
            <img class="w-40" src="img/logotipo.png" alt="">
        </div>
        <h2 class="text-4xl font-bold text-white">Cementerio General de Quetzaltenango</h2>
        <p class="max-w-xl mt-3 text-white text-justify">
            Bienvenido al sistema de gestión digital del Cementerio General de Quetzaltenango.
            Esta plataforma moderniza la administración de nichos municipales, contratos, pagos y exhumaciones,
            garantizando un manejo eficiente, seguro y transparente de la información.
        </p>
    </div>
    <div class="flex flex-col gap-6  w-1/2 mx-auto">
        <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingrese sus datos a continuación para crear su cuenta')" />
        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />
        <form wire:submit="register" class="flex flex-col gap-6">
            <div class="flex gap-4">
                <!-- Name -->
                <div class="w-full">
                    <flux:input wire:model="name" :label="__('Nombre')" type="text" required autofocus
                        autocomplete="name" :placeholder="__('Nombre completo')" />
                </div>

                <!-- LastName -->
                <div class="w-full">
                    <flux:input wire:model="lastname" :label="__('Apellido')" type="text" required autofocus
                        autocomplete="lastname" :placeholder="__('Apellidos')" />
                </div>
            </div>
            <div class="flex gap-4">
                <!-- CUI -->
                <div class="w-full">
                    <flux:input wire:model="cui" :label="__('DPI')" type="number" required autofocus
                        autocomplete="cui" :placeholder="__('Número de DPI')" />
                </div>
                <!-- Género -->
                <div class="w-full">
                <x-select
    :opciones="$generos" 
    name="genero_id" 
    value="id" 
    text="nombre" 
    label="Género" 
/>
    
                
                <flux:input wire:model="gened_id" :label="__('Género')" type="select" required
                        autocomplete="gened_id" placeholder="Masculino/Femenino" />
                </div>

                <!-- Fecha de nacimiento -->
                <div class="w-full">
                    <flux:input wire:model="dob" :label="__('Fecha de nacimiento')" type="date" required />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Dirección -->
                <div class="w-full">
                    <flux:input wire:model="address" :label="__('Dirección')" type="text" required
                        autocomplete="address" placeholder="Quetzaltenango zona 1" />
                </div>

                <!-- Rol de usuario -->
                <div class="w-full">
                    <flux:input wire:model="rol_id" :label="__('Tipo de usuario')" type="text" required
                        autocomplete="rol_id" :placeholder="__('Tipo de usuario')" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full">
                    <!-- phone number -->
                    <flux:input wire:model="phone" :label="__('Telefono')" type="number" required
                        autocomplete="telefono" placeholder="Numero de telefono" />
                </div>
                <div class="w-full">
                    <!-- Email Address -->
                    <flux:input wire:model="email" :label="__('Email address')" type="email" required
                        autocomplete="email" placeholder="email@example.com" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Password -->
                <div class="w-full">
                    <flux:input wire:model="password" :label="__('Password')" type="password" required
                        autocomplete="new-password" :placeholder="__('Password')" />
                </div>
                <!-- Confirmar Password -->
                <div class="w-full">
                    <flux:input wire:model="password_confirmation" :label="__('Confirmar contraseña')" type="password"
                        required autocomplete="new-password" :placeholder="__('Confirmar contraseña')" />
                </div>
            </div>
            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full bg-green-500">
                    {{ __('Registrar cuenta') }}
                </flux:button>
            </div>
        </form>
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Ya tienes una cuenta de Acceso') }}
            <flux:link :href="route('login')" wire:navigate>{{ __('Inicia sesion') }}</flux:link>
        </div>
    </div>
</div>
