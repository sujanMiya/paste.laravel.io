@extends('layouts.app')

@section('content')
<form action="{{ route('unlock', $paste->hash) }}" method="POST">
    @csrf

    <div x-data="{ isOpen: false }" class="h-screen flex overflow-hidden">
        <x-main>
            <div class="h-full font-mono text-sm">
                
            </div>
        </x-main>

        <x-nav>
            <x-nav-item label="Password" type="password" icon="heroicon-o-folder-plus" />
            <x-nav-item label="Save" type="submit" icon="heroicon-o-folder-plus" />
        </x-nav>
    </div>
</form>
@stop