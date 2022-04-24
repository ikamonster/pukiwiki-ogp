<?php
/*
PukiWiki - Yet another WikiWikiWeb clone.
ogp.inc.php, v1.01 2020 M.Taniguchi
License: GPL v3 or (at your option) any later version

OGP metaタグを出力するプラグイン。

【使い方】
#ogp([title][,[description][,image]])

title       … ページのタイトル文字列。省略すると「ページ名 - ウィキ名」で代替される
description … ページの説明文字列。省略すると「ページ名 - ウィキ名」で代替される
image       … ページの代表画像。ページ添付画像ファイル名または画像URL。省略すると定数 PLUGIN_OGP_DEFAULT_IMAGE で代替される

【使用例】
#ogp(サンプルページ,このページはサンプルです。,image.jpg)
#ogp(,このページはサンプルです。,https://example.com/img/ogp.jpg)

【補足】
本プラグインはデフォルトで属性に「og」プリフィクスのついたmetaタグを出力します。
そのため、スキンファイル内の head または html タグ（metaタグの親要素）にOGP名前空間プリフィクス宣言「prefix="og: http://ogp.me/ns#"」属性の追加を推奨します。
ただし、必須ではありません。ogは広く普及しているプリフィクスとして RDFa Initial Context 仕様において宣言の省略が認められているためです。
※Facebook用App-ID（PLUGIN_OGP_FACEBOOK_APPID定数）を設定する場合はFacebook名前空間プリフィクス宣言「fb: http://ogp.me/ns/fb#」が必須です。
*/

/////////////////////////////////////////////////
// OGP出力プラグイン（ogp.inc.php）
if (!defined('PLUGIN_OGP_NS_PREFIX'))        define('PLUGIN_OGP_NS_PREFIX',        ''); // OGP名前空間のプリフィクス（og以外の場合に指定）
if (!defined('PLUGIN_OGP_WITH_DESC'))        define('PLUGIN_OGP_WITH_DESC',         1); // 1：<meta name="description">タグを出力, 0：なし
if (!defined('PLUGIN_OGP_DEFAULT_IMAGE'))    define('PLUGIN_OGP_DEFAULT_IMAGE',    ''); // 既定のOGP画像の絶対URL（例：'https://example.com/img/ogp.jpg'）
if (!defined('PLUGIN_OGP_TWITTER_SITE'))     define('PLUGIN_OGP_TWITTER_SITE',     ''); // Twitterカード用：ウェブサイトの@ユーザー名（例：'@hogeSite'）
if (!defined('PLUGIN_OGP_TWITTER_CREATEOR')) define('PLUGIN_OGP_TWITTER_CREATEOR', ''); // Twitterカード用：コンテンツ作成者の@ユーザー名（例：'@fugaUser'）
if (!defined('PLUGIN_OGP_FACEBOOK_APPID'))   define('PLUGIN_OGP_FACEBOOK_APPID',   ''); // Facebook用：App-ID（例：'123456789000000'）


function plugin_ogp_convert() {
	global	$vars, $page_title, $defaultpage, $head_tags;
	static	$included = false;

	if (!$included && isset($vars['page']) && is_page($vars['page'])) {
		list($title, $desc, $image) = func_get_args();

		$prefix = (PLUGIN_OGP_NS_PREFIX)? PLUGIN_OGP_NS_PREFIX : 'og';
		$page = $vars['page'];
		$isHome = ($page === $defaultpage);
		$url = htmlsc(get_page_uri($page, PKWK_URI_ABSOLUTE));
		$title = htmlsc(($title)? $title : ($isHome ? $page_title : $page . ' - ' . $page_title));
		$desc = htmlsc(($desc)? $desc : $page . ' - ' . $page_title);

		if ($image) {
			if (!is_url($image)) {
				$image = get_script_uri() . '?plugin=attach&refer=' . urlencode($page) . '&openfile=' . urlencode($image);
			}
		} else {
			$image = PLUGIN_OGP_DEFAULT_IMAGE;
		}

		if (PLUGIN_OGP_WITH_DESC) $head_tags[] = '<meta name="description" content="' . $desc . '" itemprop="description"/>';
		$head_tags[] = '<meta property="' . $prefix . ':url" content="' . $url . '"/>';
		$head_tags[] = '<meta property="' . $prefix . ':type" content="' . ($isHome ? 'website' : 'article') . '"/>';
		$head_tags[] = '<meta property="' . $prefix . ':site_name" content="' . htmlsc($page_title) . '"/>';
		$head_tags[] = '<meta property="' . $prefix . ':title" content="' . $title . '"/>';
		$head_tags[] = '<meta property="' . $prefix . ':description" content="' . $desc . '"/>';
		if ($image) $head_tags[] = '<meta property="' . $prefix . ':image" content="' . $image . '"/>';
		if (PLUGIN_OGP_TWITTER_SITE) $head_tags[] = '<meta name="twitter:site" content="' . htmlsc(PLUGIN_OGP_TWITTER_SITE) . '"/>';
		if (PLUGIN_OGP_TWITTER_CREATEOR) $head_tags[] = '<meta name="twitter:creator" content="' . htmlsc(PLUGIN_OGP_TWITTER_CREATEOR) . '"/>';
		if (PLUGIN_OGP_FACEBOOK_APPID) $head_tags[] = '<meta property="fb:app_id" content="' . htmlsc(PLUGIN_OGP_FACEBOOK_APPID) . '"/>';

		$included = true;
	}

	return '';
}
