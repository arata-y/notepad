<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            メモ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="get" action="{{ route('memos.index')}}">
                <input type="text" name="search" placeholder="検索">
                <button class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">検索</button>
            </form>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @for  ($i = 0; $i < count($memos); $i++)
                <form method="post" action="{{ route('memos.destroy',$memos[$i]->id)}}">
                    @csrf
                    @method('DELETE')
                    <button class="text-white bg-indigo-500 border-0 px-1 focus:outline-none hover:bg-indigo-600 rounded block float-left my-10 opacity-0 delete">×</button>
                </form>
                    <div class="p-6 bg-white border-b border-gray-200 memos">
                        @if (isset($images[$i][0]->path))
                        <img src="{{ asset($images[$i][0]->path) }}" class="float-right bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out w-20 h-16 block">
                        @endif
                        <a href="{{ route('memos.show',$memos[$i]['id'])}}" class="text-2xl block w-96">{{$memos[$i]['name']}}</a>
                        <span class="text-sm">{{$dates[$i]}}</span>
                        <span class="text-sm">{{$memos[$i]['content']}}</span>
                    </div>
                @endfor
            </div>
            {{$memos->links()}}
        </div>
    </div>
    <script src="{{ asset('js/memo-index.js') }}"></script>
</x-app-layout>
