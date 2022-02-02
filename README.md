# PukiWiki用プラグイン<br>OGPメタタグ出力 ogp.inc.php

OGPメタタグを出力する[PukiWiki](https://pukiwiki.osdn.jp/)用プラグイン。  

|対象PukiWikiバージョン|対象PHPバージョン|
|:---:|:---:|
|PukiWiki 1.5.3 ~ 1.5.4RC (UTF-8)|PHP 7.4 ~ 8.1|

## インストール

下記GitHubページからダウンロードした ogp.inc.php を PukiWiki の plugin ディレクトリに配置してください。

[https://github.com/ikamonster/pukiwiki-ogp](https://github.com/ikamonster/pukiwiki-ogp)

## 使い方

```
#ogp([title][,[description][,image]])
```

* title … ページのタイトル文字列。省略すると「ページ名 - ウィキ名」で代替される
* description … ページの説明文字列。省略すると「ページ名 - ウィキ名」で代替される
* image … ページの代表画像。ページ添付画像ファイル名または画像URL。省略すると定数 PLUGIN_OGP_DEFAULT_IMAGE で代替される

## 使用例

```
#ogp(サンプルページ,このページはサンプルです。,image.jpg)
```

## 設定

ソース内の下記の定数で動作を制御することができます。

|定数名|値|既定値|意味|
|:---|:---:|:---:|:---|
|PLUGIN_OGP_NS_PREFIX|文字列||OGP名前空間のプリフィクス（og 以外の場合に指定）|
|PLUGIN_OGP_WITH_DESC|0 or 1|1|1：<meta name="description"> タグを出力, 0：なし|
|PLUGIN_OGP_DEFAULT_IMAGE|文字列||既定のOGP画像の絶対URL（例：'[HTTPS:]//example.com/img/ogp.jpg'）|
|PLUGIN_OGP_TWITTER_SITE|文字列||Twitterカード用：ウェブサイトの@ユーザー名（例：'@hogeSite'）|
|PLUGIN_OGP_TWITTER_CREATEOR|文字列||Twitterカード用：コンテンツ作成者の@ユーザー名（例：'@fugaUser'）|
|PLUGIN_OGP_FACEBOOK_APPID|文字列||Facebook用：App-ID（例：'123456789000000'）|

## 補足

本プラグインはデフォルトで属性に「og」プリフィクスのついた meta タグを出力します。  
そのため、スキンファイル内の head または html タグ（つまり meta タグの親要素）にOGP名前空間プリフィクス宣言「``prefix="og: http://ogp.me/ns#"``」を加えることを推奨します。  
ただし、必須ではありません（RDFa Initial Context 仕様において、og は広く普及しているプリフィクスのひとつとして宣言の省略が認められている）。

Facebook用App-ID（PLUGIN_OGP_FACEBOOK_APPID定数）を設定する場合は、Facebook名前空間プリフィクス宣言「``prefix="fb: http://ogp.me/ns/fb#"``」が必須となります。
