<h2>ビュー</h2>
<h3>共通</h3>
<h4><code>./views/template.php</code> - load_template()によりロードされる最上位階層のビュー</h4>
<p>
子: <code>./views/header.php</code>, <code>./views/flash.php</code>, <code>load_templage()</code>で指定したビュー, <code>./views/footer.php</code>
</p>
<hr>

<h3>CRUD</h3>
<h4><code>./views/crud/index.php</code> - 一覧</h4>
子: <code>./views/crud/search.php</code>, <code>./views/crud/pagination1.php</code>, <code>./views/crud/card.php</code>, <code>./views/crud/result.php</code>, <code>./views/crud/pagination2.php</code>, <code>./views/crud/add.php</code>, <code>./views/crud/edit.php</code>, <code>./views/crud/delete.php</code>
<hr>

<h4><code>./views/crud/search.php</code> - 検索フォーム</h4>
<hr>

<h4><code>./views/crud/pagination1.php</code> - 一覧件数</h4>
<hr>

<h4><code>./views/crud/card.php</code> - カード形式での一覧</h4>
子: <code>./views/crud/card_image.php</code>, <code>./views/crud/col.php</code>
<hr>

<h4><code>./views/crud/card.php</code> - 画像付カード形式での一覧</h4>
子: <code>./views/crud/col.php</code>
<hr>

<h4><code>./views/crud/result.php</code> - 表形式での一覧</h4>
子: <code>./views/crud/col.php</code>
<hr>

<h4><code>./views/crud/pagination2.php</code> - ページャ</h4>
<hr>

<h4><code>./views/crud/add.php</code> - 追加フォーム</h4>
子: <code>./views/crud/input.php</code>
<hr>

<h4><code>./views/crud/view.php</code> - 詳細</h4>
子: <code>./views/crud/col.php</code>, <code>./views/crud/edit.php</code>, <code>./views/crud/delete.php</code>, <code>./views/crud/index.php</code>
<hr>

<h4><code>./views/crud/edit.php</code> - 編集フォーム</h4>
子: <code>./views/crud/input.php</code>
<hr>

<h4><code>./views/crud/delete.php</code> - 削除フォーム</h4>
<hr>

<h4><code>./views/crud/col.php</code> - カラム</h4>
<hr>

<h4><code>./views/crud/input.php</code> - フォーム要素</h4>
<hr>

<h4><code>./views/crud/download.php</code> - ファイルダウンロード</h4>
<hr>
