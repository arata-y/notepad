<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            メモ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach ($memos as $m)
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-2xl"><a href="{{ route('memos.show',$m->id)}}">{{$m['name']}}</a></div>
                        <div class="text-sm">{{$m['content']}}</div>
                        <div class="text-sm">{{$m['updated_at']}}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
