<x-tests.app>
  <x-slot name="header">
    ヘッダー1
  </x-slot>
  コンポーネントテスト1

  <x-tests.card title="タイトル" content="コンテンツ" :message="$message"/>
  <x-tests.card title="タイトル" />

</x-tests.app>
