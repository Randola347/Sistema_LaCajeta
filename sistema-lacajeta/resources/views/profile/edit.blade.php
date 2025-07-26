@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-6 px-4 font-sans">
        <h2 class="text-lg font-semibold text-purple-700 mb-4 flex items-center">
            <span class="mr-2">👤</span> Mi Perfil
        </h2>

        {{-- Actualizar correo --}}
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-md font-semibold text-blue-700 mb-2 flex items-center">
                <span class="mr-2">📧</span> Correo electrónico
            </h3>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">

                <button type="submit"
                    class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-blue py-2 px-4 rounded-md transition">
                    Actualizar correo
                </button>
            </form>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-md font-semibold text-blue-700 mb-2 flex items-center">
                <span class="mr-2">🔒</span> Cambiar contraseña
            </h3>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <input type="password" name="current_password" placeholder="Contraseña actual" required
                    class="w-full mb-3 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">

                <input type="password" name="password" placeholder="Nueva contraseña" required
                    class="w-full mb-3 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">

                <input type="password" name="password_confirmation" placeholder="Confirmar nueva contraseña" required
                    class="w-full mb-4 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-blue font-bold py-2 px-4 rounded shadow-md transition duration-200">
                    Actualizar contraseña
                </button>

            </form>
        </div>
    </div>
@endsection
