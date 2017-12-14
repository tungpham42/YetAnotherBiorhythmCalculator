<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('max_execution_time', 0);
set_time_limit(0);
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/ip/geoipcity.inc.php';
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/ip/timezone.php';
$geoip = geoip_open(realpath($_SERVER['DOCUMENT_ROOT']).'/includes/ip/GeoIPCity.dat',GEOIP_STANDARD);
$geoip_record = geoip_record_by_addr($geoip,$_SERVER['REMOTE_ADDR']);
$lang_codes = array('vi','en','ru','es','zh','ja');
$lang_fb_apis = array(
	'vi' => 'vi_VN',
	'en' => 'en_US',
	'ru' => 'ru_RU',
	'es' => 'es_ES',
	'zh' => 'zh_CN',
	'ja' => 'ja_JP'
);
$lang_g_apis = array(
	'vi' => 'vi',
	'en' => 'en-US',
	'ru' => 'ru',
	'es' => 'es',
	'zh' => 'zh-CN',
	'ja' => 'ja'
);
$lang_cse_apis = array(
	'vi' => 'lang_vi',
	'en' => 'lang_en',
	'ru' => 'lang_ru',
	'es' => 'lang_es',
	'zh' => 'lang_zh-CN',
	'ja' => 'lang_ja'
);
$navs = array('member/home','member/login','member/register','intro','bmi','lunar','donate','2048','race','race/1','race/2','race/3','co','fish','proverbs','contact','author');
$adsense_navs = array('member/login','member/register','intro','bmi','lunar','donate','2048','race','race/1','race/2','race/3','co','fish','proverbs','contact');
$first_domain = 'nhipsinhhoc.vn';
$second_domain = 'biorhythm.xyz';
require realpath($_SERVER['DOCUMENT_ROOT']).'/includes/prep.inc.php';
$brand = 'Nhip Sinh Hoc . VN';
$p = isset($_GET['p']) ? prevent_xss($_GET['p']): 'home';
$q = isset($_GET['q']) ? prevent_xss($_GET['q']): "";
$dob = isset($_GET['dob']) ? prevent_xss($_GET['dob']): (isset($_COOKIE['NSH:remembered_dob']) ? $_COOKIE['NSH:remembered_dob']: "");
$fullname = isset($_GET['fullname']) ? prevent_xss($_GET['fullname']): (isset($_COOKIE['NSH:remembered_fullname']) ? $_COOKIE['NSH:remembered_fullname']: "");
$embed = isset($_GET['embed']) ? prevent_xss($_GET['embed']): 0;
$lang_code = init_lang_code();
$time_zone = 7;
$show_ad = false;
$show_donate = false;
$show_sponsor = false;
$show_addthis = false;
$show_sumome = false;
$hotjar = false;
$clicktale = false;
$smartlook = true;
$credential_id = 3; //change this to 4 in DEMO
//$cdn_url = 'http://nhipsinhhoc.cdn.vccloud.vn';
$cdn_url = "";
$number = calculate_life_path($dob);
if (isset($_GET['dob']) && isset($_GET['diff']) && isset($_GET['is_secondary']) && isset($_GET['dt_change']) && isset($_GET['partner_dob']) && isset($_GET['lang_code'])) {
	$chart = new Chart($_GET['dob'],$_GET['diff'],$_GET['is_secondary'],$_GET['dt_change'],$_GET['partner_dob'],$_GET['lang_code']);
} else {
	$date = (isset($_GET['date']) && $_GET['date'] != "") ? $_GET['date']: date('Y-m-d');
	$chart = new Chart($dob,0,0,$date,$dob,$lang_code);
}
if (isset($_GET['ad'])) {
	setcookie('NSH:show_ad',$_GET['ad']);
}
if (isset($_COOKIE['NSH:member'])) {
//	$show_ad = false;
	$chart->set_registered(true);
} else if (!isset($_COOKIE['NSH:member'])) {
//	$show_ad = true;
	$chart->set_registered(false);
}
$email_credentials = array(
	'username' => 'admin@nhipsinhhoc.vn',
	'password' => '@DM!Nv0d0i'
);
$faroo_key = 'kc5BZXhbMCj0@lx0TEVOiHNvSok_';
$clickbank = '<a href="http://tungpham42.15manifest.hop.clickbank.net"><img src="http://maxcdn.15minutemanifestation.com/affiliates/images/300x250.jpg"></a>';
$input_interfaces = array(
	'search' => array(
		'vi' => 'Tìm kiếm',
		'en' => 'Search',
		'ru' => 'Искать',
		'es' => 'Buscar',
		'zh' => '寻求',
		'ja' => '探す'
	),
	'fullname' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'dob' => array(
		'vi' => 'Ngày sinh',
		'en' => 'Date of birth',
		'ru' => 'Дата рождения',
		'es' => 'Fecha de nacimiento',
		'zh' => '出生日期',
		'ja' => '生まれた日'
	),
	'dt_change' => array(
		'vi' => 'Đổi ngày',
		'en' => 'Change date',
		'ru' => 'Изменение даты',
		'es' => 'Cambiar fecha',
		'zh' => '更改日期',
		'ja' => '日付の変更'
	),
	'partner_dob' => array(
		'vi' => 'Đối tác',
		'en' => 'Partner',
		'ru' => 'Напарник',
		'es' => 'Compañero',
		'zh' => '伙伴',
		'ja' => 'パートナー'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'password' => array(
		'vi' => 'Mật khẩu',
		'en' => 'Password',
		'ru' => 'Пароль',
		'es' => 'Contraseña',
		'zh' => '密码',
		'ja' => 'パスワード'
	),
	'repeat_password' => array(
		'vi' => 'Lặp lại',
		'en' => 'Repeat',
		'ru' => 'Повторять',
		'es' => 'Repetición',
		'zh' => '重复',
		'ja' => 'リピート'
	)
);
$button_interfaces = array(
	'name_toggle' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'home_page' => array(
		'vi' => 'Trang chủ',
		'en' => 'Home',
		'ru' => 'Дом',
		'es' => 'Casa',
		'zh' => '主页',
		'ja' => 'ホーム'
	),
	'dob_erase' => array(
		'vi' => 'Xóa',
		'en' => 'Erase',
		'ru' => 'Стирать',
		'es' => 'Borrar',
		'zh' => '抹去',
		'ja' => '消す'
	),
	'dob_submit' => array(
		'vi' => 'Chạy',
		'en' => 'Run',
		'ru' => 'Идти',
		'es' => 'Correr',
		'zh' => '运行',
		'ja' => '走る'
	),
	'dob_list' => array(
		'vi' => 'Danh sách',
		'en' => 'List',
		'ru' => 'Список',
		'es' => 'Lista',
		'zh' => '名单',
		'ja' => 'リスト'
	),
	'dob_create' => array(
		'vi' => 'Tạo',
		'en' => 'Create',
		'ru' => 'Создать',
		'es' => 'Crear',
		'zh' => '创建',
		'ja' => '作る'
	),
	'dob_edit' => array(
		'vi' => 'Sửa',
		'en' => 'Edit',
		'ru' => 'Редактировать',
		'es' => 'Editar',
		'zh' => '编辑',
		'ja' => '編集'
	),
	'dob_remove' => array(
		'vi' => 'Xóa hẳn',
		'en' => 'Remove',
		'ru' => 'Удалить',
		'es' => 'Quitar',
		'zh' => '拆除',
		'ja' => '削除します'
	),
	'today' => array(
		'vi' => ' Hôm nay',
		'en' => ' Today',
		'ru' => ' Сегодня',
		'es' => ' Hoy',
		'zh' => ' 今天',
		'ja' => ' 今日'
	),
	'prev' => array(
		'vi' => ' Trước',
		'en' => ' Back',
		'ru' => ' Назад',
		'es' => ' Atrás',
		'zh' => ' 回去',
		'ja' => ' 戻る'
	),
	'next' => array(
		'vi' => 'Sau ',
		'en' => 'Forward ',
		'ru' => 'Вперед ',
		'es' => 'Enviar ',
		'zh' => '前进 ',
		'ja' => '前進する '
	),
	'intro' => array(
		'vi' => 'Giới thiệu',
		'en' => 'Introduction',
		'ru' => 'Введение',
		'es' => 'Introducción',
		'zh' => '介绍',
		'ja' => 'はじめに'
	),
	'blog' => array(
		'vi' => 'Blog',
		'en' => 'Blog',
		'ru' => 'блог',
		'es' => 'Blog',
		'zh' => '博客',
		'ja' => 'ブログ'
	),
	'forum' => array(
		'vi' => 'Diễn đàn',
		'en' => 'Forum',
		'ru' => 'Форум',
		'es' => 'Forum',
		'zh' => '论坛',
		'ja' => 'フォーラム'
	),
	'bmi' => array(
		'vi' => 'BMI',
		'en' => 'BMI',
		'ru' => 'ИМТ',
		'es' => 'IMC',
		'zh' => '身高體重指數',
		'ja' => 'ボディマス指数'
	),
	'lunar' => array(
		'vi' => 'Bói',
		'en' => 'Lunar calendar',
		'ru' => 'Лунный календарь',
		'es' => 'Calendario lunar',
		'zh' => '阴历',
		'ja' => '太陰暦'
	),
	'game' => array(
		'vi' => 'Game',
		'en' => 'Game',
		'ru' => 'Игра',
		'es' => 'Juego',
		'zh' => '游戏',
		'ja' => 'ゲーム'
	),
	'survey' => array(
		'vi' => 'Góp ý',
		'en' => 'Survey',
		'ru' => 'Обзор',
		'es' => 'Estudio',
		'zh' => '调查',
		'ja' => '調査'
	),
	'apps' => array(
		'vi' => 'App',
		'en' => 'Applications',
		'ru' => 'Приложений',
		'es' => 'Aplicaciones',
		'zh' => '应用',
		'ja' => 'アプリ'
	),
	'donate' => array(
		'vi' => 'ĐÓNG GÓP',
		'en' => 'DONATE',
		'ru' => 'ДАРИТЬ',
		'es' => 'DONAR',
		'zh' => '捐赠',
		'ja' => '寄付する'
	),
	'donate_reason' => array(
		'vi' => 'nếu bạn thấy trang có ích',
		'en' => 'if you find it useful',
		'ru' => 'коли вас считать это полезным',
		'es' => 'si usted lo encuentra útil',
		'zh' => '如果您发现它有用',
		'ja' => 'あなたはそれが役立つかどう'
	),
	'sponsor' => array(
		'vi' => 'TÀI TRỢ',
		'en' => 'SPONSOR',
		'ru' => 'СПОНСОР',
		'es' => 'PATROCINIO',
		'zh' => '贊助',
		'ja' => 'スポンサー'
	),
	'sponsor_reason' => array(
		'vi' => 'để đặt quảng cáo',
		'en' => 'to put banner',
		'ru' => 'положить баннер',
		'es' => 'poner bandera',
		'zh' => '把旗帜',
		'ja' => 'バナーを置くために'
	),
	'install_chrome' => array(
		'vi' => 'Thêm vào Chrome',
		'en' => 'Add to Chrome',
		'ru' => 'Добавить в Chrome',
		'es' => 'Añadir a Chrome',
		'zh' => '添加到Chrome浏览器',
		'ja' => 'クロームに追加'
	),
	'install_firefox' => array(
		'vi' => 'Cài ứng dụng Firefox',
		'en' => 'Install Firefox app',
		'ru' => 'Установите Firefox приложение',
		'es' => 'Instalar Firefox aplicación',
		'zh' => '安装Firefox的应用程序',
		'ja' => 'Firefoxのアプリをインストール'
	),
	'register' => array(
		'vi' => 'Đăng ký',
		'en' => 'Register',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'try_it' => array(
		'vi' => 'Đăng ký',
		'en' => 'Try it',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'update' => array(
		'vi' => 'Cập nhật',
		'en' => 'Update',
		'ru' => 'Обновлять',
		'es' => 'Actualizar',
		'zh' => '更新',
		'ja' => 'アップデート'
	),
	'login' => array(
		'vi' => 'Đăng nhập',
		'en' => 'Log In',
		'ru' => 'Войти',
		'es' => 'Iniciar Sesión',
		'zh' => '登录',
		'ja' => 'ログイン'
	),
	'logout' => array(
		'vi' => 'Đăng xuất',
		'en' => 'Log Out',
		'ru' => 'Выйти',
		'es' => 'Cerrar Sesión',
		'zh' => '登出',
		'ja' => 'ログアウト'
	),
	'change_pass' => array(
		'vi' => 'Đổi mật khẩu',
		'en' => 'Change password',
		'ru' => 'Изменить пароль',
		'es' => 'Cambiar la contraseña',
		'zh' => '更改密码',
		'ja' => 'パスワードを変更する'
	),
	'edit' => array(
		'vi' => 'Sửa',
		'en' => 'Edit',
		'ru' => 'Редактировать',
		'es' => 'Editar',
		'zh' => '编辑',
		'ja' => '編集'
	),
	'profile' => array(
		'vi' => 'Hồ sơ',
		'en' => 'Profile',
		'ru' => 'Профиль',
		'es' => 'Perfil',
		'zh' => '轮廓',
		'ja' => 'プロフィール'
	),
	'sleep_now' => array(
		'vi' => 'Ngủ ngay bây giờ!',
		'en' => 'Sleep now!',
		'ru' => 'Засыпай!',
		'es' => '¡Duerme ahora!',
		'zh' => '现在睡觉！',
		'ja' => '今スリープ！'
	),
	'upgrade' => array(
		'vi' => 'Nâng cấp',
		'en' => 'Upgrade',
		'ru' => 'Обновить',
		'es' => 'Mejorar',
		'zh' => '升级',
		'ja' => 'アップグレード'
	),
	'submit' => array(
		'vi' => 'Gửi',
		'en' => 'Submit',
		'ru' => 'Отправить',
		'es' => 'Enviar',
		'zh' => '提交',
		'ja' => '提出します'
	),
	'contact' => array(
		'vi' => 'Liên hệ',
		'en' => 'Contact',
		'ru' => 'Связаться',
		'es' => 'Contactar',
		'zh' => '联系',
		'ja' => '接触'
	)
);
$span_interfaces = array(
	'me' => array(
		'vi' => 'Tôi',
		'en' => 'Me',
		'ru' => 'Меня',
		'es' => 'Yo',
		'zh' => '我',
		'ja' => '私に'
	),
	'author' => array(
		'vi' => 'Tác giả',
		'en' => 'Author',
		'ru' => 'Автор',
		'es' => 'Autor',
		'zh' => '作者',
		'ja' => '著者'
	),
	'author_intro' => array(
		'vi' => '<h2>Giới thiệu</h2><p>Tôi là Phạm Tùng, hiện đang là lập trình viên PHP.</p>',
		'en' => '<h2>Introduction</h2><p>I am Tung Pham, currently a PHP programmer.</p>',
		'ru' => '<h2>Введение</h2><p>Я Тунг Фам, в настоящее время программист PHP.</p>',
		'es' => '<h2>Introducción</h2><p>Soy Tung Pham, actualmente programador de PHP.</p>',
		'zh' => '<h2>介绍</h2><p>我是目前的PHP程序员范松。</p>',
		'ja' => '<h2>前書き</h2><p>私は現在、PHPプログラマーである范松です。</p>'
	),
	'author_projects' => array(
		'vi' => '<h2>Các dự án</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">bấm vào đây</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">bấm vào đây</a></li></ol>',
		'en' => '<h2>Projects</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">click here</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">click here</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">click here</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">click here</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">click here</a></li></ol>',
		'ru' => '<h2>Проектов</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">кликните сюда</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">кликните сюда</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">кликните сюда</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">кликните сюда</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">кликните сюда</a></li></ol>',
		'es' => '<h2>Proyectos</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">haga clic aquí</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">haga clic aquí</a></li></ol>',
		'zh' => '<h2>项目</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">点击这里</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">点击这里</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">点击这里</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">点击这里</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">点击这里</a></li></ol>',
		'ja' => '<h2>プロジェクト</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">ここをクリック</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">ここをクリック</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">ここをクリック</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">ここをクリック</a></li><li>Cung Rao . NET -> <a href="http://cungrao.net\/">ここをクリック</a></li></ol>'
	),
	'list_user_same_birthday_links' => array(
		'vi' => 'Những người trùng ngày sinh với tôi',
		'en' => 'People with same birthday with me',
		'ru' => 'Люди с таким же рождения со мной',
		'es' => 'Las personas con misma fecha de cumpleaños conmigo',
		'zh' => '与人同一天生日与我',
		'ja' => '私と同じ誕生日を持つ人々'
	),
	'list_user_birthday_links' => array(
		'vi' => 'Sinh nhật người nổi tiếng hôm nay',
		'en' => 'Celebrities birthdays today',
		'ru' => 'Знаменитости дня рождения',
		'es' => 'Celebridades cumpleaños hoy',
		'zh' => '名人今天生日',
		'ja' => '今日は有名人誕生日'
	),
	'list_user_links' => array(
		'vi' => 'Ngày sinh người nổi tiếng',
		'en' => 'Celebrities birth dates',
		'ru' => 'Знаменитости даты рождения',
		'es' => 'Celebridades fecha de nacimiento',
		'zh' => '人的出生日期',
		'ja' => '有名人誕生日'
	),
	'list_persons' => array(
		'vi' => 'Danh sách ngày sinh của tôi',
		'en' => 'My birthdates list',
		'ru' => 'Мой список дат рождения',
		'es' => 'Mi lista de fechas de nacimiento',
		'zh' => '我的出生日期列表',
		'ja' => '私の誕生日一覧'
	),
	'no_persons' => array(
		'vi' => 'Tạo ngày sinh đầu tiên nào',
		'en' => 'Create first birthdate now',
		'ru' => 'Создать первую дату рождения в настоящее время',
		'es' => 'Crea primera fecha de nacimiento ahora',
		'zh' => '现在创建第一个生日',
		'ja' => '今最初の誕生日を作成します'
	),
	'copyright' => array(
		'vi' => 'bản quyền thuộc',
		'en' => 'copyright',
		'ru' => 'авторское право',
		'es' => 'derechos del autor',
		'zh' => '著作權',
		'ja' => 'コピーライト'
	),
	'pham_tung' => array(
		'vi' => 'Phạm Tùng',
		'en' => 'Tung Pham',
		'ru' => 'Тунг Фам',
		'es' => 'Tung Pham',
		'zh' => '范松',
		'ja' => '范松'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'blog' => array(
		'vi' => 'Blog',
		'en' => 'Blog',
		'ru' => 'блог',
		'es' => 'Blog',
		'zh' => '博客',
		'ja' => 'ブログ'
	),
	'forum' => array(
		'vi' => 'Diễn đàn',
		'en' => 'Forum',
		'ru' => 'Форум',
		'es' => 'Forum',
		'zh' => '论坛',
		'ja' => 'フォーラム'
	),
	'sleep_time' => array(
		'vi' => 'Nhịp sinh học ngủ',
		'en' => 'Sleep rhythm',
		'ru' => 'Ритм сна',
		'es' => 'Ritmo del sueño',
		'zh' => '睡眠节律',
		'ja' => '睡眠リズム'
	),
	'hour' => array(
		'vi' => 'Giờ',
		'en' => 'Hour',
		'ru' => 'Час',
		'es' => 'Hora',
		'zh' => '钟头',
		'ja' => 'アワー'
	),
	'minute' => array(
		'vi' => 'Phút',
		'en' => 'Minute',
		'ru' => 'Минут',
		'es' => 'Minuto',
		'zh' => '分钟',
		'ja' => '一刻'
	),
	'sleep_time_head' => array(
		'vi' => 'Nếu bạn dự định thức dậy lúc',
		'en' => 'If you plan to get up at',
		'ru' => 'Если вы встаете на',
		'es' => 'Si te levantas a',
		'zh' => '如果你起床',
		'ja' => 'あなたは時に起きる場合'
	),
	'wake_up_time_head' => array(
		'vi' => 'Hoặc nếu bạn muốn ngủ ngay bây giờ',
		'en' => 'Or if you want to sleep right now',
		'ru' => 'Или, если вы ложитесь спать прямо сейчас',
		'es' => 'O si usted va a dormir en este momento',
		'zh' => '或者，如果你去睡觉，现在',
		'ja' => 'あるいは場合は、あなたは今すぐに眠りにつきます'
	),
	'sleep_time_results' => array(
		'vi' => 'Bạn nên đi ngủ vào một trong những giờ sau:',
		'en' => 'You should try to fall asleep at one of the following times:',
		'ru' => 'Вы должны попытаться заснуть в одном из следующих времен:',
		'es' => 'Usted debe tratar de conciliar el sueño en uno de los siguientes horarios:',
		'zh' => '你应该尝试入睡的以下任一：',
		'ja' => 'あなたは、以下のいずれかの時点で眠りに落ちるようにしてください：'
	),
	'wake_up_time_results' => array(
		'vi' => 'Bạn nên thức dậy vào một trong những giờ sau:',
		'en' => 'You should try to get up at one of the following times:',
		'ru' => 'Вы должны попробовать, чтобы встать в один из следующих случаях:',
		'es' => 'Usted debe tratar de levantarse a uno de los siguientes horarios:',
		'zh' => '你应该尝试起床的以下任一：',
		'ja' => 'あなたは、以下のいずれかの時点で、最大取得しようとする必要があります：'
	),
	'news' => array(
		'vi' => 'Tin tức',
		'en' => 'News',
		'ru' => 'Новости',
		'es' => 'Noticias',
		'zh' => '新闻',
		'ja' => 'ニュース'
	),
	'apps' => array(
		'vi' => 'Ứng dụng',
		'en' => 'Applications',
		'ru' => 'Приложений',
		'es' => 'Aplicaciones',
		'zh' => '应用',
		'ja' => 'アプリ'
	),
	'apps_six_lang' => array(
		'vi' => '6 ngôn ngữ',
		'en' => '6 language',
		'ru' => '6 язык',
		'es' => '6 lenguaje',
		'zh' => '6語言',
		'ja' => '6言語'
	),
	'apps_one_lang' => array(
		'vi' => 'Một ngôn ngữ',
		'en' => 'Single language',
		'ru' => 'Один язык',
		'es' => 'Uno lenguaje',
		'zh' => '一語言',
		'ja' => '一言語'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'fullname' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'dob' => array(
		'vi' => 'Ngày sinh',
		'en' => 'Date of birth',
		'ru' => 'Дата рождения',
		'es' => 'Fecha de nacimiento',
		'zh' => '出生日期',
		'ja' => '生まれた日'
	),
	'password' => array(
		'vi' => 'Mật khẩu',
		'en' => 'Password',
		'ru' => 'Пароль',
		'es' => 'Contraseña',
		'zh' => '密码',
		'ja' => 'パスワード'
	),
	'repeat_password' => array(
		'vi' => 'Lặp lại',
		'en' => 'Repeat',
		'ru' => 'Повторять',
		'es' => 'Repetición',
		'zh' => '重复',
		'ja' => 'リピート'
	),
	'register' => array(
		'vi' => 'Đăng ký',
		'en' => 'Register',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'login' => array(
		'vi' => 'Đăng nhập',
		'en' => 'Log In',
		'ru' => 'Войти',
		'es' => 'Iniciar Sesión',
		'zh' => '登录',
		'ja' => 'ログイン'
	),
	'not_yet_registered' => array(
		'vi' => 'Chưa đăng ký?',
		'en' => 'Not yet registered?',
		'ru' => 'Еще не зарегистрированы?',
		'es' => '¿Todavía no estás registrado?',
		'zh' => '尚未注册？',
		'ja' => 'まだ登録されていませんか？'
	),
	'already_registered' => array(
		'vi' => 'Đã đăng ký?',
		'en' => 'Already registered?',
		'ru' => 'Уже зарегистрирован?',
		'es' => '¿Ya registrado?',
		'zh' => '已经注册？',
		'ja' => '既に登録されています？'
	),
	'forget_password' => array(
		'vi' => 'Quên mật khẩu?',
		'en' => 'Forget password?',
		'ru' => 'Забыли пароль?',
		'es' => '¿Contraseña olvidada?',
		'zh' => '忘记密码？',
		'ja' => 'パスワードをお忘れですか？'
	),
	'forget_password_hint' => array(
		'vi' => '[cho mình biết họ tên đăng ký của bạn]',
		'en' => '[let me know your registered full name]',
		'ru' => '[Дайте мне знать ваше зарегистрированное полное имя]',
		'es' => '[Me dejó saber su nombre completo registrada]',
		'zh' => '[让我知道你注册的全名]',
		'ja' => '[私はあなたの登録フルネームをお知らせ]'
	),
	'trial_expire' => array(
		'vi' => 'Đã hết hạn dùng thử. Hãy đăng ký ngay để không còn quảng cáo.',
		'en' => 'Your trial has expired. Sign up for ads free.',
		'ru' => 'Ваш суд истек. Подпишитесь на объявления бесплатно.',
		'es' => 'Su prueba ha caducado. Inscríbete gratis anuncios.',
		'zh' => '您的试用已过期。订阅的分类广告。',
		'ja' => '試用期限が切れています。無料広告にサインアップしてください。'
	),
	'register_modal' => array(
		'vi' => 'Hãy đăng ký ngay và khám phá thêm nhiều tính năng.',
		'en' => 'Exploring more about your health, emotion, and mind.',
		'ru' => 'Зарегистрируйтесь сейчас, чтобы изучить больше возможностей.',
		'es' => 'Registrate ahora para explorar más características.',
		'zh' => '现在注册探索更多的功能。',
		'ja' => 'より多くの機能を探索するために今すぐ登録。'
	),
	'biorhythm' => array(
		'vi' => 'nhịp sinh học',
		'en' => 'biorhythm',
		'ru' => 'биоритм',
		'es' => 'biorritmo',
		'zh' => '生理节律',
		'ja' => 'バイオリズム'
	),
	'health' => array(
		'vi' => 'sức khỏe',
		'en' => 'health',
		'ru' => 'здоровье',
		'es' => 'salud',
		'zh' => '健康',
		'ja' => 'ヘルス'
	),
	'year' => array(
		'vi' => 'năm',
		'en' => 'year',
		'ru' => 'год',
		'es' => 'año',
		'zh' => '年',
		'ja' => '年'
	),
	'advertisements' => array(
		'vi' => 'Quảng cáo',
		'en' => 'Advertisements',
		'ru' => 'Объявления',
		'es' => 'Anuncios',
		'zh' => '广告',
		'ja' => '広告'
	),
	'keyboard_shortcuts' => array(
		'vi' => 'Phím tắt',
		'en' => 'Keyboard shortcuts',
		'ru' => 'Горячие клавиши',
		'es' => 'Atajos de teclado',
		'zh' => '快捷键',
		'ja' => 'キーボードショートカット'
	),
	'keyboard_shortcuts_long' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>S / G / K -> Hôm nay</li><li>A / F / J -> Trước</li><li>D / H / L -> Sau</li><li>W / T / I -> Sinh nhật</li><li>E / Y / O -> Nhịp sinh học phụ</li><li>R / U / P -> Thành ngữ</li><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h6>Keyboard shortcuts:</h6><ul><li>S / G / K -> Today</li><li>A / F / J -> Back</li><li>D / H / L -> Forward</li><li>W / T / I -> Birthday</li><li>E / Y / O -> Secondary rhythm</li><li>R / U / P -> Proverb</li><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h6>Горячие клавиши:</h6><ul><li>S / G / K -> Сегодня</li><li>A / F / J -> Назад</li><li>D / H / L -> Вперед</li><li>W / T / I -> День рождения</li><li>E / Y / O -> Вторичный ритм</li><li>R / U / P -> Пословица</li><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h6>Atajos de teclado:</h6><ul><li>S / G / K -> Hoy</li><li>A / F / J -> Atrás</li><li>D / H / L -> Enviar</li><li>W / T / I -> Cumpleaños</li><li>E / Y / O -> Ritmo secundaria</li><li>R / U / P -> Proverbio</li><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h6>快捷键：</h6><ul><li>S，G，K -> 今天</li><li>A，F，J -> 回去</li><li>D，H，L -> 前进</li><li>W，T，I -> 生辰</li><li>E，Y，O -> 次要节奏</li><li>R，U，P -> 谚语</li><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h6>キーボードショートカット：</h6><ul><li>S、G、K -> 今日</li><li>A、F、J -> 戻る</li><li>D、H、L -> 前進する</li><li>W、T、I -> バースデー</li><li>E、Y、O -> セカンダリリズム</li><li>R、U、P -> ことわざ</li><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'keyboard_shortcuts_short' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h6>Keyboard shortcuts:</h6><ul><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h6>Горячие клавиши:</h6><ul><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h6>Atajos de teclado:</h6><ul><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h6>快捷键：</h6><ul><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h6>キーボードショートカット：</h6><ul><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'bank_account' => array(
		'vi' => '<h6>Tài khoản ngân hàng:</h6><ul><li>Ngân hàng: TECHCOMBANK</li><li>Số tài khoản: 19027906069012</li><li>Tên người thụ hưởng: PHAM TUNG</li><li>Chi nhánh: Phú Mỹ Hưng</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'en' => '<h6>Bank account:</h6><ul><li>Bank: TECHCOMBANK</li><li>Account number: 19027906069012</li><li>Beneficiary name: PHAM TUNG</li><li>Branch: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'ru' => '<h6>банковский счет:</h6><ul><li>Банка: TECHCOMBANK</li><li>Номер аккаунта: 19027906069012</li><li>Имя Получателя: PHAM TUNG</li><li>Филиал: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'es' => '<h6>Cuenta bancaria:</h6><ul><li>Banco: TECHCOMBANK</li><li>Número de cuenta: 19027906069012</li><li>Nombre del Beneficiario: PHAM TUNG</li><li>Branch: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'zh' => '<h6>银行账户：</h6><ul><li>银行： TECHCOMBANK</li><li>帐号： 19027906069012</li><li>受益人姓名： PHAM TUNG</li><li>科： Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'ja' => '<h6>銀行口座：</h6><ul><li>バンク： TECHCOMBANK</li><li>口座番号： 19027906069012</li><li>受取人名： PHAM TUNG</li><li>ブランチ： Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
	),
	'for_reference_only' => array(
		'vi' => 'Chỉ mang tính tham khảo',
		'en' => 'For reference only',
		'ru' => 'Только для справки',
		'es' => 'Solo por referencia',
		'zh' => '仅供参考',
		'ja' => '参考のためのみ'
	),
	'comments_head' => array(
		'vi' => 'Mong nhận được ý kiến của các bạn. Hãy để lại bình luận dưới đây.',
		'en' => 'Look forward to your comments. Put them down here.',
		'ru' => 'Посмотрите ждем ваших комментариев. Положите их сюда.',
		'es' => 'Esperamos sus comentarios. Póngalos aquí.',
		'zh' => '期待您的意见。把它们放在这儿。',
		'ja' => 'あなたのコメントを楽しみにしています。ここではそれらを置きます。'
	),
	'bmi_weight' => array(
		'vi' => 'Cân nặng:',
		'en' => 'Weight:',
		'ru' => 'Вес:',
		'es' => 'Peso:',
		'zh' => '重量：',
		'ja' => '重さ：'
	),
	'bmi_height' => array(
		'vi' => 'Chiều cao:',
		'en' => 'Height:',
		'ru' => 'Высота:',
		'es' => 'Altura:',
		'zh' => '身高：',
		'ja' => '身長：'
	),
	'bmi_weight_unit' => array(
		'vi' => 'ký',
		'en' => 'kg',
		'ru' => 'кг',
		'es' => 'kilo',
		'zh' => '千克',
		'ja' => 'キロ'
	),
	'bmi_height_unit' => array(
		'vi' => 'mét',
		'en' => 'metre',
		'ru' => 'метр',
		'es' => 'medidor',
		'zh' => '计',
		'ja' => 'メーター'
	),
	'bmi_value' => array(
		'vi' => 'Chỉ số BMI:',
		'en' => 'BMI value:',
		'ru' => 'Значение ИМТ:',
		'es' => 'Valor de IMC:',
		'zh' => 'BMI值：',
		'ja' => 'BMI値：'
	),
	'bmi_explanation' => array(
		'vi' => 'Đánh giá:',
		'en' => 'Explanation:',
		'ru' => 'Объяснение:',
		'es' => 'Explicación:',
		'zh' => '说明：',
		'ja' => '説明：'
	),
	'bmi_ideal_weight' => array(
		'vi' => 'Cân nặng lý tưởng:',
		'en' => 'Ideal weight:',
		'ru' => 'Идеальный вес:',
		'es' => 'Peso ideal:',
		'zh' => '理想的体重：',
		'ja' => '理想的な体重：'
	),
	'bmi_ideal_height' => array(
		'vi' => 'Chiều cao lý tưởng:',
		'en' => 'Ideal height:',
		'ru' => 'Идеальная высота:',
		'es' => 'Altura ideal:',
		'zh' => '理想的身高：',
		'ja' => '理想の高さ：'
	),
	'bmi_recommendation' => array(
		'vi' => 'Lời khuyên:',
		'en' => 'Recommendation:',
		'ru' => 'Рекомендация:',
		'es' => 'Recomendación:',
		'zh' => '建议：',
		'ja' => '推奨事項：'
	),
	'bmi' => array(
		'vi' => 'Chỉ số khối cơ thể',
		'en' => 'Body mass index',
		'ru' => 'Индекс массы тела',
		'es' => 'IMC',
		'zh' => '身高體重指數',
		'ja' => 'ボディマス指数'
	),
	'vip' => array(
		'vi' => 'Nâng cấp tài khoản VIP để sử dụng các tính năng đầy đủ, và không có quảng cáo. Thanh toán một lần. Chỉ có 12 USD.',
		'en' => 'Upgrade to VIP account to use full features, and no ads. One time payment. Only 12 USD.',
		'ru' => 'Обновление до VIP счет использования полных возможностей и без рекламы. Одноразовый платеж. Только 12 долларов США.',
		'es' => 'Actualizar a cuenta VIP para usar las características completas y sin anuncios. Pago único. Sólo 12 USD.',
		'zh' => '升级为VIP帐户才能使用全部功能，而且没有广告。一次性付款。只有12美元。',
		'ja' => 'フル機能、および広告がないを使用するVIPアカウントにアップグレードしてください。一回払い。わずか12ドル。'
	),
	'vip_title' => array(
		'vi' => 'Nâng cấp lên tài khoản VIP',
		'en' => 'Upgrade to VIP account',
		'ru' => 'Обновление до VIP счет',
		'es' => 'Actualizar a cuenta VIP',
		'zh' => '升级为VIP帐号。',
		'ja' => 'VIPアカウントにアップグレード'
	),
	'upgrade' => array(
		'vi' => 'Nâng cấp',
		'en' => 'Upgrade',
		'ru' => 'Обновить',
		'es' => 'Mejorar',
		'zh' => '升级',
		'ja' => 'アップグレード'
	),
	'latest_posts' => array(
		'vi' => 'Bài viết mới',
		'en' => 'Latest posts',
		'ru' => 'Последние посты',
		'es' => 'Últimas publicaciones',
		'zh' => '最新文章',
		'ja' => '最新の投稿'
	),
	'view_more' => array(
		'vi' => 'Xem thêm',
		'en' => 'View more',
		'ru' => 'Показать еще',
		'es' => 'Ver más',
		'zh' => '查看更多',
		'ja' => 'もっと見る'
	),
	'or' => array(
		'vi' => 'Hoặc',
		'en' => 'Or',
		'ru' => 'Или',
		'es' => 'O',
		'zh' => '要么',
		'ja' => 'または'
	),
	'donate_intro' => array(
		'vi' => '<h2>Kính chào quý khách</h2><br/><h4>Nhằm duy trì sự hiện diện của trang Nhịp Sinh Học . VN, kính mong quý khách dành 20.000 đồng để đóng góp vào quỹ xây dựng website thông qua các hình thức thanh toán sau:</h4><br/>',
		'en' => '<h2>Dear visitors</h2><br/><h4>In order to raise the funds to maintain Nhịp Sinh Học . VN, please spend 20.000 VND through the payment methods below:</h4><br/>',
		'ru' => '<h2>Уважаемые посетители</h2><br/><h4>Для того чтобы поддерживать Nhip Sinh Hoc. VN, пожалуйста, потратить 20.000 донгов с помощью методов оплаты ниже:</h4><br/>',
		'es' => '<h2>Estimados visitantes</h2><br/><h4>Con el fin de mantener la Nhip Sinh Hoc . VN, por favor pasar 20.000 VND a través de los siguientes métodos de pago:</h4><br/>',
		'zh' => '<h2>亲爱的访客</h2><br/><h4>为了保持Nhip Sinh Hoc. VN，请花20.000 VND经过下面的付款方式：</h4><br/>',
		'ja' => '<h2>親愛なる訪問者</h2><br/><h4>Nhip Sinh Hoc. VNを維持するためには、以下のお支払い方法を介して20.000 VNDを過ごしてください。</h4><br/>'
	),
	'donate' => array(
		'vi' => 'Ủng hộ',
		'en' => 'Donate',
		'ru' => 'Дарить',
		'es' => 'Donar',
		'zh' => '捐赠',
		'ja' => '寄付する'
	),
	'donate_reason' => array(
		'vi' => 'nếu bạn thấy trang có ích',
		'en' => 'if you find it useful',
		'ru' => 'коли вас считать это полезным',
		'es' => 'si usted lo encuentra útil',
		'zh' => '如果您发现它有用',
		'ja' => 'あなたはそれが役立つかどう'
	),
	'sponsor' => array(
		'vi' => 'Tài trợ',
		'en' => 'Sponsor',
		'ru' => 'Спонсор',
		'es' => 'Patrocinio',
		'zh' => '贊助',
		'ja' => 'スポンサー'
	),
	'contact' => array(
		'vi' => 'Liên hệ với chúng tôi',
		'en' => 'Contact us',
		'ru' => 'Свяжитесь с нами',
		'es' => 'Contáctenos',
		'zh' => '联系我们',
		'ja' => 'お問い合わせ'
	),
	'content' => array(
		'vi' => 'Nội dung',
		'en' => 'Content',
		'ru' => 'Содержание',
		'es' => 'Contenido',
		'zh' => '内容',
		'ja' => 'コンテンツ'
	),
	'update_success' => array(
		'vi' => 'Cập nhật thành công!',
		'en' => 'Updated successfully!',
		'ru' => 'Успешно Обновлено!',
		'es' => '¡Actualizado correctamente!',
		'zh' => '成功更新！',
		'ja' => '正常に更新！'
	),
	'submit_success' => array(
		'vi' => 'Đã gửi thành công!',
		'en' => 'Submitted successfully!',
		'ru' => 'Успешно!',
		'es' => '¡Enviado satisfactoriamente!',
		'zh' => '提交成功！',
		'ja' => '正常に送信！'
	),
	'adblock' => array(
		'vi' => 'Trang web của chúng tôi vận hành được là nhờ hiển thị quảng cáo cho quý vị độc giả.<br/> Vui lòng hỗ trợ chúng tôi bằng cách tắt phần mềm chặn quảng cáo của bạn.',
		'en' => 'Our website is made possible by displaying online advertisements to our visitors.<br/> Please consider supporting us by disabling your ad blocker.',
		'ru' => 'Наш веб-сайт стал возможным благодаря показа рекламы в Интернете для наших посетителей.<br/> Пожалуйста, обратите внимание поддержку нас, отключив объявление блокатор.',
		'es' => 'Nuestro sitio web es posible gracias a la visualización de anuncios en línea para nuestros visitantes.<br/> Por favor considere apoyarnos mediante la desactivación de su bloqueador de anuncios.',
		'zh' => '我们的网站是通过向我们的访客显示在线广告。<br/> 请考虑通过停用广告拦截器来支持我们。',
		'ja' => '弊社のウェブサイトは、当社の訪問者にオンライン広告を表示することによって可能となります。<br/>広告ブロッカーを無効にすることによって私たちをサポートしてご検討ください。'
	),
	'proverbs' => array(
		'vi' => 'Danh ngôn',
		'en' => 'Proverbs',
		'ru' => 'Пословицы',
		'es' => 'Proverbios',
		'zh' => '谚语',
		'ja' => '諺'
	),
	'all_proverbs' => array(
		'vi' => 'Tất cả danh ngôn',
		'en' => 'All proverbs',
		'ru' => 'Все пословицы',
		'es' => 'Todos los proverbios',
		'zh' => '所有谚语',
		'ja' => 'すべての諺'
	)
);
$email_interfaces = array(
	'hi' => array(
		'vi' => 'Xin chào bạn',
		'en' => 'Hi',
		'ru' => 'Привет',
		'es' => 'Hola',
		'zh' => '你好',
		'ja' => 'こんにちは'
	),
	'happy_birthday' => array(
		'vi' => 'Chúc mừng sinh nhật bạn',
		'en' => 'Happy birthday',
		'ru' => 'Днем Рождения',
		'es' => 'Feliz cumpleaños',
		'zh' => '生日快乐',
		'ja' => 'お誕生日おめでとうございます'
	),
	'create_user_thank' => array(
		'vi' => 'Cám ơn bạn đã quan tâm đến Nhịp sinh học.',
		'en' => 'Thank you for your interest in Biorhythm.',
		'ru' => 'Спасибо за ваш интерес к Биоритмы.',
		'es' => 'Gracias por su interés en Biorritmo usted.',
		'zh' => '感谢您对生物节律的兴趣。',
		'ja' => 'バイオリズムにご関心をお寄せいただき、ありがとうございます。'
	),
	'create_user_detail' => array(
		'vi' => 'Sau đây là thông tin tài khoản của bạn:',
		'en' => 'Here is your account information:',
		'ru' => 'Вот информация Ваш счет:',
		'es' => 'Aquí está la información de su cuenta:',
		'zh' => '这是您的帐户信息：',
		'ja' => 'ここにあなたのアカウント情報は、次のとおりです：'
	),
	'edit_user_notify' => array(
		'vi' => 'Bạn đã cập nhật hồ sơ Nhịp sinh học.',
		'en' => 'You have updated your Biorhythm profile.',
		'ru' => 'Вы обновили свой профиль Биоритм.',
		'es' => 'Ha actualizado su perfil Biorritmo.',
		'zh' => '您已经更新了您的个人资料生物节律。',
		'ja' => 'あなたのバイオリズムのプロフィールを更新しました。'
	),
	'edit_user_detail' => array(
		'vi' => 'Sau đây là thông tin tài khoản của bạn sau khi sửa đổi hồ sơ:',
		'en' => 'Here is your account information after updating profile:',
		'ru' => 'Вот информация Ваш счет после обновления профиля:',
		'es' => 'Aquí está la información de su cuenta después de actualizar el perfil:',
		'zh' => '这里是更新配置文件后您的帐户信息：',
		'ja' => 'ここでは、プロファイルを更新した後、あなたのアカウント情報は、次のとおりです：'
	),
	'daily_suggestion' => array(
		'vi' => 'Đây là lời khuyên cho bạn ngày hôm nay',
		'en' => 'This is your daily suggestion',
		'ru' => 'Это ваш день предложение',
		'es' => 'Esta es su sugerencia diaria',
		'zh' => '这是你的每日建议',
		'ja' => 'これはあなたの毎日の提案です'
	),
	'daily_values' => array(
		'vi' => 'Các chỉ số nhịp sinh học chính của bạn',
		'en' => 'Your primary biorhythm values',
		'ru' => 'Ваши первичные значения биоритмов',
		'es' => 'Sus valores biorritmo primarias',
		'zh' => '您的主要生物节律值',
		'ja' => 'あなたの主なバイオリズム値'
	),
	'go_to_your_profile' => array(
		'vi' => 'Đi đến hồ sơ của bạn',
		'en' => 'Go to your profile',
		'ru' => 'Перейти в профиль',
		'es' => 'Ir a su perfil',
		'zh' => '转到您的个人资料',
		'ja' => 'あなたのプロフィールに移動します'
	),
	'colon' => array(
		'vi' => ':',
		'en' => ':',
		'ru' => ':',
		'es' => ':',
		'zh' => '：',
		'ja' => '：'
	),
	'going_up' => array(
		'vi' => 'đang lên',
		'en' => 'going up',
		'ru' => 'подниматься',
		'es' => 'subiendo',
		'zh' => '上升',
		'ja' => '上がっていく'
	),
	'going_down' => array(
		'vi' => 'đang xuống',
		'en' => 'going down',
		'ru' => 'спускаться',
		'es' => 'bajando',
		'zh' => '下降',
		'ja' => '下っていく'
	),
	'regards' => array(
		'vi' => 'Trân trọng,',
		'en' => 'Best regards,',
		'ru' => 'С уважением,',
		'es' => 'Atentamente,',
		'zh' => '最好的问候，',
		'ja' => '宜しくお願いします、'
	),
	'not_changed' => array(
		'vi' => 'Không thay đổi',
		'en' => 'Not changed',
		'ru' => 'Не изменилось',
		'es' => 'Sin cambio',
		'zh' => '没有改变',
		'ja' => '変更されていません'
	),
	'not_mark_as_spam' => array(
		'vi' => 'Đây không phải là thư rác. Vui lòng không đánh dấu thư rác.',
		'en' => 'This is not a spam. Please do not mark it as spam.',
		'ru' => 'Это не спам. Пожалуйста, не отметить его как спам.',
		'es' => 'Esto no es un spam. Por favor, no marcarlo como spam.',
		'zh' => '这不是一个垃圾邮件。请不要将其标记为垃圾邮件。',
		'ja' => 'これはスパムではありません。スパムとしてそれをマークしないでください。'
	),
	'definition' => array(
		'vi' => 'Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh. Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.',
		'en' => 'A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm") is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.',
		'ru' => 'Биоритм - (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях ее организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе. Одни биологические ритмы относительно самостоятельны (например, частота сокращений сердца, дыхания), другие связаны с приспособлением организмов к геофизическим циклам — суточным (например, колебания интенсивности деления клеток, обмена веществ, двигательной активности животных), приливным (например, открывание и закрывание раковин у морских моллюсков, связанные с уровнем морских приливов), годичным (изменение численности и активности животных, роста и развития растений и др.)',
		'es' => 'Los biorritmos constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.',
		'zh' => '生理节律是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。',
		'ja' => 'バイオリズム（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。'
	),
	'instruction_video_text' => array(
		'vi' => 'Video hướng dẫn',
		'en' => 'Instruction video',
		'ru' => 'Видео инструкции',
		'es' => 'Instrucción de vídeo',
		'zh' => '教学视频',
		'ja' => '教育ビデオ'
	),
	'instruction_video_youtube_id' => array(
		'vi' => '0od3PsgixvQ',
		'en' => '7dRGGRcvI0E',
		'ru' => 'rp8_cTRP4ro',
		'es' => 'sifJsC3v-Lw',
		'zh' => 'TG2ngtokaVc',
		'ja' => 'SJw7lMuKipc'
	),
	'keyboard_shortcuts' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>S / G / K -> Hôm nay</li><li>A / F / J -> Trước<li>D / H / L -> Sau</li><li>W / T / I -> Sinh nhật</li><li>E / Y / O -> Nhịp sinh học phụ</li><li>R / U / P -> Thành ngữ</li><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h6>Keyboard shortcuts:</h6><ul><li>S / G / K -> Today</li><li>A / F / J -> Back</li><li>D / H / L -> Forward</li><li>W / T / I -> Birthday</li><li>E / Y / O -> Secondary rhythm</li><li>R / U / P -> Proverb</li><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h6>Горячие клавиши:</h6><ul><li>S / G / K -> Сегодня</li><li>A / F / J -> Назад</li><li>D / H / L -> Вперед</li><li>W / T / I -> День рождения</li><li>E / Y / O -> Вторичный ритм</li><li>R / U / P -> Пословица</li><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h6>Atajos de teclado:</h6><ul><li>S / G / K -> Hoy</li><li>A / F / J -> Atrás</li><li>D / H / L -> Enviar</li><li>W / T / I -> Cumpleaños</li><li>E / Y / O -> Ritmo secundaria</li><li>R / U / P -> Proverbio</li><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h6>快捷键：</h6><ul><li>S，G，K -> 今天</li><li>A，F，J -> 回去</li><li>D，H，L -> 前进</li><li>W，T，I -> 生辰</li><li>E，Y，O -> 次要节奏</li><li>R，U，P -> 谚语</li><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h6>キーボードショートカット：</h6><ul><li>S、G、K -> 今日</li><li>A、F、J -> 戻る</li><li>D、H、L -> 前進する</li><li>W、T、I -> バースデー</li><li>E、Y、O -> セカンダリリズム</li><li>R、U、P -> ことわざ</li><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'unsubscribe' => array(
		'vi' => 'Hủy đăng ký',
		'en' => 'Unsubscribe',
		'ru' => 'Отказаться',
		'es' => 'Darse de baja',
		'zh' => '退订',
		'ja' => '退会'
	)
);
$menu_interfaces = array(
	'today' => array(
		'vi' => 'Hôm nay',
		'en' => 'Today',
		'ru' => 'Сегодня',
		'es' => 'Hoy',
		'zh' => '今天',
		'ja' => '今日'
	),
	'prev' => array(
		'vi' => 'Trước',
		'en' => 'Back',
		'ru' => 'Назад',
		'es' => 'Atrás',
		'zh' => '回去',
		'ja' => '戻る'
	),
	'next' => array(
		'vi' => 'Sau',
		'en' => 'Forward',
		'ru' => 'Вперед',
		'es' => 'Enviar',
		'zh' => '前进',
		'ja' => '前進する'
	),
	'next_birthday' => array(
		'vi' => 'Sinh nhật tới',
		'en' => 'Next birthday',
		'ru' => 'Следующий день рождения',
		'es' => 'Próximo cumpleaños',
		'zh' => '下一个生日',
		'ja' => '次の誕生日'
	)
);
$error_interfaces = array(
	'not_filled' => array(
		'vi' => 'Chưa điền hết các mục',
		'en' => 'All the fields must be filled in',
		'ru' => 'Все поля должны быть заполнены',
		'es' => 'Todos los campos deben ser llenados',
		'zh' => '所有字段必须填写',
		'ja' => 'すべてのフィールドは記入する必要があります'
	),
	'invalid_member' => array(
		'vi' => 'Thư điện tử hoặc mật khẩu không chính xác',
		'en' => 'Incorrect email or password',
		'ru' => 'Неверный адрес электронной почты или пароль',
		'es' => 'Correo o contraseña incorrectos',
		'zh' => '不正确的电子邮件或密码',
		'ja' => '不適切な電子メールやパスワード'
	),
	'invalid_email' => array(
		'vi' => 'Thư điện tử không hợp lệ',
		'en' => 'Invalid email',
		'ru' => 'Неверный адрес электронной почты',
		'es' => 'Email inválido',
		'zh' => '不合规电邮',
		'ja' => '無効なメール'
	),
	'short_pass' => array(
		'vi' => 'Mật khẩu quá ngắn (≥ 8)',
		'en' => 'Password too short (≥ 8)',
		'ru' => 'Пароль слишком короткий (≥ 8)',
		'es' => 'Contraseña demasiado corta (≥ 8)',
		'zh' => '密码太短 (≥= 8)',
		'ja' => 'パスワードが短すぎます (≥ 8)'
	), 
	'long_pass' => array(
		'vi' => 'Mật khẩu quá dài (≤ 20)',
		'en' => 'Password too long (≤ 20)',
		'ru' => 'Пароль слишком долго (≤ 20)',
		'es' => 'Contraseña demasiado largo (≤ 20)',
		'zh' => '密码太长 (≤ 20)',
		'ja' => 'あまりにも長いパスワード (≤ 20)'
	),
	'no_number_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ số',
		'en' => 'Password must include at least one number',
		'ru' => 'Пароль должен содержать по крайней мере один номер',
		'es' => 'La contraseña debe incluir al menos un número',
		'zh' => '密码必须包括至少一个数',
		'ja' => 'パスワードは、少なくとも1つの番号を含める必要があります'
	),
	'no_letter_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ cái',
		'en' => 'Password must include at least one letter',
		'ru' => 'Пароль должен содержать по меньшей мере одну букву',
		'es' => 'La contraseña debe incluir al menos una letra',
		'zh' => '密码必须包含至少一个字母',
		'ja' => 'パスワードは、少なくとも1つの文字を含める必要があります'
	),
	'no_caps_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ cái VIẾT HOA',
		'en' => 'Password must include at least one CAPS',
		'ru' => 'Пароль должен содержать по крайней мере один ЗАГЛАВНАЯ БУКВА',
		'es' => 'La contraseña debe incluir al menos un MAYÚSCULA',
		'zh' => '密码必须包括至少一个大写字母',
		'ja' => 'パスワードは、少なくとも1つの大文字を含める必要があります'
	),
	'no_symbol_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt',
		'en' => 'Password must include at least one symbol',
		'ru' => 'Пароль должен содержать по меньшей мере одну символ',
		'es' => 'La contraseña debe incluir al menos una símbolo',
		'zh' => '密码必须包含至少一个符号',
		'ja' => 'パスワードは、少なくとも1つのシンボルを含める必要があります'
	),
	'not_match_pass' => array(
		'vi' => 'Mật khẩu không khớp',
		'en' => 'Password not match',
		'ru' => 'Пароль не совпадает',
		'es' => 'La contraseña no coincide',
		'zh' => '密码不匹配',
		'ja' => 'パスワードが一致しません'
	),
	'invalid_dob' => array(
		'vi' => 'Ngày sinh không hợp lệ',
		'en' => 'Invalid date of birth',
		'ru' => 'Неправильная дата рождения',
		'es' => 'Fecha no válida de nacimiento',
		'zh' => '出生日期无效',
		'ja' => '誕生の無効な日付'
	),
	'taken_email' => array(
		'vi' => 'Thư điện tử đã được người khác dùng',
		'en' => 'Email taken',
		'ru' => 'Электронная почта приняты',
		'es' => 'Email tomada',
		'zh' => '采取电子邮件',
		'ja' => 'メール撮影'
	)
);
$help_interfaces = array(
	'help_h5' => array(
		'vi' => 'Hướng dẫn',
		'en' => 'Instruction',
		'ru' => 'Инструкция',
		'es' => 'Instrucción',
		'zh' => '指令',
		'ja' => '命令'
	),
	'help_p' => array(
		'vi' => 'Nhập thông tin ngày tháng năm sinh Dương lịch của bạn vào ô Ngày sinh theo định dạng YYYY-MM-DD (năm-tháng-ngày). Sau đó, nhấn nút &#9658; để hiển thị dự đoán Sức khỏe, Tình cảm, Trí tuệ. Nếu bạn chỉ quan tâm đến Nhịp sinh học ngủ, bạn không cần điền Họ tên và Ngày sinh.',
		'en' => 'Type in your date of birth into the Date of birth field with the format YYYY-MM-DD (year-month-day). Then click &#9658; to know your physical, emotional, intellectual values. If you only care about Sleep Rhythm, you don\'t need to type in your Full name or Date of birth.',
		'ru' => 'Введите дату своего рождения в поле День Рождения с форматом YYYY-MM-DD (год-месяц-день). Затем нажмите кнопку &#9658;, чтобы узнать ваши физические, эмоциональные, интеллектуальные ценности. Если вы только заботиться о Sleep ритм, вам не нужно вводить полное имя или дату рождения.',
		'es' => 'Escriba su fecha de nacimiento en el campo Fecha de nacimiento con el formato YYYY-MM-DD (año-mes-dia). Luego haga clic en &#9658; para conocer sus valores, físicas, intelectuales y emocionales. Si sólo se preocupa por el sueño Ritmo, usted no tiene que escribir su nombre o fecha de nacimiento completa.',
		'zh' => '输入你的出生日期为出生场的日期格式为YYYY-MM-DD（年-月-日）。然后点击&#9658;就知道你的身体，情绪，智力值。如果你只在乎睡眠节律，你不需要输入您的姓名和出生日期。',
		'ja' => '誕生フィールドの日にあなたの生年月日を入力しますフォーマットYYYY-MM-DDと（年-月-日）。 次に、あなたの物理的、感情的、知的な値を知るために実行]をクリックします。あなたが唯一の睡眠リズムに関心があるのであれば、あなたは自分のフルネームや生年月日を入力する必要はありません。'
	),
	'news_box' => array(
		'vi' => 'Hiển thị các tin tức liên quan đến bạn.',
		'en' => 'Show the news related to you.',
		'ru' => 'Показать новости, связанные с вами.',
		'es' => 'Mostrar las noticias relacionadas con usted.',
		'zh' => '显示的消息与你。',
		'ja' => 'あなたに関連するニュースを表示します。'
	),
	'stats_box' => array(
		'vi' => 'Hiển thị các thông số liên quan đến ngày sinh của bạn.',
		'en' => 'Display the general statistics related to your birth date.',
		'ru' => 'Показать общую статистику, связанные с вашей даты рождения.',
		'es' => 'Mostrar las estadísticas generales relacionadas con su fecha de nacimiento.',
		'zh' => '显示与你的出生日期的统计资料。',
		'ja' => 'あなたの誕生日に関連する一般的な統計情報を表示します。'
	),
	'lunar_box' => array(
		'vi' => 'Hiển thị ngày tháng năm sinh Âm lịch của bạn. Ấn nút Ngày Âm lịch sẽ hiện ra ngày tháng năm Âm lịch hiện tại. Đây là tính năng VIP.',
		'en' => 'Display the Lunar Calendar year, month and day of your birth date. Click on Lunar date to show the current Lunar year, month and day. This is a VIP feature.',
		'ru' => 'Отображение лунному календарю год, месяц и день вашего дня рождения. Нажмите на Лунной даты, чтобы показать текущий лунный год, месяц и день. Это VIP-функция.',
		'es' => 'Visualizar el calendario lunar año, mes y día de su fecha de nacimiento. Clic en la fecha Lunar para mostrar la corriente Lunar año, mes y día. Esta es una característica VIP.',
		'zh' => '显示您的出生日期是农历日历年，月，日。点击农历日期显示当前的农历年，月，日。这是一个VIP功能。',
		'ja' => 'あなたの誕生日の太陰暦の年、月、日を表示します。現在の月の年、月、日を示すために月の日付をクリックします。これはVIPの機能です。'
	),
	'compatibility_box' => array(
		'vi' => 'Cho biết sự khả năng hòa hợp giữa Bạn và Đối tác (người yêu, bạn bè, thân hữu). Chọn ngày sinh (theo thứ tự năm, tháng, ngày) của Đối tác để xem các chỉ số thể hiện mức độ hòa hợp, chỉ số phần trăm càng cao thì càng gần gũi.',
		'en' => 'Show the Compatibility between you and your Partner (lover, friends, acquaintance). Choose the birth date of your partner to get the values indicating compatibility, the higher the more compatible.',
		'ru' => 'Показать совместимость между вами и вашим партнером (любовник, друзья, знакомства). Выберите дату рождения вашего партнера, чтобы получить значения, указывающие совместимость, выше более совместимыми.',
		'es' => 'Mostrar el Compatibilidad entre usted y su pareja (amante, amigos, conocidos). Elija la fecha de nacimiento de su pareja para obtener los valores que indican la compatibilidad, el más alto es el más compatible.',
		'zh' => '告诉你和你的伴侣（情人，朋友，熟人）之间的兼容性。选择你的伴侣的出生日期以获得显示兼容性值，越高越不兼容。',
		'ja' => 'あなたとあなたのパートナー（恋人、友人、知人）間の互換性を表示します。より互換性が高い、互換性を示す値を取得するためにあなたのパートナーの誕生日を選択してください。'
	),
	'info_box' => array(
		'vi' => 'Hiển thị lời khuyên cho ngày hiện tại.',
		'en' => 'Display the suggestion for current date.',
		'ru' => 'Показать предложение для текущей даты.',
		'es' => 'Visualice la sugerencia para la fecha actual.',
		'zh' => '显示的建议为当前日期。',
		'ja' => '現在の日付のための提案を表示します。'
	),
	'controls_box' => array(
		'vi' => 'Hiển thị các chỉ số nhịp sinh học cho ngày hiện tại, chỉ số phần trăm càng cao thì càng tốt. Ấn nút Hiện nhịp sinh học phụ để hiện ra thêm các chỉ số phụ. Chọn ngày bằng cách ấn ô Xem ngày. Ấn nút Trước và Sau để thay đổi ngày hiện tại, nút Hôm nay để trở về Hôm nay.',
		'en' => 'Display the biorhythm values for the current date, the higher the better. Press the button Show secondary rhythms to show more biorhythm values. Choose the date by clicking on the field View date. Click on Back or Forward to change the current date, show today values by clicking on Today.',
		'ru' => 'Отображение значения биоритмов на текущую дату. Нажмите кнопку Показать вторичные ритмы, чтобы показать несколько значений биоритмов. Выберите дату, нажав на дату поле зрения. Нажмите на вперед или назад, чтобы изменить текущую дату, показать значения сегодня, нажав на Сегодня.',
		'es' => 'Muestra los valores de biorritmo para la fecha actual. Pulse el botón Mostrar ritmos secundarias para mostrar más valores biorritmo. Elija la fecha haciendo clic en el campo de fecha Vista. Haga clic en Atrás o en Siguiente para cambiar la fecha actual, mostrar los valores actuales, haga clic en Hoy.',
		'zh' => '显示的生物节律的值对于当前日期。按下按钮显示次要节奏，表现出更多的生物节律值。通过单击现场查看日期。单击后退或前进，以改变当前的日期，点击今天显示今天的价值观。',
		'ja' => '現在の日付のためのバイオリズム値を表示します。より多くのバイオリズムの値を表示するためにボタンを表示二次リズムを押します。フィールドビューの日付をクリックして日付を選択してください。 、現在の日付を変更するには、戻るまたは進むをクリックして今日をクリックすることで、今日の値を表示。'
	),
	'biorhythm' => array(
		'vi' => 'nhịp sinh học',
		'en' => 'biorhythm',
		'ru' => 'биоритм',
		'es' => 'biorritmo',
		'zh' => '生理节律',
		'ja' => 'バイオリズム'
	),
	'definition' => array(
		'vi' => 'Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh.',
		'en' => 'A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm"[2]) is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.',
		'ru' => 'Биологи́ческие ри́тмы — (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях ее организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе.',
		'es' => 'Los biorritmos constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.',
		'zh' => '生理节律是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。',
		'ja' => 'バイオリズム（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。'
	),
	'wiki' => array(
		'vi' => 'http://vi.wikipedia.org/wiki/Nh%E1%BB%8Bp_sinh_h%E1%BB%8Dc',
		'en' => 'http://en.wikipedia.org/wiki/Biorhythm',
		'ru' => 'https://ru.wikipedia.org/wiki/%D0%91%D0%B8%D0%BE%D1%80%D0%B8%D1%82%D0%BC',
		'es' => 'http://es.wikipedia.org/wiki/Biorritmo',
		'zh' => 'http://zh.wikipedia.org/wiki/%E7%94%9F%E7%90%86%E8%8A%82%E5%BE%8B',
		'ja' => 'http://ja.wikipedia.org/wiki/%E3%83%90%E3%82%A4%E3%82%AA%E3%83%AA%E3%82%BA%E3%83%A0'
	),
	'life_path_number_prefix' => array(
		'vi' => 'http://nhipsinhhoc.vn/wiki/Con_s%E1%BB%91_cu%E1%BB%99c_%C4%91%E1%BB%9Di_',
		'en' => 'http://www.astrology-numerology.com/num-lifepath.html#lp',
		'ru' => 'http://www.astroland.ru/numerology/lw/lifeway',
		'es' => 'http://www.horoscopius.es/numerologia/perfil-numerologico-para-el-numero-',
		'zh' => 'http://nhipsinhhoc.vn/blog/life-path-number-',
		'ja' => 'http://www.heavenlyblue.jp/num/'
	),
	'life_path_number_suffix' => array(
		'vi' => '',
		'en' => "",
		'ru' => '.htm',
		'es' => '/',
		'zh' => '/',
		'ja' => '.html'
	),
	'youtube_id' => array(
		'vi' => '0od3PsgixvQ',
		'en' => '7dRGGRcvI0E',
		'ru' => 'rp8_cTRP4ro',
		'es' => 'sifJsC3v-Lw',
		'zh' => 'TG2ngtokaVc',
		'ja' => 'SJw7lMuKipc'
	)
);
$information_interfaces = array(
	'average' => array(
		'vi' => array(
			'excellent' => 'Xin chúc mừng bạn. Ngày hiện tại của bạn rất tốt, bạn nên tận hưởng ngày này.',
			'good' => 'Chúc mừng bạn. Ngày hiện tại của bạn khá tốt, tuy nhiên bạn không nên chủ quan trong ngày này.',
			'gray' => 'Chúc bạn một ngày tốt lành. Ngày hiện tại của bạn không được tốt lắm, bạn nên cẩn trọng hơn.',
			'bad' => 'Chúc bạn một ngày vui vẻ. Rất tiếc phải thông báo rằng ngày hiện tại của bạn hơi xấu, mong bạn cẩn thận.'
		),
		'en' => array(
			'excellent' => 'Your current day is excellent, enjoy it.',
			'good' => 'Your current day is quite good, take a little care.',
			'gray' => 'Your current day is not good, take more care of yourself.',
			'bad' => 'Your current day is bad, should take a lot of care.'
		),
		'ru' => array(
			'excellent' => 'Ваш текущий день отлично, наслаждаться ею.',
			'good' => 'Ваш текущий день является достаточно хорошим, возьмите немного заботы.',
			'gray' => 'Ваш текущий день не очень хорошо, больше заботиться о себе.',
			'bad' => 'Ваш текущий день плохо, должно занять много ухода.'
		),
		'es' => array(
			'excellent' => 'Su día actual es excelente, que lo disfruten.',
			'good' => 'Su día actual es bastante buena, tomar un poco de cuidado.',
			'gray' => 'Su día actual no es bueno, tener más cuidado de ti mismo.',
			'bad' => 'Su día actual es mala, hay que tener mucho cuidado.'
		),
		'zh' => array(
			'excellent' => '您当前的一天是优秀的，享受它。',
			'good' => '您当前的一天是相当不错的，需要一点点的关心。',
			'gray' => '您当前的日子是不好的，把自己的更多的关怀。',
			'bad' => '您当前的日子是不好的，应该采取大量的关怀。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の日が優れている、それを楽しむ。',
			'good' => '現在の日はかなり良いです、少し注意してください。',
			'gray' => '現在の日はよくない、自分のことをより多くの世話をする。',
			'bad' => 'あなたの現在の日が悪い、介護の多くを取る必要があります。'
		)
	),
	'physical' => array(
		'vi' => array(
			'excellent' => 'Sức khỏe hiện tại của bạn rất sung mãn, hãy tham gia vận động nhiều hơn, như vận động thể dục, thể thao, tham gia các cuộc vui, để tận dụng năng lượng nhé. Sức đề kháng của bạn rất cao nên đây có thể là lúc phát bệnh mà bạn đã ủ trong suốt thời gian vừa qua.',
			'good' => 'Sức khỏe hiện tại của bạn khá sung mãn, hãy vận động điều độ, như các hoạt động thể dục nhẹ nhàng nha bạn.',
			'critical' => 'Sức khỏe hiện tại của bạn đang rơi vào giai đoạn chuyển tiếp, bạn nên nghỉ ngơi nhiều lên nhé, do thể lực bạn đang biến đổi khó lường.',
			'gray' => 'Sức khỏe hiện tại của bạn hơi kém, hãy nghỉ ngơi một tí, do thể lực đã ở vào mức khá thấp, hãy tích trữ năng lượng để dành vào những lúc sung mãn nha.',
			'bad' => 'Sức khỏe hiện tại của bạn khá kém, hãy nghỉ ngơi nhiều hơn, bạn đã hoạt động nhiều rồi, thời gian này nên dành để ngủ đông nhé. Sức đề kháng của bạn lúc này khá kém nên đây có thể là thời gian ủ bệnh.'
		),
		'en' => array(
			'excellent' => 'Your current health is excellent, you should work out more.',
			'good' => 'Your current health is quite good, you should work out with care.',
			'critical' => 'Your current health is in critical period, you should be extremely careful.',
			'gray' => 'Your current health is not good, take a little rest.',
			'bad' => 'Your current health is bad, take more rest.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее здоровье отличное, вы должны работать больше.',
			'good' => 'Ваше текущее здоровье неплохое, вы должны работать с осторожностью.',
			'critical' => 'Ваше текущее здоровье в критический период, вы должны быть очень осторожны.',
			'gray' => 'Ваше текущее здоровье не хорошо, немного отдохнуть.',
			'bad' => 'Ваше текущее здоровье плохо, взять больше отдыхать.'
		),
		'es' => array(
			'excellent' => 'Su estado de salud actual es excelente, debe trabajar más.',
			'good' => 'Su estado de salud actual es bastante bueno, usted debe hacer ejercicio con cuidado.',
			'critical' => 'Su salud actual está en el período crítico , debe ser extremadamente cuidadoso.',
			'gray' => 'Su estado de salud actual no es buena, tomar un poco de descanso.',
			'bad' => 'Su estado de salud actual es mala, tener más descanso.'
		),
		'zh' => array(
			'excellent' => '您当前的健康是优秀的，你应该更多。',
			'good' => '您当前的健康是相当不错的，你应该制定出谨慎。',
			'critical' => '您当前的健康是关键时期，你应该非常小心。',
			'gray' => '你目前的身体不好，需要一点休息。',
			'bad' => '您当前的健康是不好的，需要更多的休息。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の健康状態が優れている、あなたはより多くを動作するはずです。',
			'good' => 'あなたの現在の健康状態はかなり良いですが、あなたが注意して動作するはずです。',
			'critical' => 'あなたの現在の健康状態は、臨界期に、あなたは非常に慎重であるべきです。',
			'gray' => 'あなたの現在の健康状態が良くない、少し休憩を取る。',
			'bad' => 'あなたの現在の健康状態が悪いと、より多くの休息を取る。'
		)
	),
	'emotional' => array(
		'vi' => array(
			'excellent' => 'Tâm trạng hiện tại của bạn rất ổn, hãy tham gia gặp gỡ bạn bè nhiều hơn, dành thời gian hẹn hò, đi chơi với những người thân yêu của mình để tận dụng lúc cảm xúc đang thăng hoa bạn nhé.',
			'good' => 'Tâm trạng hiện tại của bạn khá ổn, hãy gặp gỡ bạn bè, người thân, nhưng cũng chú ý tránh những xung đột nhỏ để cho cuộc vui được trọn vẹn nha bạn.',
			'critical' => 'Tâm trạng hiện tại của bạn đang rơi vào giai đoạn chuyển giao, hãy chú ý nhiều đến cảm xúc của mình, do đây là lúc cảm xúc thay đổi khó lường.',
			'gray' => 'Tâm trạng hiện tại của bạn hơi tệ, bạn hơi dễ cáu kỉnh, dễ cãi nhau, vì thế, bạn nên tìm đến những góc nhỏ cho riêng mình, để tĩnh tâm lại bạn nhé.',
			'bad' => 'Tâm trạng hiện tại của bạn khá tệ, bạn nên tránh các cuộc xung đột, cãi vã, vì lúc này điều đó rất dễ xảy ra. Bạn nên dành thời gian ở một mình, khoảng thời gian này sẽ qua mau thôi.'
		),
		'en' => array(
			'excellent' => 'Your current mood is excellent, you meet more friends.',
			'good' => 'Your current mood is quite good, you should meet some friends.',
			'critical' => 'Your current mood is in critical period, you should pay more attention to your feelings.',
			'gray' => 'Your current mood is not good, you are easily annoyed.',
			'bad' => 'Your current mood is bad, avoid conflicts.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее настроение отличное, вы встретите больше друзей.',
			'good' => 'Ваше текущее настроение неплохое, вы должны встретиться с друзьями.',
			'critical' => 'Ваше текущее настроение в критический период, вы должны уделять больше внимания на ваши чувства.',
			'gray' => 'Ваше текущее настроение не очень хорошо, вы легко раздражаться.',
			'bad' => 'Ваше текущее настроение плохое, во избежание конфликтов.'
		),
		'es' => array(
			'excellent' => 'Su estado de ánimo actual es excelente, te encuentras con más amigos.',
			'good' => 'Su estado de ánimo actual es bastante buena, usted debe cumplir con algunos amigos.',
			'critical' => 'Su estado de ánimo actual está en el período crítico, se debe prestar más atención a sus sentimientos.',
			'gray' => 'Su estado de ánimo actual no es bueno, ustedes son fácilmente molesto.',
			'bad' => 'Su estado de ánimo actual es mala, evitar conflictos.'
		),
		'zh' => array(
			'excellent' => '你现在的心情非常好，你认识更多的朋友。',
			'good' => '你现在的心情是相当不错的，你应该满足一些朋友。',
			'critical' => '您现在的心情是关键时期，你应该更加注意你的感受。',
			'gray' => '你现在的心情不是很好，你很容易生气。',
			'bad' => '你现在的心情不好，避免冲突。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の気分が優れている、あなたはより多くの友人に会う。',
			'good' => 'あなたの現在の気分はかなり良いですが、あなたは何人かの友人を満たしている必要があります。',
			'critical' => 'あなたの現在の気分は、臨界期に、あなたはあなたの気持ちにもっと注意を払う必要があります。',
			'gray' => 'あなたの現在の気分が良くない、あなたは簡単にイライラです。',
			'bad' => 'あなたの現在の気分が悪い、競合を避ける。'
		)
	),
	'intellectual' => array(
		'vi' => array(
			'excellent' => 'Trí tuệ hiện tại của bạn rất sáng suốt, bạn có thể đưa ra những quyết định đúng đắn, có những suy nghĩ rất chính xác, hợp lý.',
			'good' => 'Trí tuệ hiện tại của bạn khá sáng suốt, bạn có thể đưa ra quyết định nhưng cần suy tính kỹ, bởi vì suy nghĩ của bạn chưa đạt độ chính xác cao nhất có thể.',
			'critical' => 'Trí tuệ hiện tại của bạn đang ở trong giai đoạn chuyển biến, bạn nên chú ý kỹ hơn đến suy nghĩ của mình, vì nó có thể dẫn đến những quyết định sai lầm.',
			'gray' => 'Trí tuệ hiện tại của bạn hơi thiếu sáng suốt, bạn nên suy nghĩ kỹ trước khi ra quyết định. Nếu cần thiết, hãy hỏi thêm ý kiến của người thân, bạn bè, đồng nghiệp.',
			'bad' => 'Trí tuệ hiện tại của bạn khá thiếu sáng suốt, bạn không nên đưa ra quyết định lớn. Nếu phải ra quyết định, bạn nhất định nên hỏi ý kiến người khác.'
		),
		'en' => array(
			'excellent' => 'Your current intellect is excellent, you can make great decisions.',
			'good' => 'Your current intellect is quite good, you can make decisions with a little care.',
			'critical' => 'Your current intellect is in critical period, you should pay extra attention to your thoughts, as it may lead to wrong decisions.',
			'gray' => 'Your current intellect is not good, you should think twice before making decisions.',
			'bad' => 'Your current intellect is bad, you should not make big decisions.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее интеллект отлично, вы можете сделать большие решения.',
			'good' => 'Ваше текущее интеллект является достаточно хорошим, вы можете принимать решения с особого ухода.',
			'critical' => 'Ваше текущее интеллект в критический период, следует обратить особое внимание на ваши мысли, так как это может привести к неправильным решениям.',
			'gray' => 'Ваше текущее интеллект не является хорошим, вы должны подумать дважды, прежде чем принимать решения.',
			'bad' => 'Ваше текущее интеллект плохо, вы не должны делать большие решения.'
		),
		'es' => array(
			'excellent' => 'Su intelecto actual es excelente, puedes tomar grandes decisiones.',
			'good' => 'Su intelecto actual es bastante buena, se puede tomar decisiones con un poco de cuidado.',
			'critical' => 'Su intelecto actual está en período crítico, se debe prestar especial atención a sus pensamientos, ya que puede conducir a decisiones equivocadas.',
			'gray' => 'Su intelecto actual no es buena, usted debe pensar dos veces antes de tomar decisiones.',
			'bad' => 'Su intelecto actual es mala, no debe tomar decisiones importantes.'
		),
		'zh' => array(
			'excellent' => '您当前的智力是优秀的，你可以做出伟大的决定。',
			'good' => '您当前的智力是相当不错的，你可以用一点点小心做出决定。',
			'critical' => '您当前的智力是关键时期，你要格外注意你的想法，因为这可能会导致错误的决策。',
			'gray' => '您当前的智力不好，你做决策前，应三思而后行。',
			'bad' => '您当前的智力是坏的，你不应该做出重大的决定。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の知性は、あなたは偉大な決定を行うことができ、優れたものである。',
			'good' => 'あなたの現在の知性はかなり良いですが、あなたは少し注意して意思決定を行うことができます。',
			'critical' => 'それは間違った意思決定につながる可能性としてあなたの現在の知性は、臨界期に、あなたは、あなたの考えに特別な注意を払う必要がありますされています。',
			'gray' => 'あなたの現在の知性はあなたが意思決定をする前に二度考える必要があり、良いではありません。',
			'bad' => 'あなたの現在の知性は、あなたは大きな意思決定を行うべきではない、悪いです。'
		)
	)
);
$explanation_interfaces = array(
	'vi' => '<section id="explanation_vi">
		<h2 class="explain">Bạn có biết?</h2>
		<p class="explain"><strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['vi'].'"><span data-title="Nhịp sinh học">Nhịp sinh học</span></a></strong> (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh. Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.</p>
		<h3 class="explain">Ba đường <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['vi'].'"><span data-title="nhịp sinh học">nhịp sinh học</span></a></strong> chính là:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'vi').'</strong>: Đường này có chu kỳ 23 ngày<span class="explain_more"> và nó theo dõi tình trạng thể chất và sức khỏe. Chỉ số cao thể hiện sức đề kháng tăng lên, do đó bạn có xu hướng phát bệnh ra. Ngược lại khi chỉ số thấp nghĩa là bạn đang ủ bệnh trong người.</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'vi').'</strong>: Đường này có chu kỳ 28 ngày<span class="explain_more"> và nó theo dõi năng lượng ổn định và tích cực của tinh thần và cách nhìn về cuộc sống, cũng như năng lực của bạn để cảm thông và xây dựng mối quan hệ với những người khác</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'vi').'</strong>: Đường này có chu kỳ 33 ngày<span class="explain_more"> và nó theo dõi, bằng lời nói, khả năng toán học của bạn, khả năng tưởng tượng, và sáng tạo, cũng như năng lực của bạn để áp dụng lý trí và phân tích với thế giới xung quanh bạn</span>.</p>
		<h3 class="explain">Bốn đường <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['vi'].'"><span data-title="nhịp sinh học">nhịp sinh học</span></a></strong> phụ là:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'vi').'</strong>: Đường này có chu kỳ 38 ngày<span class="explain_more"> và nó ảnh hưởng đến nhận thức, linh cảm, bản năng và "giác quan thứ sáu"</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'vi').'</strong>: Đường này có chu kỳ 43 ngày<span class="explain_more"> và nó mô tả sự quan tâm đến cái đẹp và sự hài hòa</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'vi').'</strong>: Đường này có chu kỳ 48 ngày<span class="explain_more"> và nó thể hiện khả năng cảm nhận được cá tính riêng</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'vi').'</strong>: Đường này có chu kỳ 53 ngày<span class="explain_more"> và nó mô tả sự ổn định bên trong và thái độ thoải mái của bạn</span>.</p>
	</section>',
	'en' => '<section id="explanation_en">
		<h2 class="explain">Do you know?</h2>
		<p class="explain">A <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['en'].'"><span data-title="biorhythm">biorhythm</span></a></strong> (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm") is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.</p>
		<h3 class="explain">The three primary <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['en'].'"><span data-title="biorhythm">biorhythm</span></a></strong> cycles are:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'en').'</strong>: This cycle is 23 days<span class="explain_more"> and tracks your strength, health, and raw physical vitality</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'en').'</strong>: This cycle is 28 days<span class="explain_more"> and tracks the stability and positive energy of your psyche and outlook on life, as well as your capacity to empathize with and build rapport with other people</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'en').'</strong>: This cycle is 33 days<span class="explain_more"> and tracks your verbal, mathematical, symbolic, and creative abilities, as well as your capacity to apply reason and analysis to the world around you</span>.</p>
		<h3 class="explain">The four secondary <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['en'].'"><span data-title="biorhythm">biorhythm</span></a></strong> cycles are:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'en').'</strong>: This cycle is 38 days<span class="explain_more"> and influences unconscious perception, hunches, instincts and \'sixth sense\'</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'en').'</strong>: This cycle is 43 days<span class="explain_more"> and describes interest in the beautiful and the harmonious</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'en').'</strong>: This cycle is 48 days<span class="explain_more"> and expresses ability to perceive own personality and individuality</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'en').'</strong>: This cycle is 53 days<span class="explain_more"> and describes inner stability and relaxed attitude</span>.</p>
	</section>',
	'ru' => '<section id="explanation_ru">
		<h2 class="explain">Знаете ли вы?</h2>
		<p class="explain"><strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ru'].'"><span data-title="Биоритм">Биоритм</span></a></strong> - (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях ее организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе. Одни биологические ритмы относительно самостоятельны (например, частота сокращений сердца, дыхания), другие связаны с приспособлением организмов к геофизическим циклам — суточным (например, колебания интенсивности деления клеток, обмена веществ, двигательной активности животных), приливным (например, открывание и закрывание раковин у морских моллюсков, связанные с уровнем морских приливов), годичным (изменение численности и активности животных, роста и развития растений и др.)</p>
		<h3 class="explain">Три основных <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ru'].'"><span data-title="биоритм">биоритм</span></a></strong> циклы:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'ru').'</strong>: Этот цикл составляет 23 дней<span class="explain_more"> и отслеживает ваши силы, здоровье и сырой физическую жизнеспособность</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'ru').'</strong>: Этот цикл составляет 28 дней<span class="explain_more"> и отслеживает стабильность и положительную энергию вашей психики и взглядом на жизнь, а также свою способность сопереживать и наладить контакт с другими людьми</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'ru').'</strong>: Этот цикл составляет 33 дней<span class="explain_more"> и отслеживает ваши словесные, математические, символические, и творческие способности, а также ваша способность применять разум и анализ, чтобы мир вокруг вас</span>.</p>
		<h3 class="explain">Четыре вторичный <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ru'].'"><span data-title="биоритм">биоритм</span></a></strong> циклы:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'ru').'</strong>: Этот цикл составляет 38 дней<span class="explain_more"> и влияет на бессознательное восприятие, догадки, инстинкты и «шестое чувство»</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'ru').'</strong>: Этот цикл составляет 43 дней<span class="explain_more"> и описывает интерес к красивым и гармоничным</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'ru').'</strong>: Этот цикл составляет 48 дней<span class="explain_more"> и выражает способность воспринимать собственную индивидуальность и индивидуальность</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'ru').'</strong>: Этот цикл составляет 53 дней<span class="explain_more"> и описывает внутреннюю стабильность и спокойное отношение</span>.</p>
	</section>',
	'es' => '<section id="explanation_es">
		<h2 class="explain">¿Sabe usted?</h2>
		<p class="explain">Los <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['es'].'"><span data-title="biorritmos">biorritmos</span></a></strong> constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.</p>
		<h3 class="explain">Las tres principales ciclos <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['es'].'"><span data-title="biorritmo">biorritmo</span></a></strong>:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'es').'</strong>: Este ciclo es de 23 días<span class="explain_more"> y realiza un seguimiento de su fortaleza, de salud y vitalidad física bruta</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'es').'</strong>: Este ciclo es de 28 días<span class="explain_more"> y las vías la estabilidad y energía positiva de su psique y la perspectiva de la vida, así como su capacidad de empatía y construir una relación con otras personas</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'es').'</strong>: Este ciclo es de 33 días<span class="explain_more"> y realiza un seguimiento de los verbales, matemáticas, simbólico, y su capacidad creativa, así como su capacidad para aplicar razón y análisis para el mundo que le rodea</span>.</p>
		<h3 class="explain">Los cuatro secundario ciclos <strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['es'].'"><span data-title="biorritmo">biorritmo</span></a></strong>:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'es').'</strong>: Este ciclo es de 38 días<span class="explain_more"> y la influencia inconsciente percepción, presentimientos, instintos y "sexto sentido"</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'es').'</strong>: Este ciclo es de 43 días<span class="explain_more">, y se describe en el hermoso y la armonía</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'es').'</strong>: Este ciclo es de 48 días<span class="explain_more">, y expresa la capacidad de percibir la propia personalidad e individualidad</span>.</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'es').'</strong>: Este ciclo es de 53 días<span class="explain_more"> y describe estabilidad interna y actitud relajada</span>.</p>
	</section>',
	'zh' => '<section id="explanation_zh">
		<h2 class="explain">你知道吗?</h2>
		<p class="explain"><strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['zh'].'"><span data-title="生理节律">生理节律</span></a></strong>是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。</p>
		<h3 class="explain">三初级<strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['zh'].'"><span data-title="生理节律">生理节律</span></a></strong>周期是:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'zh').'</strong>: 这个周期是23天<span class="explain_more">，跟踪你的力量，健康和原始物理活力</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'zh').'</strong>: 这个周期是28天<span class="explain_more">，并追踪你的心灵和人生观的稳定和积极的能量，以及你的能力，以同情，并与其他人建立关系</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'zh').'</strong>: 这个周期是33天<span class="explain_more">，跟踪您的语言，数学，符号和创造能力，以及你的能力，以申请理由和分析你周围的世界</span>。</p>
		<h3 class="explain">四第二性<strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['zh'].'"><span data-title="生理节律">生理节律</span></a></strong>周期是:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'zh').'</strong>: 这个周期为38天<span class="explain_more">，影响无意识知觉，直觉，本能和\'第六感\'</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'zh').'</strong>: 这个周期为43天<span class="explain_more">，说明在美丽的兴趣与和谐</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'zh').'</strong>: 这个周期为48天<span class="explain_more">，表现为感知自己的人格和个性的能力</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'zh').'</strong>: 这个周期为53天<span class="explain_more">，说明内部的稳定和轻松的态度</span>。</p>
	</section>',
	'ja' => '<section id="explanation_ja">
		<h2 class="explain">あなたが知っていますか</h2>
		<p class="explain"><strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ja'].'"><span data-title="バイオリズム">バイオリズム</span></a></strong>（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。</p>
		<h3 class="explain">三つ一次の<strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ja'].'"><span data-title="バイオリズム">バイオリズム</span></a></strong>サイクルおいでになる:</h3>
		<p class="explain"><strong>'.get_rhythm_title(1,'ja').'</strong>: このサイクルは23日<span class="explain_more">、あなたの強さ、健康、およびひりひりする物理活力トラック</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(2,'ja').'</strong>: このサイクルは28日<span class="explain_more">、安定性と生命のあなたの精神および見込みの肯定的なエネルギー、トラックに共感し、親密な関係の他の人々を、容量、</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(3,'ja').'</strong>: このサイクルは33日<span class="explain_more">あり、あなたの、言葉の数学的、象徴的、そして創造力だけでなく、あなたの周りの世界にその理由と分析を適用するためにあなたの能力を追跡します</span>。</p>
		<h3 class="explain">四セカンダリ・<strong><a target="_blank" class="rotate" href="'.$help_interfaces['wiki']['ja'].'"><span data-title="バイオリズム">バイオリズム</span></a></strong>サイクルおいでになる:</h3>
		<p class="explain"><strong>'.get_rhythm_title(4,'ja').'</strong>: このサイクルは38日<span class="explain_more">、意識に対する認識は、直感や本能と第六感に影響します</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(5,'ja').'</strong>: このサイクルは43日<span class="explain_more">、美しいとは、調和で金利について説明します</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(6,'ja').'</strong>: このサイクルは48日<span class="explain_more">、自分自身の人格と個性を知覚する能力を表現しています</span>。</p>
		<p class="explain"><strong>'.get_rhythm_title(7,'ja').'</strong>: このサイクルは53日<span class="explain_more">、内部安定性とリラックスした姿勢について説明します</span>。</p>
	</section>'
);
$introduction_interfaces = array(
	'vi' => '<section id="introduction_vi">
		<p>Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh.</p>
		<p>Cụ thể hơn, lấy một ví dụ, người ta cho rằng có thời điểm một người rất dễ mắc bệnh, còn có lúc khác thì không. Các thời điểm này cứ lặp đi lặp lại rất nhiều lần và có quy luật. Quy luật đó gọi là nhịp sinh học. Và chúng sẽ dao động đều trong khoảng -100% đến 100% trong đồ thị nhịp sinh học (số càng lớn thì càng mạnh).</p>
		<p>Cũng bởi vì vậy nên có rất nhiều lý thuyết cũng như nhiều loại nhịp sinh học khác nhau. Không có gì đảm bảo những loại nhịp sinh học này là chính xác, bởi vì bản thân con người luôn chịu nhiều tác động từ môi trường, và đời sống xã hội. Tuy nhiên, rất nhiều nhà khoa học công nhận 3 loại nhịp sinh học cơ bản là: Sức khỏe (Physical), Tình cảm (Emotional) và Trí tuệ (Intellectual).</p>
		<h3>Các nhịp sinh học cơ bản</h3>
		<p>Biểu đồ nhịp sinh học với 3 đường cơ bản và một đường bổ sung, với trục ngang chỉ thời gian, chính giữa là ngày hiện tại.</p>
		<p>Lý thuyết cổ điển của nhịp sinh học gắn liền với Hermann Swoboda ở đầu thế kỷ 20, ông được cho là người đưa ra chu trình 23 ngày cho nhịp sức khỏe và 28 ngày cho nhịp tình cảm.</p>
		<p>Năm 1920, Alfred Teltschercho rằng chu trình của trí thông minh là 33 ngày.</p>
		<p>Chúng ta sẽ thấy một chuỗi số thú vị: 23-28-33, và số tiếp theo là 38 được cho là chu trình của trực giác.</p>
		<h3>Công thức tính toán</h3>
		<p>Do có chu trình đều và lặp lại, với mốc thời gian là ngày sinh, hoàn toàn dễ hiểu với các hàm số sau:</p>
		<ul>
			<li>Sức khỏe: sin(2π t/23)</li>
			<li>Tình cảm: sin(2π t/28)</li>
			<li>Trí tuệ: sin(2π t/33)</li>
			<li>Trực giác: sin(2π t/38)</li>
			<li>Thẩm mỹ: sin(2π t/43)</li>
			<li>Nhận thức: sin(2π t/48)</li>
			<li>Tinh thần: sin(2π t/53)</li>
		</ul>
		<p>Với t là thời gian tính từ khi người đó được sinh ra.</p>
		<h3>Ứng dụng</h3>
		<p>Đây là ứng dụng tính toán nhịp sinh học của cơ thể bạn về các mặt:</p>
		<ul>
			<li>Sức khỏe: thể lực, sức mạnh, sự phối hợp các cơ quan trong cơ thể và nó theo dõi tình trạng thể chất và sức khỏe.</li>
			<li>Tình cảm: sự sáng tạo, nhạy cảm, tâm trạng và nhận thức.</li>
			<li>Trí tuệ: sự tỉnh táo, phân tích hoạt động, phân tích vấn đề, bộ nhớ, tiếp nhận thông tin.</li>
			<li>Trực giác: khả năng nhận biết, cảm nhận.</li>
			<li>Thẩm mỹ: sự nhạy cảm về mặt thẩm mỹ, thẩm mỹ của bản thân.</li>
			<li>Nhận thức: thể hiện khả năng cảm nhận được cá tính riêng.</li>
			<li>Tinh thần: vấn đề tâm linh, quan niệm và các hiện tượng thần bí.</li>
		</ul>
		<p>Thực tế đã chứng minh khi chỉ số thấp:</p>
		<ul>
			<li>Đối với chu kỳ tình cảm, thường buồn bực vô cớ.</li>
			<li>Đối với chu kỳ trí tuệ, đó là ngày đãng trí, khả năng tư duy kém.</li>
			<li>Đặc biệt đối với chu kỳ sức khỏe, đó là ngày thường xảy ra tai nạn lao động.</li>
			<li>Đối với hai chu kỳ, số ngày chuyển tiếp trùng nhau chỉ xảy ra một lần trong một năm.</li>
			<li>Ngày trùng hợp đó của ba chu kỳ là ngày xấu nhất, có thể coi là ngày "vận hạn" của mỗi người.</li>
		</ul>
		<p>Việc nghiên cứu về nhịp sinh học thu hút sự quan tâm của rất nhiều nhà khoa học trên thế giới, họ đã nghiên cứu trong nhiều năm.</p>
		<p>Ở các nước phát triển như Nhật, Mỹ… Nhịp sinh học được áp dụng nhiều trong việc sử dụng con người, các phi công, kỹ sư trong hàng không, vũ trụ sẽ được nghỉ ngơi vào thời gian họ có nhịp sinh học nhỏ hơn 50%. Các nhà khoa học cũng tìm ra mối liên hệ giữa nhịp sinh học với tai nạn lao động, và các hiện tượng xảy ra trong đời sống của con người.</p>
		<p>Ngày nay, môn khoa học này đã phát triển và nhịp sinh học là khái niệm được dùng để chỉ khuynh hướng riêng biệt của mỗi người theo từng ngày. Nhịp sinh học đo xu hướng này dựa vào ngày sinh của mỗi người. Nhịp sinh học tính toán các chu kỳ ứng với các khía cạnh khác nhau của con người. Có 3 chu kỳ sinh học cơ bản: sức khỏe, tình cảm và trí tuệ và 4 chu kỳ phụ: trực giác, thẩm mỹ, tinh thần, nhận thức. Chúng hoạt động theo mô hình: bắt đầu bằng 50% vào lúc sinh của bạn và tăng dần, Sau khi đạt đến mức tối đa 100%, chu kỳ giảm xuống tới 0%. Sau đó, chiều hướng sẽ thay đổi và chu kỳ lại chuyển động về phía trên.</p>
		<p>Các ứng dụng về Nhịp sinh học có rất nhiều, trong đó cụ thể nhất là việc phòng tránh tai nạn lao động. Một nghiên cứu của tiến sỹ Toán – Lý người Nga Serik Mazhkenov tiến hành tại một công ty thăm dò dầu khí cho thấy 70% số vụ tai nạn lao động xảy ra với nhân công trùng với những ngày mà một trong 3 nhịp sinh học chính của người đó tiệm cận với mức 0. Và từ đó đưa ra phương hướng giải quyết để giảm tới 29% vụ tai nạn thường gặp.</p>
		<p>Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.</p>
		<p>Với ứng dụng hàng ngày, bạn nên xem qua các đường sinh học của mình để có những biện pháp đối phó phù hợp. Ví như nếu nhịp sinh học về cảm xúc ở mức thấp thì bạn rất dễ bị bực bội, cáu kỉnh. Khi đó, nên tìm những không gian thoáng đãng, vui tươi hay trò chuyện với những người vui vẻ. Hoặc nếu Nhịp sinh học Sức khỏe giảm sút, bạn chớ nên làm những việc nặng nhọc hay đòi hỏi thời gian làm kéo dài.</p>
	</section>',
	'en' => '<section id="introduction_en">
		<p>A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm") is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.</p>
		<p>According to the theory of biorhythms, a person\'s life is influenced by rhythmic biological cycles that affect his or her ability in various domains, such as mental, physical and emotional activity. These cycles begin at birth and oscillate in a steady (sine wave) fashion throughout life, and by modeling them mathematically, it is suggested that a person\'s level of ability in each of these domains can be predicted from day to day. The theory is built on the idea that the biofeedback chemical and hormonal secretion functions within the body could show a sinusoidal behavior over time.</p>
		<p>Most biorhythm models use three cycles: a 23-day physical cycle, a 28-day emotional cycle, and a 33-day intellectual cycle. Although the 28-day cycle is the same length as the average woman\'s menstrual cycle and was originally described as a "female" cycle (see below), the two are not necessarily in any particular synchronization. Each of these cycles varies between high and low extremes sinusoidally, with days where the cycle crosses the zero line described as "critical days" of greater risk or uncertainty.</p>
		<p>In addition to the three popular cycles, various other cycles have been proposed, based on linear combination of the three, or on longer or shorter rhythms.</p>
		<h3>Calculation</h3>
		<p>The equations for the cycles are:</p>
		<ul>
			<li>Physical: sin(2π t/23)</li>
			<li>Emotional: sin(2π t/28)</li>
			<li>Intellectual: sin(2π t/33)</li>
		</ul>
		<p>Where t indicates the number of days since birth.</p>
		<p>Basic arithmetic shows that the simpler 23- and 28-day cycles repeats every 644 days (or 1-3/4 years), while the triple 23-, 28-, and 33-day cycles repeats every 21,252 days (or 58.2+ years).</p>
		<h3>History</h3>
		<p>The notion of periodic cycles in human fortunes is ancient; for instance, it is found in natal astrology and in folk beliefs about "lucky days". The 23- and 28-day rhythms used by biorhythmists, however, were first devised in the late 19th century by Wilhelm Fliess, a Berlin physician and patient of Sigmund Freud. Fliess believed that he observed regularities at 23- and 28-day intervals in a number of phenomena, including births and deaths. He labeled the 23-day rhythm "male" and the 28-day rhythm "female", matching the menstrual cycle.</p>
		<p>In 1904, psychology professor Hermann Swoboda claimed to have independently discovered the same cycles. Later, Alfred Teltscher, professor of engineering at the University of Innsbruck, came to the conclusion that his students\' good and bad days followed a rhythmic pattern of 33 days. Teltscher believed that the brain\'s ability to absorb, mental ability, and alertness ran in 33-day cycles. One of the first academic researchers of biorhythms was also Estonian-born Nikolai Pärna, who published a book in German called Rhythm, Life and Creation in 1923.</p>
		<p>The practice of consulting biorhythms was popularized in the 1970s by a series of books by Bernard Gittelson, including Biorhythm — A Personal Science, Biorhythm Charts of the Famous and Infamous, and Biorhythm Sports Forecasting. Gittelson\'s company, Biorhythm Computers, Inc., made a business selling personal biorhythm charts and calculators, but his ability to predict sporting events was not substantiated.</p>
		<p>Charting biorhythms for personal use was popular in the United States during the 1970s; many places (especially video arcades and amusement areas) had a biorhythm machine that provided charts upon entry of date of birth. Biorhythm charts were common in newspapers, usually found with horoscopes, at the time as well. Biorhythm programs were a common application on personal computers; and in the late 1970s, there were also handheld biorhythm calculators on the market, the Kosmos 1 and the Casio Biolator. Though biorhythms have declined in popularity, there are numerous websites on the Internet that offer free biorhythm readings. In addition, there exist free and proprietary software programs that offer more advanced charting and analysis capabilities.</p>
	</section>',
	'ru' => '<section id="introduction_ru">
		<p>Биологи́ческие ри́тмы — (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях её организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе. Одни биологические ритмы относительно самостоятельны (например, частота сокращений сердца, дыхания), другие связаны с приспособлением организмов к геофизическим циклам — суточным (например, колебания интенсивности деления клеток, обмена веществ, двигательной активности животных), приливным (например, открывание и закрывание раковин у морских моллюсков, связанные с уровнем морских приливов), годичным (изменение численности и активности животных, роста и развития растений и др.)</p>
		<p>Наука, изучающая роль фактора времени в осуществлении биологических явлений и в поведении живых систем, временнýю организацию биологических систем, природу, условия возникновения и значение биоритмов для организмов называется — биоритмология. Биоритмология является одним из направлений, сформировавшегося в 1960-е гг. раздела биологии — хронобиологии. На стыке биоритмологии и клинической медицины находится так называемая хрономедицина, изучающая взаимосвязи биоритмов с течением различных заболеваний, разрабатывающая схемы лечения и профилактики болезней с учетом биоритмов и исследующая другие медицинские аспекты биоритмов и их нарушений.</p>
		<p>Биоритмы подразделяются на физиологические и экологические. Физиологические ритмы, как правило, имеют периоды от долей секунды до нескольких минут. Это, например, ритмы давления, биения сердца и артериального давления. Экологические ритмы по длительности совпадают с каким-либо естественным ритмом окружающей среды.</p>
		<p>Биологические ритмы описаны на всех уровнях, начиная от простейших биологических реакций в клетке и кончая сложными поведенческими реакциями. Таким образом, живой организм является совокупностью многочисленных ритмов с разными характеристиками. По последним научным данным в организме человека выявлено около 400 суточных ритмов.</p>
		<p>Адаптация организмов к окружающей среде в процессе эволюционного развития шла в направлении как совершенствования их структурной организации, так и согласования во времени и пространстве деятельности различных функциональных систем. Исключительная стабильность периодичности изменения освещенности, температуры, влажности, геомагнитного поля и других параметров окружающей среды, обусловленных движением Земли и Луны вокруг Солнца, позволила живым системам в процессе эволюции выработать стабильные и устойчивые к внешним воздействиям временные программы, проявлением которых служат биоритмы. Такие ритмы, обозначаемые иногда как экологические, или адаптивные (например: суточные, приливные, лунные и годовые), закреплены в генетической структуре. В искусственных условиях, когда организм лишен информации о внешних природных изменениях (например, при непрерывном освещении или темноте, в помещении с поддерживаемыми на одном уровне влажностью, давлением и т. п.) периоды таких ритмов отклоняются от периодов соответствующих ритмов окружающей среды, проявляя тем самым свой собственный период.</p>
		<h3>Историческая справка</h3>
		<p>О существовании биологических ритмов людям известно с древних времен.</p>
		<p>Уже в «Ветхом Завете» даны указания о правильном образе жизни, питании, чередовании фаз активности и отдыха. О том же писали ученые древности: Гиппократ, Авиценна и другие.</p>
		<p>Основателем хронобиологии — науки о биоритмах, принято считать немецкого врача К. В. Гуфеланда, который в 1797 году обратил внимание коллег на универсальность ритмических процессов в биологии: каждый день жизнь повторяется в определенных ритмах, а суточный цикл, связанный с вращением Земли вокруг своей оси регулирует жизнедеятельность всего живого, включая организм человека.</p>
		<p>Первые систематические научные исследования в этой области начали проводиться в начале XX века, в том числе российскими учеными И. П. Павловым, В. И. Вернадским, А. Л. Чижевским и другими.</p>
		<p>К концу XX века факт ритмичности биологических процессов живых организмов по праву стал считаться одним из фундаментальных свойств живой материи и сущностью организации жизни. Но до последнего времени природа и все физиологические свойства биологических ритмов не выяснены, хотя понятно, что они имеют в процессах жизнедеятельности живых организмов очень большое значение.</p>
		<p>Поэтому исследования биоритмов пока представляют собой процесс накопления информации, выявления свойств и закономерностей методами статистики.</p>
		<p>В результате в науке о биоритмах возникло два научных направления: хронобиология и хрономедицина.</p>
		<p>Советские ученые Ф. И. Комаров и С. И. Рапопорт в своей книге «Хронобиология и хрономедицина» дают следующее определение биоритмов: «Ритм представляет собой характеристику периодической временной структуры. Ритмичность характеризует как определенный порядок временной последовательности, так и длительность отрезков времени, поскольку содержит чередование фаз различной продолжительности».</p>
		<p>Одной из основных работ в этой области можно считать разработанную хронобиологом Ф. Хальбергом (нем.)русск. в 1964 году классификацию биологических ритмов.</p>
		<p>По поводу природы биоритмов было высказано множество гипотез, производились многочисленные попытки определить ещё целый ряд новых закономерностей.</p>
		<p>Вот некоторые из них.</p>
		<p>Шведский исследователь Э. Форсгрен (E. Forsgren) в опытах на кроликах обнаружил суточный ритм гликогена и желчеобразования (1930).</p>
		<p>Советские ученые Н. Е. Введенский, А. А. Ухтомский, И. П. Павлов и В. В. Парин осуществили попытку теоретически обосновать механизмы возникновения ритмических процессов в нервной системе и показали, что колебания характеристик состояния нервной системы определяются прежде всего ритмами возбуждения и торможения.</p>
		<p>В 1959 году Юрген Ашофф (англ.)русск., впоследствии директор Планковского Института физиологии поведения (нем.)русск. в Андексе (Германия), обнаружил закономерность, которая была названа «правилом Ашоффа» (под этим названием оно вошло в хронобиологию и историю науки): «У ночных животных активный период (бодрствование) более продолжителен при постоянном освещении, в то время как у дневных животных бодрствование более продолжительно при постоянной темноте».</p>
		<p>Им было установлено, что при длительной изоляции человека и дневных животных в темноте, цикл «бодрствование-сон» удлиняется за счет увеличения продолжительности фазы бодрствования. Ю. Ашофф предположил, что именно свет стабилизирует циркадные ритмы организма.</p>
	</section>',
	'es' => '<section id="introduction_es">
		<p>Los biorritmos constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.</p>
		<p>Según los creyentes en los biorritmos, la vida de una persona se vería determinada por ciclos biológicos rítmicos que afectarían a la capacidad de cada individuo en distintos terrenos, como el mental, el físico o el de las emociones. Estos ciclos se iniciarían con el nacimiento y oscilarían de acuerdo a una onda senoidal durante toda la vida. De este modo, la capacidad de una persona en cada uno de estos terrenos podría predecirse día por día mediante un modelo matemático ad hoc.</p>
		<p>La mayoría de modelos que están basados en los biorritmos definen 3 ciclos: un ciclo "físico" de 23 días, otro ciclo "emocional" de 28 días y un ciclo "intelectual" de 33 días. Aunque el ciclo de 28 días duraría lo mismo que el ciclo menstrual medio de las mujeres y en principio se habría calificado como un ciclo "femenino" (véase más abajo), ambos ciclos no necesariamente estarían sincronizados. Cada uno de estos ciclos variaría sinusoidalmente entre dos extremos, alto y bajo. Los días en los que el ciclo cruzara el eje del cero constituirían una suerte de "días críticos" de mayor riesgo o incertidumbre.</p>
		<p>Además de los 3 ciclos que son más conocidos, se han propuesto otros ciclos basados en la combinación lineal de los 3 primeros o en ritmos de oscilación o bien más cortos, o bien más largos.</p>
		<h3>Cálculo</h3>
		<p>Las ecuaciones que regirían estos ciclos serían:</p>
		<ul>
			<li>ciclo físico: \sen(2π t/23)</li>
			<li>ciclo emocional: \sen(2π t/28)</li>
			<li>ciclo intelectual: \sen(2π t/33)</li>
		</ul>
		<p>Donde t indicaría el número de días transcurridos desde el nacimiento de la persona.</p>
		<p>Unos simples cálculos demuestran que el ciclo doble de 23 y 28 días se repite cada 644 días (o 1 3/4 años) mientras que el triple de 23, 28 y 33 días se repite cada 21 252 días (o 58.2+ años).</p>
		<h3>Historia</h3>
		<p>La idea de que hay ciclos periódicos que rigen el destino del hombre es de larga data y se encuentra implícita, por ejemplo, en la astrología natal así como en la creencia popular en los "días de la suerte". Sin embargo, los ciclos de 23 y 28 días que usan los biorritmistas surgen a finales del siglo XIX de la mano de Wilhelm Fliess, médico berlinés y también paciente de Sigmund Freud. Fliess creia haber observado regularidades en cierto número de fenómenos a intervalos de 23 y 28 días, incluyendo nacimientos y fallecimientos. Llamó "masculino" al ritmo de 23 días y "femenino" al ritmo de 28 días coincidente con el ciclo menstrual.</p>
		<p>En 1904, Hermann Swoboda, catedrático de psicología sostenía haber descubierlo los mismos ciclos por su cuenta. Más tarde, Alfred Teltscher, catedrático de ingeniería en Innsbruck, llega a la conclusión de que los días buenos y malos de sus estudiantes seguirían un patrón periódico de 33 días. Teltscher creía que la habilidad del cerebro de absorber conocimientos, la capacidad mental y el estado de alerta seguirían ciclos de 33 días.</p>
		<p>La práctica de consultar los biorritmos se popularizó en los años 70 a través de una serie de libros escritos por Bernard Gittelson, entre los que se encuentran Biorhythm — A Personal Science (Biorritmo - Una ciencia personal), Biorhythm Charts of the Famous and Infamous (Cartas biorrítmicas de los famosos e infames) y Biorhythm Sports Forecasting (Pronóstico deportivo mediante biorritmos). La empresa de Gittelson, Biorhytm Computers Inc., ganó dinero vendiendo calculadoras de biorritmos y cartas biorrítmicas personalizadas, sin embargo nunca llegó a nada en la predicción de resultados de eventos deportivos.</p>
		<p>El uso personal de las cartas biorrítmicas estuvo muy extendido en Estados Unidos durante esa época. Muchos lugares (especialmente los salones recreativos) contaban con una máquina que producía cartas biorrítmicas con solo introducir la fecha de nacimiento. Los programas de biorritmos eran una aplicación bastante común de la computadora personal. Aunque la popularidad de los biorritmos ha declinado, existen numerosos sitios web que ofrecen lecturas gratuitas de biorritmos. Además existen aplicaciones libres y privativas de software que permiten llevar a cabo análisis y cartas más avanzados.</p>
		<p>Así los creyentes en los biorritmos vieron en ellos un medio para el autoconocimiento y asumir la existencia de periodos de debilidad, insensibilidad o torpeza a lo largo de la vida. Asimismo éstos entendían que el conocimiento de los biorritmos supondría comprender la alternancia en la vida entre periodos negativos de debilidad y positivos de recuperación.</p>
		<p>También se consultaban los biorritmos para evitar realizar actividades arriesgadas o peligrosas en los días críticos o de mayor debilidad: conducir, manejar maquinaria peligrosa, etc. En el ámbito lectivo, ante unos exámenes, el estudiante podría concentrar sus esfuerzos en los días de mayor energía intelectual relajándose los días de menor potencia.</p>
		<p>En el mundo laboral, los ferrocarriles y las aerolíneas han experimentado grandemente con los biorritmos. Un piloto pone de relieve la actitud hacia los biorritmos de japoneses y estadounidenses.8 Sostiene, revisando su bitácora de piloto, que sus mayores errores de juicio habrían tenido lugar durante los llamados días críticos pero concluye que conocer los propios días críticos y prestar más atención (en ellos) sería suficiente para garantizar la seguridad. Un antiguo piloto de United Airlines confirmó que la compañía habría hecho uso de los biorritmos hasta mediados de los años 90, mientras que la aerolinea de carga Nippon Express aun los seguiría empleando.</p>
	</section>',
	'zh' => '<section id="introduction_zh">
		<p>生理节律是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。</p>
		<h3>理论内容</h3>
		<p>該理論認為，人體受生理節律曲線影響，因此可以對這些曲線進行預測並依據預測結果來安排任務和計劃。這些固定的周期性节律被認為可以控制或引起各種生理進程。有三種經典的周期節律被認為可以控制人類行為並可以表現出自然生理改變的先天周期，它們分別是：</p>
		<ul>
			<li>身体节律（23 天）</li>
			<li>情感节律（28 天）</li>
			<li>智力节律（33 天）</li>
		</ul>
		<p>經典三節律屬於固有的超晝夜節律（Endogenous Infradian Rhythm），其理論基於生理周期和情感周期。通常它們被表示為對稱性的曲線圖，而最常用的形式是正弦波曲線。每個周期按正弦曲線在正位置（0%..100%）和負位置（0%..-100%）之間振蕩。在大多數理論中，該曲線始於基線位置（0%），表示個體出生時的狀態。每當該曲線越過基線位置時，當日即被稱為臨界日，即認為在該日進行的工作狀況會比非臨界日的情況不穩定得多。通常，構造這樣的曲線是為了進行臨界日的計算，以便進行或是避免某些活動。</p>
		<p>其正弦曲線的計算方法為：</p>
		<ul>
			<li>身體: sin(2πt / 23)</li>
			<li>情感: sin(2πt / 28)</li>
			<li>智力: sin(2πt / 33)</li>
			<li>直覺: sin(2πt / 38)</li>
		</ul>
		<p>經典三節律理論僅適用於人類。按照經典理論，每個人的各個周期值都可以在任意時間計算得出，現在也有很多網站提供這樣的服務。</p>
		<h3>歷史</h3>
		<p>該理論發端於19世紀初1897年-1902年之間，主要基於觀察研究。</p>
		<p>維也納大學的心理學教授Hermann Swoboda在研究發燒病人的周期性病情改變時，猜測心情和健康可能存在節律性的改變。他收集了關於病痛反應、發燒、哮喘、心臟病等發作的數據，認為存在一個23天的身體周期以及一個28天的情感周期。</p>
		<p>Wilhelm Fliess，柏林的鼻喉專家，也獨立研究了他的病人的發燒、疾病、死亡的發作情況，同樣得出了存在23天周期及28天周期的結論。他的理論對於後來弗洛伊德（Sigmund Freud）形成及發展精神分析學理論有重要影響。</p>
		<p>因斯布魯克大學的工程學教授Alfred Teltscher，觀察了他的學生的“吉日”和“凶日”，發現其中存在一個33天的周期。Teltscher發現大腦的理解能力、精神活動、警覺性都遵循著這個33天的周期。1920年，美國賓夕法尼亞大學的心理學專家Rexford Hersey博士也聲稱參與了發現經典理論的研究。</p>
		<p>這三種節律構成了經典的生理節律理論。然而，針對該理論在德國、日本、美國所開展的研究卻得不到一致的結果。經典理論在現代存在多種派生理論。</p>
		<h3>可信性(度)</h3>
		<p>该理论的支持者认为生理节律是一门跨学科的新科学，虽然有很多“未能解释的疑点”。但反对者认为这是一本地道的命理学，再加上一些基本数学建立起来的伪科学。生理节律的可信性受到数学家，生物学家和其他一些科学家的质疑。一個基本的問題是，即使假定存在這樣的生理節律，也不清楚為何要開始於人們出生的時刻。</p>
		<p>生理节律和生物钟学有相似的命题，比如近昼夜节律和其他节律的研究。通过一些医学研究，医学工作者发现人的寿命健康的确有其周期节律现象，但只有少部分医生相信这是所谓的“生理节律”。生物钟学的研究表明了诸如近昼夜节律的存在。这些发现说明的是人会受到生理，情感和智力的节律影响（但并不能说明生理节律的正确）。在这一门前科学的“研究中”，视生理节律在有些人身上起主要作用。</p>
		<p>批评者列举下面的理由：</p>
		<ul>
			<li>节律曲线的随意确定性，</li>
			<li>零线的随意确定性，</li>
			<li>三个周期23，28和33的随意确定性，</li>
			<li>建立在轶事，而非实验的基础上，</li>
			<li>忽视数论，</li>
			<li>在假设检验方面犯低级错误，</li>
			<li>对人类行为的不适当量化和一般化，</li>
			<li>对理论缺乏精确的表述，</li>
			<li>同行复查，缺乏实验数据，</li>
			<li>不具可重复性，还有</li>
			<li>一些不道德的从业者被揭发为职业算命骗子</li>
		</ul>
		<p>除了对三种节律的疑问外，人们对这些节律是否有时间相关性都提出了疑问。女性的月经若没有荷尔蒙的调节会出现不稳定，这不可能提前几个月去预测。</p>
		<p>由于生理周期的概念与生物钟学被揉集，导致后者也被一些人怀疑。</p>
	</section>',
	'ja' => '<section id="introduction_ja">
		<p>バイオリズム（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。</p>
		<h3>概要</h3>
		<p>ドイツの外科医ウィルヘルム・フリース（英語版）が、1897年に「生物学から見た鼻と女性性器の関係」で提唱した概念。統計学的に有意なデータが見られず、疑似科学とみなされている。</p>
		<p>人間の場合は、身体（Physical）、感情（EmotionalまたはSensitivity）、知性（Intellectual）の3種類の波を用いて説明されることが多く、頭文字 P, S (E), I と表記される。各リズムは、誕生日を基準とする同じ振幅の正弦波として表され、身体リズムは23日、感情リズムは28日、知性リズムは33日の周期をもつ。</p>
		<p>これらのリズムは、一定の周期でくり返されるため、未来の自分の身体や精神の状態を前もって知ることができるとされ、その時の波形の高低で高調期、低調期などと区別されるが、高調期と低調期の切り替り点は体調が変動しやすいとされ、注意が必要な日とされる。</p>
		<p>なお、数学的には、23と28と33の最小公倍数は21252であり、バイオリズムが完全に一巡するにはおよそ58.2年かかる計算になる。</p>
		<p>最近では元の意味から離れて、単に体調の波を言ったり、月経周期の隠語的言い回しとしてバイオリズムという言葉が使われることがある。</p>
	</section>'
);
// DKIM is used to sign e-mails. If you change your RSA key, apply modifications to the DNS DKIM record of the mailing (sub)domain too !
// Disclaimer : the php openssl extension can be buggy with Windows, try with Linux first

// To generate a new private key with Linux :
// openssl genrsa -des3 -out private.pem 1024
// Then get the public key
// openssl rsa -in private.pem -out public.pem -outform PEM -pubout

// Edit with your own info :

define('MAIL_RSA_PASSPHRASE', 'nhipsinhhoc');

define('MAIL_RSA_PRIV',
'-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA2FAIgMDFIe/HC3oQEeAFXglSzVNlWsEUW7YZmEEKT86Usdxh
BxSpdWDBC6F6USmjV7QO22L+KLHh3o0CnPgKxKetdWNX72kATq/WYMF4jHMFPCi+
yJ+j9+j5HyW+w4kARoRfzZcEf39SeO2YQnWVgjAn6vDfOGvU2R7jpEME7Q7iEwJz
xhrFqnT4WqkH/iscAOgpP897vNLALKHlNTY8cM40jbQfeybu96i0nIGlbeH11nf0
y3zmnMjdX5yY37443+9CjFjtotIo9GhciWN50+O9KtS7MZM19/z8HZzrIXqy4yJu
JgC4JPq5SshO83Hzbipe0kUeqe625QUFkr+zyQIDAQABAoIBAQCEp7g/NXjvkfuQ
R0AZpjfEbpVQBAfROz1/7NIPdDudq8O2u77pN7ugl0BsIJBBu/ZHL844rqHNVSF+
eR2UI+1+opIWvmDMGqmsl9sxpRSHlXYtaZNut7A3tbEpb91oTtlTZZTXIPkKM4vh
S1wnzbJtj5i7VRKfqEl1CaNzNgKMWYYtRyT43P/ZVx2zzruwWsnZm4D0EYaeXqsi
dg9PYhqaDUKLW1uEgCR6/sC5WhxAwdaUczPlv+ZHoBMxZK3oANJwQCHOhtiZw81A
LfEl0djhvhIf3YXtT1JN/NE6JXnGTBS1ykimxW7rCMD3lpi55y5gsonktUal9vMx
Hnw72RIxAoGBAPiTq/GEweAdpDrUOBkTr36gp7lnG5Q5AMshnMtPyxNusjIo7jdO
OOUWNiWnGxHrM9UH3Ln9RSDiDyabjvpD/mp8RFerJGSRq9q/lC7be1mnFiO3HcY+
XaZLBAhBlCIt6VpMxwlcmoKAKf0BKhOpNpF70BRDpdN+FL6rR8h7IeKVAoGBAN7F
tQGiWY+aY6Kr5dzqOZyfMZX4n8JkxeCTpXNXZ/M0D4IoP/l2pa/E7euDrd38CG8Z
pDcI+OKhmF0rE2Ax4dElmuqvNmnj7ev8y1q+41mzi65yUm1Pj20BCZhNpoc7Q8VU
aAc0VNDxreMyeuxI6f8i1qj1jBsuaiGnI46yf1NlAoGBANn/kATAu9KTzEq3gPcl
F3yC4nUrorksALEkqkB3mw5Qv0BUOw4PsL/f6d69nXTqg8tpGL+YCX8cIdNnC04a
QU4b60fDVKhKRKAT8Z3iaFwot7bcyeTpHvJOyZt//6y1/PdvbAKezvZx9eDnm4Ig
nTWbktGivhQrd3/78Hk223G9AoGANOCw6kZjA9cdt0seMKXYLvkKiBKPuVC6N2hU
aWBh6DR/SeHL7abgBSy1XKXQ61QbnGN1uyqCCSlaRSMoYjj05PQMJZCZVeEoZE+I
5W1SAcTAbxPrxM6RuMn6QRCNaqQCniA+4Rbm2wHCyJ7+1n8oK8tVMJ24N+t4faxy
oDh2SJECgYEAl4nSqxFO5cQlArum8qjfDFC/1qQp1Px/NfrG1QssvUYHuPoIuHk9
G06bBta22pT+BxdX48b9tTdoz5szJNgFrAfnc6sJjELeyO/pOhNDwInPdO4VSQ0Z
NWlfMw/pZZd3hmr2vzTJhylISVv4FArB+LzOo10YQesDE4ZCXrmdb44=
-----END RSA PRIVATE KEY-----');

define('MAIL_RSA_PUBL',
'-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2FAIgMDFIe/HC3oQEeAF
XglSzVNlWsEUW7YZmEEKT86UsdxhBxSpdWDBC6F6USmjV7QO22L+KLHh3o0CnPgK
xKetdWNX72kATq/WYMF4jHMFPCi+yJ+j9+j5HyW+w4kARoRfzZcEf39SeO2YQnWV
gjAn6vDfOGvU2R7jpEME7Q7iEwJzxhrFqnT4WqkH/iscAOgpP897vNLALKHlNTY8
cM40jbQfeybu96i0nIGlbeH11nf0y3zmnMjdX5yY37443+9CjFjtotIo9GhciWN5
0+O9KtS7MZM19/z8HZzrIXqy4yJuJgC4JPq5SshO83Hzbipe0kUeqe625QUFkr+z
yQIDAQAB
-----END PUBLIC KEY-----');

// Domain or subdomain of the signing entity (i.e. the domain where the e-mail comes from)
define('MAIL_DOMAIN', 'mail.nhipsinhhoc.vn');  

// Allowed user, defaults is "@<MAIL_DKIM_DOMAIN>", meaning anybody in the MAIL_DKIM_DOMAIN domain. Ex: 'admin@mydomain.tld'. You'll never have to use this unless you do not control the "From" value in the e-mails you send.
define('MAIL_IDENTITY', NULL);

// Selector used in your DKIM DNS record, e.g. : selector._domainkey.MAIL_DKIM_DOMAIN
define('MAIL_SELECTOR', 'x');