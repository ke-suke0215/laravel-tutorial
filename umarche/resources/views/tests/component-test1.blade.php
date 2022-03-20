<x-tests.app>
  <x-slot name="header">
    ヘッダー1
  </x-slot>
  コンポーネントテスト1

  <x-tests.card title="タイトル1" content="コンテンツ" :message="$message"/>
  <x-tests.card title="タイトル2" />
  <!-- タイトル3はCSSを変更したい -->
  <x-tests.card title="タイトル3" class="bg-red-300" />

</x-tests.app>
