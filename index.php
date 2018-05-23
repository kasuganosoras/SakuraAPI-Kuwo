<?php
if($_GET['id']) {
	$string = file_get_contents('http://player.kuwo.cn/webmusic/st/getNewMuiseByRid?rid=MUSIC_'.$_GET["id"]);
	preg_match_all( "/\<mp3path\>(.*?)\<\/mp3path\>/", $string, $path);
	preg_match_all( "/\<mp3dl\>(.*?)\<\/mp3dl\>/", $string, $dl);
	preg_match_all( "/\<name\>(.*?)\<\/name\>/", $string, $name);
	preg_match_all( "/\<singer\>(.*?)\<\/singer\>/", $string, $singer);
	preg_match_all( "/\<mp3size\>(.*?)\<\/mp3size\>/", $string, $size);
	preg_match_all( "/\<artist_pic240\>(.*?)\<\/artist_pic240\>/", $string, $logo);
	preg_match_all( "/\<music_id\>(.*?)\<\/music_id\>/", $string, $id);
	$mp3path = $path[1][0];
	$mp3dl = $dl[1][0];
	$mp3name = $name[1][0];
	$mp3singer = $singer[1][0];
	$mp3size = $size[1][0];
	$mp3logo = $logo[1][0];
	$mp3id = $id[1][0];
	$musicurl = "http://" . $mp3dl . "/resource/" . $mp3path;
	if($mp3dl == "" || $mp3path == "") {
		$array = Array(
			'id' => $mp3id,
			'error' => 'Music not found',
		);
		$musicurl = "Music not found";
	} else {
		$array = Array(
			'id' => $mp3id,
			'name' => $mp3name,
			'singer' => $mp3singer,
			'size' => $mp3size,
			'logo' => $mp3logo,
			'url' => "http://" . $mp3dl . "/resource/" . $mp3path
		);
	}
	switch($_GET['type']) {
		case 'text':
			Header("Content-type: text/plain;charset=utf-8");
			echo $musicurl;
			break;
		case 'json':
			Header("Content-type: application/json;charset=utf-8");
			echo json_encode($array);
			break;
		case 'xml':
			Header("Content-type: text/xml;charset=utf-8");
			echo xml_encode($array);
			break;
		default:
			Header("Content-type: text/plain;charset=utf-8");
			echo $musicurl;
	}
	exit;
} else {
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SakuraMC Kuwo Music API</title>
	<link href="https://api.sakuramc.org/css/font-awesome.min.css" rel="stylesheet">
	<style type="text/css">
		body {
			background: #F1F1F1;
			font-size: 14px;
			font-family: "Open Sans", sans-serif;
		}
		big {
			font-size: 32px;
		}
		a {
			color: #333333;
			text-decoration: none;
		}
		a:hover {
			color: #FF56AD;
		}
		code {
			padding: 8px;
			border-radius: 6px;
			margin-top: 4px;
			margin-bottom: 4px;
			display: inline-block;
			background: rgba(255,0,0,0.05);
			font-family: Menlo,Monaco,Consolas,"Courier New",monospace;
			color: #BF0000;
		}
		::selection {
			background: rgba(255,134,184,0.8);
			color: #FFF;
		}
		::-moz-selection {
			background: rgba(255,134,184,0.8);
			color: #FFF;
		}
		::-webkit-selection {
			background: rgba(255,134,184,0.8);
			color: #FFF;
		}
	</style>
</head>
<body>
	<big><i class="fa fa-paper-plane"></i> SakuraMC API</big>
	<p>欢迎使用由 SakuraMC 提供的前台公共 API 服务接口</p>
	<p>本平台提供多种常用的 API 免费提供调用，支持 AJAX / HTTPS</p>
	<p>我们长期提供免费的服务，服务器压力很大，如果您希望帮助我们，欢迎通过微信 / 支付宝赞助</p>
	<p>微信：<a href="http://natfrp.org/images/wechat.png" target="_blank">[ 二维码 ]</a>&nbsp;&nbsp;支付宝：<a href="http://natfrp.org/images/alipay.jpg" target="_blank">[ 二维码 ]</a></p>
	<p>
		<i class="fa fa-home"></i>
		<i class="fa fa-angle-right"></i>
		<a href="/">主页</a>
		<i class="fa fa-angle-right"></i> 酷我音乐信息获取 API
	</p>
	<hr>
	<p>接口调用方法：</p>
	<p><code>GET /?id=&lt;Music Id&gt;&amp;type=&lt;Return Type&gt;</code></p>
	<ul>
		<li><b>Music Id：</b>音乐 Id，即 URL 中 /yinyue/ 后面的数字</li>
		<li><b>Return Type：</b>返回数据类型，目前仅支持 json / xml / text</li>
	</ul>
	<p>返回数据：</p>
	<ol>
		<li><b>Json 模式：</b>返回 Json 格式数据，如果音乐不存在，则返回 error </li>
		<li><b>Xml 模式：</b>返回 Xml 格式数据，如果音乐不存在，则返回 music <i class="fa fa-angle-right"></i> error</li>
		<li><b>Text 模式：</b>返回音乐的外链 Url，如果音乐不存在，则返回 Music not found</li>
	</ol>
	<p>示例请求：</p>
	<p><code>https://api.sakuramc.org/kuwo/?id=14453166&type=json</code></p>
	<hr>
	<p><i class="fa fa-server"></i> <em>Powered by SakuraOS/11.3.1 ( CentOS 7.5 )</em></p>
</body>
</html>
<?php
	exit;
}

function xml_encode($data, $encoding = 'utf-8', $root = 'music') {
    $xml    = '<?xml version="1.0" encoding="' . $encoding . '"?>';
    $xml   .= '<' . $root . '>';
    $xml   .= data_to_xml($data);
    $xml   .= '</' . $root . '>';
    return $xml;
}

function data_to_xml($data) {
    $xml = '';
    foreach ($data as $key => $val) {
        is_numeric($key) && $key = "item id=\"$key\"";
        $xml    .=  "<$key>";
        $xml    .=  ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
        list($key, ) = explode(' ', $key);
        $xml    .=  "</$key>";
    }
    return $xml;
}
