@extends('layouts.app')

@section('content')
<form action="{{ route('unlock', $paste->hash) }}" method="POST">
    @csrf

    <div x-data="{ isOpen: false }" class="h-screen flex overflow-hidden">
        <x-main>
            <div class="h-full font-mono text-lg">
                <h1 class="text-3xl md:text-4xl font-bold text-white text-balance text-center typewriter">
                    This content is password protected. Please enter the password.
                </h1>
            </div>
    </x-main>

    <x-nav>
        <x-nav-item label="Password" type="password" icon="heroicon-o-folder-plus" />
        <x-nav-item label="Save" type="submit" icon="heroicon-o-folder-plus" />
    </x-nav>
    </div>
</form>
@stop