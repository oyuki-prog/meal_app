<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-white shadow-md">
        <h2 class="text-center text-lg font-bold pt-6 tracking-widest">食事記事投稿</h2>

        <x-validation-errors :messages='$errors' />

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
            class="rounded pt-3 pb-8 mb-4">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="title">
                    タイトル
                </label>
                <input type="text" name="title" id="title"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    required placeholder="タイトル" value="{{ old('title', $post->title) }}">
            </div>
            <div class="mb-4">
                <p class="block text-gray-700 text-sm mb-2">カテゴリー</p>
                @foreach ($categories as $category)
                    <label class="block"><input class="mr-2" type="radio" name="category_id"
                            value="{{ $category->id }}" @if ($post->category_id == $category->id) checked @endif>{{ $category->name }}</label>
                @endforeach
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="body">
                    詳細
                </label>
                <textarea name="body" rows="10" id="body"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    required>{{ old('body', $post->body) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="image">
                    食事の画像
                </label>
                <img src="{{ $post->image_url }}" alt="" class="mb-4 w-2/5">
                <input type="file" name="image" class="border-gray-300 w-full">
            </div>
            <x-action-button type="submit" color="blue" text="更新" class="w-full" />
        </form>
    </div>
</x-app-layout>
