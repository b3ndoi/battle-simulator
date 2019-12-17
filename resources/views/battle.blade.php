@extends('layout.app')

@section('content')
    <div class="py-4">
        <h1 class="font-semibold text-4xl  text-center">Battle Simulator</h1>
    </div>
    <div class="mx-32 bg-white rounded-lg shadow-lg p-10 flex">
        
        <div class="w-1/2">
            <h2 class="text-2xl">Armies</h2>
            <div class="flex">
                <add-army game_id="{{$game->id}}"></add-army>
                <army-list game_id="{{$game->id}}"></army-list>
            </div>
        </div>
        <battle-log game_id="{{$game->id}}"></battle-log>
    </div>
@endsection