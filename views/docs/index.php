<h3>pieni2について</h3>
<p>
pieni2はLAMP環境で動作するPHPフレームワークです。<br>
CodeIgniterの影響を大きく受けています。<br>
CRUDと認証機能が含まれます。<br>
強力なモデルによりコントローラやビューを作成することなくプロトタイピングを行うことができます。<br>
コントローラ、モデル、ライブラリ、ビュー、言語ファイルなど、ほとんどのファイルはフォールバックによって、然るべきパスからロードされます。<br>
これはあらゆるファイルをあなたのパッケージに含めることができ、また置換が可能ということを意味します。<br>
</p>

<h3>インストール</h3>
<p>
pieni2は<code><a href="<?php href('docs/file/.'); ?>">vendor/sagittar-org/pieni2</a></code>へ展開されます。<br>
下記の様に<code>./index.php</code>と<code>./.htaccess</code>をコピーし、データベース<code>pieni2</code>を作成してください。</br>
サンプルアプリケーションが動作します。<br>
</p>
<pre>
cp ./vendor/sagittar-org/pieni2/index.php .
cp ./vendor/sagittar-org/pieni2/.htaccess .
mysql < ./vendor/sagittar-org/pieni2/misc/pieni2.dump
</pre>

<h3>リクエスト</h3>
<p>
<code>./.htaccess</code>での指定から<code>./direct</code>から始まらない全てのURIは<code>./index.php</code>によって処理されます。<br>
</p>

<h3>パッケージ</h3>
<p>
ロードの検索バスに含ませるパッケージは<code>./index.php</code>の<code>package_list</code>で指示します。<br>
</p>

<h3>URIセグメント</h3>
<p>
セグメント情報は<code>uri()</code>で参照します。<br>
第1から第4セグメントは、言語、アクター、クラス、メソッドに対応します。<br>
言語、アクター、クラスが取り得る値は<code>./config.php</code>から配列で指示されます。<br>
0番目の要素がデフォルト値で、これらは省略されなければいけません。<br>
クラスが<code>./config.php</code>で指示される<code>table_list</code>に含まれる場合、第5から第7セグメントは、ID、エイリアス、親IDに対応します。<br>
上記以降のセグメントはクラスメソッドの引数に渡されます。<br>
URI<code>docs/index/a/b/c</code>での例を示します。<br>
</p>
<pre>
Array
(
    [uri_string] => docs/index/a/b/c
    [param_arr] => Array
        (
            [0] => a
            [1] => b
            [2] => c
        )

    [language] => ja
    [actor] => g
    [class] => docs
    [method] => index
)
</pre>

<h3>コントローラのロード</h3>
<p>
検索バスから<code>uri('class')</code>に対応するコントローラをロードします。<br>
<code>uri('class')</code>が<code>table_list</code>に含まれる場合、コントローラ<code>Crud</code>も検索パスに含まれます。<br>
これは独自のビジネスロジックがない場合、コントローラが不要であることを意味します。<br>
</p>

<h3>モデルのロード</h3>
<p>
コントローラが<code>Crud</code>を継承する場合、<code>uri('class')</code>に対応するモデルがロードされます。<br>
</p>

<h3>クラスメソッドの実行</h3>
<p>
コントローラの<code>uri('method')</code>に対応するメソッドを実行します。<br>
</p>

<h3>ビューのロード</h3>
<p>
Ajaxではないリクエストでは、多くの場合クラスメソッドからビューをロードします。<br>
ビューからも他のビューをネストしてロードできます。<br>
ビューをカスタマイズする場合は、あなたのパッケージバスにビューファイルを設置します。<br>
pieni2が提供するビューは最終フォールバック先であり編集も削除も必要ありません。<br>
</p>
