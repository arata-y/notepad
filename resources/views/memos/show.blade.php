<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font relative">
                      <div class="container px-5 py-24 mx-auto">
                        <div class="flex flex-col text-center w-full">
                          <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">詳細</h1>
                        </div>
                        <div class="lg:w-1/2 md:w-2/3 mx-auto">
                          <div class="flex flex-wrap -m-2">
                            <div class="p-2 w-full">
                              <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600">タイトル</label>
                                <div id="name" name="name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$memo->name}}</div>
                              </div>
                            </div>
                            <div class="p-2 w-full">
                              <div class="relative">
                                <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                <div id="image" name="new_image[]" class="imageForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block">{{$memo->image}}</div>
                              </div>
                            </div>
                            <div class="p-2 w-full">
                              <div class="relative">
                                <label for="tag" class="leading-7 text-sm text-gray-600">タグ</label>
                                <div id="new_tag" name="new_tag[]" class="tagForm w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out block">
                                  @foreach($tags as $t)
                                    #{{$t['name']}}
                                  @endforeach
                                </div>
                              </div>
                            </div>
                            <div class="p-2 w-full">
                              <div class="relative">
                                <label for="message" class="leading-7 text-sm text-gray-600">メモ</label>
                                <div id="message" name="content" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 leading-6 transition-colors duration-200 ease-in-out">{{$memo->content}}</div>
                              </div>
                            </div>
                            <div class="p-2 w-full">
                              <form method="get" action="{{route('memos.edit',$memo->id)}}">
                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
