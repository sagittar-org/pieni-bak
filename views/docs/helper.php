<h2>ヘルパー</h2>
<p>
全てのパッケージの<code>./helpers/common.php</code>ヘルパーがロードされます。<br>
優先度の高いパッケージで関数の上書きが可能です。<br>
</p>
<pre>
<?php echo shell_exec("grep -e '^[[:space:]]//' -e '^[[:space:]]function ' ./vendor/sagittar-org/pieni2/helpers/common.php | sed 's/\t//' 2>&1"); ?>
</pre>
