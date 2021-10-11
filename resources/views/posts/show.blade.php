<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">

        <x-flash-message :message="session('notice')" />

        <x-validation-errors :messages='$errors' />

        <article class="mb-2">
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                {{ $post->title }}</h2>
            <h3>{{ $post->user->name }}</h3>
            <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                記事作成日:
                {{ $post->date_diff }}
            </p>
            <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                記事作成日:
                {{ $post->created_at }}
            </p>
            <img src="{{ $post->image_url }}" alt="" class="mb-4 mx-auto">
            <p class="text-gray-700 text-base break-words">{!! nl2br(e($post->body)) !!}</p>
            @auth
                @if (!empty($like))
                    <form action="{{ route('posts.likes.destroy', [$post, $like]) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <x-action-button type="submit" color="pink" text="お気に入り削除" />
                    </form>
                @else
                    <form action="{{ route('posts.likes.store', $post) }}" method="POST" class="mt-2">
                        @csrf
                        {{-- <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="post_id" value="{{ $post->id }}"> --}}
                        <x-action-button type="submit" color="blue" text="お気に入り" />
                    </form>
                @endif
            @endauth
            <p class="font-bold">お気に入り数：{{ $post->likes->count() }}</p>
            <div class="flex flex-row text-center my-4">
                @can('update', $post)
                    <x-action-button type="button" onclick="location.href='{{ route('posts.edit', $post) }}'"
                        color="green" text="編集" class="w-20" />
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <x-action-button type="submit" onclick="if(!confirm('本当に削除しますか？')){return false}" color="red"
                            text="削除" class="w-20" />
                    </form>
                @endcan
            </div>
        </article>
    </div>
</x-app-layout>
