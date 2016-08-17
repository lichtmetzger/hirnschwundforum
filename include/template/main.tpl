<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<pun_language>" lang="<pun_language>" dir="<pun_content_direction>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
<link rel="stylesheet" href="/css/menu.css" type="text/css">
<link rel="stylesheet" href="/css/pure-min.css">
<pun_head>
	<!-- markitup! bbcode editor -->
	<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
	<script type="text/javascript" src="markitup/sets/bbcode/set.js"></script>
	<link rel="stylesheet" type="text/css" href="markitup/skins/simple/style.css" />
	<link rel="stylesheet" type="text/css" href="markitup/sets/bbcode/style.css" />
	<script type="text/javascript" >
		$(document).ready(function() {
			$("textarea").markItUp(bbcodeSettings);
		});
	</script>
	<!-- /markitup! bbcode editor -->
</head>

<body>

<style>
.custom-menu-wrapper {
    background-color: #990000;
    white-space: nowrap;
    position: relative;
    border-bottom: 5px solid #fff;
    font-weight: bold;
    text-shadow: 1px 1px 3px rgba(37, 37, 37, 1);
}

.custom-menu {
    display: inline-block;
    width: auto;
    vertical-align: middle;
    -webkit-font-smoothing: antialiased;
}

.custom-menu .pure-menu-link,
.custom-menu .pure-menu-heading {
    color: white;
}

.custom-menu .pure-menu-link:hover,
.custom-menu .pure-menu-heading:hover {
    background-color: transparent;
}

.custom-menu-top {
    position: relative;
    padding-top: .5em;
    padding-bottom: .5em;
}

.custom-menu-brand {
    display: block;
    text-align: center;
    position: relative;
}

.custom-menu-toggle {
    width: 44px;
    height: 44px;
    display: block;
    position: absolute;
    top: 3px;
    right: 0;
    display: none;
}

.custom-menu-toggle .bar {
    background-color: white;
    display: block;
    width: 20px;
    height: 2px;
    border-radius: 100px;
    position: absolute;
    top: 22px;
    right: 12px;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    -ms-transition: all 0.5s;
    transition: all 0.5s;
}

.custom-menu-toggle .bar:first-child {
    -webkit-transform: translateY(-6px);
    -moz-transform: translateY(-6px);
    -ms-transform: translateY(-6px);
    transform: translateY(-6px);
}

.custom-menu-toggle.x .bar {
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.custom-menu-toggle.x .bar:first-child {
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    transform: rotate(-45deg);
}

.custom-menu-screen {
    background-color: rgba(0, 0, 0, 0.5);
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    -ms-transition: all 0.5s;
    transition: all 0.5s;
    height: 3em;
    width: 70em;
    position: absolute;
    top: 0;
    z-index: -1;
}

.custom-menu-tucked .custom-menu-screen {
    -webkit-transform: translateY(-44px);
    -moz-transform: translateY(-44px);
    -ms-transform: translateY(-44px);
    transform: translateY(-44px);
}

@media (max-width: 62em) {

    .custom-menu {
        display: block;
    }

    .custom-menu-toggle {
        display: block;
        display: none\9;
    }

    .custom-menu-bottom {
        position: absolute;
        width: 100%;
        border-top: 1px solid #eee;
        background-color: #808080\9;
        z-index: 100;
    }

    .custom-menu-bottom .pure-menu-link {
        opacity: 1;
        -webkit-transform: translateX(0);
        -moz-transform: translateX(0);
        -ms-transform: translateX(0);
        transform: translateX(0);
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        -ms-transition: all 0.5s;
        transition: all 0.5s;
    }

    .custom-menu-bottom.custom-menu-tucked .pure-menu-link {
        -webkit-transform: translateX(-140px);
        -moz-transform: translateX(-140px);
        -ms-transform: translateX(-140px);
        transform: translateX(-140px);
        opacity: 0;
        opacity: 1\9;
    }

    .pure-menu-horizontal.custom-menu-tucked {
        z-index: -1;
        top: 45px;
        position: absolute;
        overflow: hidden;
    }

}
</style>
<div class="custom-menu-wrapper">
    <div class="pure-menu custom-menu custom-menu-top">
        <a href="https://hirnschwund.net" class="pure-menu-heading custom-menu-brand">massengeschmack.net</a>
        <a href="#" class="custom-menu-toggle" id="toggle"><s class="bar"></s><s class="bar"></s></a>
    </div>
    <div class="pure-menu pure-menu-horizontal pure-menu-scrollable custom-menu custom-menu-bottom custom-menu-tucked" id="tuckedMenu">
        <div class="custom-menu-screen"></div>
        <ul class="pure-menu-list">
            <li class="pure-menu-item"><a href="/status.php" class="pure-menu-link">Status</a></li>
            <li class="pure-menu-item"><a href="/anleitungen.php" class="pure-menu-link">Anleitungen</a></li>
            <li class="pure-menu-item"><a href="/forum" class="pure-menu-link">Forum</a></li>
            <li class="pure-menu-item"><a href="https://hirnschwund.net/gameserver/" class="pure-menu-link">Gameserver</a></li>
            <li class="pure-menu-item"><a href="/impressum.php" class="pure-menu-link">Impressum</a></li>
        </ul>
    </div>
</div>
<script>
(function (window, document) {
document.getElementById('toggle').addEventListener('click', function (e) {
    document.getElementById('tuckedMenu').classList.toggle('custom-menu-tucked');
    document.getElementById('toggle').classList.toggle('x');
});
})(this, this.document);
</script>

<div id="pun<pun_page>" class="pun">
<div class="top-box"></div>
<div class="punwrap">

<div id="brdheader" class="block">
	<div class="box">
		<div id="brdtitle" class="inbox">
			<pun_title>
			<pun_desc>
		</div>
		<pun_navlinks>
		<pun_status>
	</div>
</div>

<pun_announcement>

<div id="brdmain">
<pun_main>
</div>

<pun_footer>

</div>
<div class="end-box"></div>
</div>

</body>
</html>
