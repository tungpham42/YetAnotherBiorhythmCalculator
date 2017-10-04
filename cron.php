<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('max_execution_time', 0);
ini_set('session.save_handler','files');
ini_set('session.save_path','/tmp');
$db_type = 'sqlite';
// mysql or sqlite
if ($db_type == 'mysql') {
	/* Database config */
	$db_host		= '127.0.0.1';
	$db_user		= 'nhipsinh_root';
	$db_pass		= 'P@ssword0129';
	$db_database	= 'nhipsinh_main';
	/* End config */
	$pdo = new PDO('mysql:host='.$db_host.';port=3306;dbname='.$db_database,$db_user,$db_pass);
	$pdo->query('SET names UTF8');
} else if ($db_type == 'sqlite') {
	$db_path = '/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/db/nsh.db';
	$pdo = new PDO('sqlite:'.$db_path);
}
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
function load_all_array($table_name): array { //Put all table records into an array
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'"');
	$result->execute();
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_operator($table_name,$identifier,$value,$operator): array { //Put specific table records according to condition into an array
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier.$operator.':value');
	$result->execute(array(':value' => $value));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_two_identifiers($table_name,$identifier1,$value1,$identifier2,$value2): array { //Load array from database with 2 identifiers
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier1.'=:value1 AND '.$identifier2.'=:value2');
	$result->execute(array(':value1' => $value1, ':value2' => $value2));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array_with_two_values($table_name,$identifier,$value1,$value2): array { //Load array from database with 1 identifier and 2 values
	global $pdo;
	$array = array();
	$result = $pdo->prepare('SELECT * FROM "'.$table_name.'" WHERE '.$identifier.'=:value1 OR '.$identifier.'=:value2');
	$result->execute(array(':value1' => $value1, ':value2' => $value2));
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
	}
	return $array;
}
function load_array($table_name,$identifier,$value): array { //Load array from database with 1 identifier and 1 value
	$array = load_array_with_operator($table_name,$identifier,$value,'=');
	return $array;
}
function digitval($number): int {
	$sum = 0;
	while ($number > 0) {
	    $rem = $number % 10;
	    $number = $number / 10;
	    $sum = $sum + $rem;
	}
	if (strlen($sum) >= 2)
		$res = digitval($sum);
	elseif ($sum == 11 || $sum == 22)
		$res = $sum;
	else
		$res = $sum;
	return $res;
}
function calculate_life_path($dob): int {
	$life_path_number = 0;
	$year = date('Y',strtotime($dob));
	$month = date('m',strtotime($dob));
	$day = date('d',strtotime($dob));
	$life_path_number = digitval(digitval($year) + digitval($month) + digitval($day));
	return $life_path_number;
}
function libraries_autoload($class_name) {
	require '/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/includes/libraries/'.$class_name.'.class.php';
}
function google_api_php_client_autoload($class_name) {
	$class_path = explode('_', $class_name);
	if ($class_path[0] != 'Google') {
		return;
	}
	if (count($class_path) > 3) {		// Maximum class file path depth in this project is 3.
		$class_path = array_slice($class_path, 0, 3);
	}
	$file_path = '/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/includes/' . implode('/', $class_path) . '.php';
	if (file_exists($file_path)) {
		require_once($file_path);
	}
}
spl_autoload_register('libraries_autoload');
spl_autoload_register('google_api_php_client_autoload');
$lang_codes = array('vi','en','ru','es','zh','ja');
$one_lang = 'vi';
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
		'en' => 'Body mass index',
		'ru' => 'Индекс массы тела',
		'es' => 'Índice de masa corporal',
		'zh' => '身高體重指數',
		'ja' => 'ボディマス指数'
	),
	'lunar' => array(
		'vi' => 'Xem ngày',
		'en' => 'Lunar calendar',
		'ru' => 'Лунный календарь',
		'es' => 'Calendario lunar',
		'zh' => '阴历',
		'ja' => '太陰暦'
	),
	'game' => array(
		'vi' => 'Trò chơi',
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
		'vi' => 'Ứng dụng',
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
	'keyboard_shortcuts' => array(
		'vi' => 'Phím tắt',
		'en' => 'Keyboard shortcuts',
		'ru' => 'Горячие клавиши',
		'es' => 'Atajos de teclado',
		'zh' => '快捷键',
		'ja' => 'キーボードショートカット'
	),
	'keyboard_shortcuts_long' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>S / G / K -> Hôm nay</li><li>A / F / J -> Trước<li>D / H / L -> Sau</li><li>W / T / I -> Sinh nhật</li><li>E / Y / O -> Nhịp sinh học phụ</li><li>R / U / P -> Thành ngữ</li><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
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
		'es' => 'Índice de masa corporal',
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
		'vi' => 'Nhập thông tin ngày tháng năm sinh Dương lịch của bạn vào ô Ngày sinh theo định dạng YYYY-MM-DD (năm-tháng-ngày). Sau đó, nhấn nút Chạy để hiển thị dự đoán Sức khỏe, Tình cảm, Trí tuệ. Nếu bạn chỉ quan tâm đến Nhịp sinh học ngủ, bạn không cần điền Họ tên và Ngày sinh.',
		'en' => 'Type in your date of birth into the Date of birth field with the format YYYY-MM-DD (year-month-day). Then click Run to know your physical, emotional, intellectual values. If you only care about Sleep Rhythm, you don\'t need to type in your Full name or Date of birth.',
		'ru' => 'Введите дату своего рождения в поле День Рождения с форматом YYYY-MM-DD (год-месяц-день). Затем нажмите кнопку Идти, чтобы узнать ваши физические, эмоциональные, интеллектуальные ценности. Если вы только заботиться о Sleep ритм, вам не нужно вводить полное имя или дату рождения.',
		'es' => 'Escriba su fecha de nacimiento en el campo Fecha de nacimiento con el formato YYYY-MM-DD (año-mes-dia). Luego haga clic en Correr para conocer sus valores, físicas, intelectuales y emocionales. Si sólo se preocupa por el sueño Ritmo, usted no tiene que escribir su nombre o fecha de nacimiento completa.',
		'zh' => '输入你的出生日期为出生场的日期格式为YYYY-MM-DD（年-月-日）。然后点击运行就知道你的身体，情绪，智力值。如果你只在乎睡眠节律，你不需要输入您的姓名和出生日期。',
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
		'vi' => 'http://www.12cungsao.com/p/',
		'en' => 'http://www.astrology-numerology.com/num-lifepath.html#lp',
		'ru' => 'http://www.astroland.ru/numerology/lw/lifeway',
		'es' => 'http://www.horoscopius.es/numerologia/perfil-numerologico-para-el-numero-',
		'zh' => 'http://nhipsinhhoc.vn/blog/life-path-number-',
		'ja' => 'http://www.heavenlyblue.jp/num/'
	),
	'life_path_number_suffix' => array(
		'vi' => '.html',
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
$clickbank = '<a href="http://tungpham42.15manifest.hop.clickbank.net"><img src="http://maxcdn.15minutemanifestation.com/affiliates/images/300x250.jpg"></a>';
function generate_proverb($lang): array {
	$proverbs = new parseCSV();
	$proverbs->delimiter = '|';
	$proverbs->parse('/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/proverbs/'.$lang.'.csv');
	$count = count($proverbs->data);
	$index = rand(0, $count-1);
	return $proverbs->data[$index];
}
function render_proverb($lang) {
	$proverb = generate_proverb($lang);
	echo '<blockquote id="proverb_content" class="changeable"><i title="R / U / P" id="proverb_refresh" class="icon-white icon-refresh"></i><div id="proverb_text" onClick="selectText(\'proverb_text\')">'.$proverb['content'].'</div></blockquote ><span class="arrow_down"></span><p id="proverb_author">'.$proverb['author'].'</p><a id="all_proverbs" class="m-btn green" href="/proverbs/" target="_blank"><i class="icon-more-items"></i> '.translate_span('all_proverbs').'</a>';
}
function has_birthday($dob,$time): bool {
	if (date('m-d',strtotime($dob)) == date('m-d',$time)) {
		return true;
	} else {
		return false;
	}
}
function is_birthday(): bool {
	global $dob;
	return has_birthday($dob, time());
}
function can_wish(): bool {
	if (has_dob() && is_birthday()) {
		return true;
	} else {
		return false;
	}
}
function sort_date_member_ascend($a,$b){ //Call back function to sort date ascendently
	if (isset($a['created_at']) && isset($b['created_at'])) {
		return strcmp(strtotime($a['created_at']),strtotime($b['created_at']));
	}
}
function load_member_from_email($email): array {
	$array = array();
	if ($email != "") {
		$path = '/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/member/'.strtolower($email);
		$db_path = $path.'/member.db';
		$db_sql = 'SELECT * FROM "member"';
		try {
			$db = new PDO('sqlite:'.$db_path);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db_query = $db->prepare($db_sql);
			$db_query->execute();
			if ($db_query) {
				while($row = $db_query->fetch(PDO::FETCH_ASSOC)) {
					$array = $row;
				}
			}
		}
		catch (PDOException $e) {
			echo 'ERROR: '.$e->getMessage();
		}
		return $array;
	} else {
		return null;
	}
}
function differ_date($start, $end): int {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = $end_ts - $start_ts;
	return round($diff/86400);
}
function differ_year($start, $end): int {
	$start_dt = new DateTime(date('Y-m-d', strtotime($start)));
	$end_dt = new DateTime(date('Y-m-d', strtotime($end)));
	$diff = $end_dt->diff($start_dt);
	return $diff->y;
}
function bio_count($dob,$date,$scale): float { //http://en.wikipedia.org/wiki/Biorhythm
	$x = differ_date($dob,$date);
	//return (number_format((sin(2*pi()*$x/$scale)*100),2) != '-0.00') ? number_format((sin(2*pi()*$x/$scale)*100),2): '0.00';
	return number_format(((sin(2*pi()*$x/$scale)*100)+100)/2,2);
}
function percent_bio_count($dob,$date,$scale): string {
	return bio_count($dob,$date,$scale).' %';
}
function average_bio_count($dob,$date,$rhythms): float {
	$total = 0;
	$count = (count($rhythms) > 0) ? count($rhythms): 1;
	$i = 0;
	foreach ($rhythms as $rhythm) {
		$total += bio_count($dob,$date,$rhythm['scale']);
		++$i;
	}
	return number_format($total/$count,2);
}
function percent_average_bio_count($dob,$date,$rhythms): string {
	return average_bio_count($dob,$date,$rhythms).' %';
}
function countdown_birthday($dob, $date = 'today'): int {
	$countdown = 0;
	$birthday = date('m-d', strtotime($dob));
	$diff = differ_date($date, date('Y', strtotime($date)).'-'.$birthday);
	if ($diff <= 0) {
		$countdown = differ_date($date, (date('Y', strtotime($date)) + 1).'-'.$birthday);
	} else if ($diff > 0) {
		$countdown = $diff;
	}
	return $countdown;
}
function pluralize($count, $singular, $plural = false): string {
	if (!$plural) $plural = $singular . 's';
	return (($count == 0 || $count == 1) ? $singular : $plural);
}
function get_wiki_url_nsh($title): string {
	return 'http://nhipsinhhoc.vn/wiki/'.str_replace(' ', '_', $title);
}
function get_ad($name): string {
	$ads = new parseCSV('/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/ads/'.$name.'.csv');
	$count = count($ads->data);
	$index = rand(0, $count-1);
	$ad = "";
	switch ($name) {
		case 'itunes_160x600':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=160&amp;h=600"></iframe>';
			break;
		case 'itunes_300x250':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=300&amp;h=250"></iframe>';
			break;
		case 'itunes_728x90':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=728&amp;h=90"></iframe>';
			break;
		case 'amazon_160x600':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=14&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_300x250':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=12&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_728x90':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=48&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		default:
			$ad = '<a class="ads" target="_blank" href="'.$ads->data[$index]['link_href'].'"><img style="width: 360px" alt="'.$name.'" src="'.$ads->data[$index]['img_src'].'" /></a>'.((isset($ads->data[$index]['other_img_src'])) ? '<img class="other_img" style="border:0" src="'.$ads->data[$index]['other_img_src'].'" width="1" height="1" alt="" />': "");
	}
	return $ad;
}
function generate_message_id() {
	return sprintf("<%s.%s@%s>",base_convert(microtime(), 10, 36),base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),"nhipsinhhoc.vn");
}
function send_mail($to,$subject,$message) {
	global $span_interfaces, $email_credentials;
//	$unsubscriber_emails = array();
//	$unsubscribers = new parseCSV();
//	$unsubscribers->parse('/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/member/unsubscribers_list.csv');
//	$unsubscribers_count = count($unsubscribers->data);
//	for ($i = 0; $i < $unsubscribers_count; ++$i) {
//		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
//	}
//	sort($unsubscriber_emails);
//	if (!in_array(strtolower($to), $unsubscriber_emails)) {
		$fullname = load_member_from_email($to)['fullname'];
		$boundary = uniqid('np');
		$headers = "";
		$headers .= "Organization: \"Nhip Sinh Hoc . VN\"".PHP_EOL;
		$headers  = "MIME-Version: 1.0".PHP_EOL;
		$headers .= "X-Priority: 1 (Highest)".PHP_EOL;
		$headers .= "Importance: High".PHP_EOL;
		$headers .= "X-Mailer: PHP/". phpversion().PHP_EOL;
		$headers .= "Content-Transfer-Encoding: 8bit".PHP_EOL;
		$headers .= "From: \"Nhip Sinh Hoc . VN\" <noreply@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "Sender: <noreply@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "Reply-To: \"Nhip Sinh Hoc . VN\" <admin@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "Return-Path: \"Nhip Sinh Hoc . VN\" <admin@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "List-Unsubscribe: <mailto:admin@nhipsinhhoc.vn?subject=Unsubscribe me out of Nhip Sinh Hoc . VN mailing list&body=Please unsubscribe my email&cc=tung.42@gmail.com>".PHP_EOL;
		$headers .= "Content-Type: multipart/alternative;boundary=".$boundary.PHP_EOL;
		//here is the content body
		$body = "This is a MIME encoded message.".PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary.PHP_EOL;
		$body .= "Content-type: text/plain;charset=utf-8".PHP_EOL.PHP_EOL;
		//Plain text body
		$body .= $message['plain'].PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary.PHP_EOL;
		$body .= "Content-type: text/html;charset=utf-8".PHP_EOL.PHP_EOL;
		//Html body
		$body .= $message['html'].PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary."--";
		mail("\"".$fullname."\" <".strtolower($to).">", '=?utf-8?B?'.base64_encode('☺ '.$subject).'?=', $body, $headers);
//	}
}
function email_message($heading,$content): array {
	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <meta name="viewport" content="width=device-width"/></head><body style="padding: 0px; margin: 0px; width: 100%; min-width: 100%"> <table class="body" style="color: #222222;background-image: url(\'http://nhipsinhhoc.vn/css/images/coin.png\'); min-height: 420px;border: none; border-spacing: 0px; position: relative; height: 100%;width: 100%; top: 0px; left: 0px; margin: 0px;"> <tr style="padding: 0px; margin: 0px;text-align: center; width: 100%;"> <td style="padding: 0px; margin: 0px;text-align: center; width: 100%;" align="center" valign="top"> <center> <table style="height: 100px;padding: 0px;width: 100%;position: relative;background: #007799;" class="row header"> <tr> <td style="text-align: center;" align="center"> <center> <table style="margin: 0 auto;text-align: inherit;width: 95% !important;" class="container"> <tr> <td style="padding: 10px 20px 0px 0px;position: relative;display: block !important;padding-right: 0 !important;" class="wrapper last"> <table style="width: 95%;" class="twelve columns"> <tr> <td style="padding: 8px;" class="six sub-columns"> <a target="_blank" href="http://nhipsinhhoc.vn/"><img alt="logo" src="http://nhipsinhhoc.vn/app-icons/icon-60.png"> </a> </td><td class="six sub-columns last" style="text-align:left; vertical-align:middle;padding-right: 0px; color: white; width: 90%"> <span class="template-label"><a style="font-size: 24px;color: white; text-decoration: none;" target="_blank" href="http://nhipsinhhoc.vn/">'.$heading.'</a></span> </td><td class="expander"></td></tr></table> </td></tr></table> </center> </td></tr></table> <table class="container"> <tr> <td> <table class="row"> <tr> <td style="padding: 10px 10px 0px 0px;position: relative;display: block !important;padding-right: 0 !important;" class="wrapper last"> <table style="width: 80%;font-size:16px;margin: auto;" class="twelve columns"> <tr> <td> '.$content.' </td><td class="expander"></td></tr></table> </td></tr></table> </td></tr></table> </center> </td></tr></table></body></html>';
	return array(
		'html' => $message,
		'plain' => strip_tags($content)
	);
}
function email_daily_suggestion() {
	global $email_interfaces, $span_interfaces;
	//$my_email = 'nhipsinhhoc@mail-tester.com';
	$my_email = 'tung.42@gmail.com';
	$unsubscriber_emails = array();
	$all_emails = array();
	$emails = array();
	$members = array();
	$unsubscribers = new parseCSV();
	$unsubscribers->parse('/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/member/unsubscribers_list.csv');
	$unsubscribers_count = count($unsubscribers->data);
	for ($i = 0; $i < $unsubscribers_count; ++$i) {
		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
	}
	sort($unsubscriber_emails);
	$path = '/home/nhipsinh/domains/nhipsinhhoc.vn/public_html/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$all_emails[] = str_replace($path, "", $directory);
			}
		}
	}
	sort($all_emails);
	$emails = array_diff($all_emails, $unsubscriber_emails);
	$count = count($emails);
	//$count = 42; // test only
	sort($emails);
	for ($m = 0; $m < $count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	usort($members,'sort_date_member_ascend');
//	$feed_email = rss_feed_email('http://nhipsinhhoc.vn/blog/feed/?cat=3%2C81',$span_interfaces['latest_posts']['vi'],'feed_blog');
	for ($i = 0; $i < $count; ++$i) {
		$member_chart = new Chart($members[$i]['dob'],0,0,date('Y-m-d'),$members[$i]['dob'],$members[$i]['lang']);
		$heading = "";
		switch ($members[$i]['lang']) {
			case 'vi': $heading = 'Biểu đồ nhịp sinh học | Bieu do nhip sinh hoc'; break;
			case 'en': $heading = 'Biorhythm chart'; break;
			case 'ru': $heading = 'Биоритм диаграммы'; break;
			case 'es': $heading = 'Biorritmo carta'; break;
			case 'zh': $heading = '生理节律图'; break;
			case 'ja': $heading = 'バイオリズムチャート'; break;
		}
		$proverb = generate_proverb($members[$i]['lang']);
		$content = "";
		$content .= (has_birthday($members[$i]['dob'], time())) ? '<style>body {background-image: url("http://nhipsinhhoc.vn/css/images/gifts_mobile.png") !important;}</style>' : "";
		$content .= '<h1>'.((has_birthday($members[$i]['dob'], time())) ? $email_interfaces['happy_birthday'][$members[$i]['lang']] : $email_interfaces['hi'][$members[$i]['lang']]).' '.$members[$i]['fullname'].' (<a style="text-decoration: none; font-size: 42px; color: green;" href="'.get_wiki_url_nsh($members[$i]['fullname']).'">WIKI</a>)</h1>';
		$content .= get_ad('banner_300x250');
		$content .= '<p class="lead">'.$email_interfaces['daily_suggestion'][$members[$i]['lang']].$email_interfaces['colon'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$member_chart->get_infor().'</p>';
		$content .= '<p>'.$member_chart->get_birthday_countdown().'</p>';
		$content .= '<p class="lead">'.$email_interfaces['daily_values'][$members[$i]['lang']].$email_interfaces['colon'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$member_chart->get_infor_values().'</p>';
		$content .= '<p><a href="http://nhipsinhhoc.vn/member/'.$members[$i]['email'].'/">'.$email_interfaces['go_to_your_profile'][$members[$i]['lang']].'</a></p>';
		$content .= '<p><a href="https://www.youtube.com/watch?v='.$email_interfaces['instruction_video_youtube_id'][$members[$i]['lang']].'">'.$email_interfaces['instruction_video_text'][$members[$i]['lang']].'</a></p>';
		$content .= '<p>'.$email_interfaces['regards'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$span_interfaces['pham_tung'][$members[$i]['lang']].'</p>';
		if ($members[$i]['lang'] == 'vi') {
//			$content .= $feed_email;
			$content .= '<a target="_blank" href="https://docs.google.com/forms/d/1iMLcQNKnDoHyqMaS-uQo9ocvZawhc2JUPUtjcz1WR4E/viewform">Link Góp ý</a>';
		}
		$content .= '<h4><a href="http://nhipsinhhoc.vn/donate/?lang='.$members[$i]['lang'].'">'.$span_interfaces['donate'][$members[$i]['lang']].'</a> '.$span_interfaces['donate_reason'][$members[$i]['lang']].'</h4>';
		$content .= '<p><em>"'.$proverb['content'].'"</em></p><em><a href="'.get_wiki_url_nsh($proverb['author']).'">'.$proverb['author'].'</a></em>';
		$content .= '<p><em>'.$email_interfaces['definition'][$members[$i]['lang']].'</em></p>';
		$content .= '<p>'.$span_interfaces['for_reference_only'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$email_interfaces['keyboard_shortcuts'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$email_interfaces['not_mark_as_spam'][$members[$i]['lang']].'</p>';
		$content .= '<p><a href="mailto:admin@nhipsinhhoc.vn?subject='.$email_interfaces['unsubscribe'][$members[$i]['lang']].'&body='.$email_interfaces['unsubscribe'][$members[$i]['lang']].' '.$members[$i]['email'].'&cc=tung.42@gmail.com">'.$email_interfaces['unsubscribe'][$members[$i]['lang']].'</a></p>';
		$message = email_message($heading, $content);
		//send_mail($my_email,$email_interfaces['hi'][$members[$i]['lang']].' '.$members[$i]['fullname'].', '.$email_interfaces['daily_suggestion'][$members[$i]['lang']].' | '.date('Y-m-d'),$message); // test only
		send_mail($members[$i]['email'],$email_interfaces['hi'][$members[$i]['lang']].' '.$members[$i]['fullname'].', '.$email_interfaces['daily_suggestion'][$members[$i]['lang']].' | '.date('Y-m-d'),$message);
		//sleep(2);
	}
}
function test_email_daily_suggestion() {
	global $email_interfaces, $span_interfaces;
	//$my_email = 'nhipsinhhoc@mail-tester.com';
	$my_email = 'tung.42@gmail.com';
	$unsubscriber_emails = array();
	$all_emails = array();
	$emails = array();
	$members = array();
	$unsubscribers = new parseCSV();
	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
	$unsubscribers_count = count($unsubscribers->data);
	for ($i = 0; $i < $unsubscribers_count; ++$i) {
		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
	}
	sort($unsubscriber_emails);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$all_emails[] = str_replace($path, "", $directory);
			}
		}
	}
	sort($all_emails);
	$emails = array_diff($all_emails, $unsubscriber_emails);
	$count = count($emails);
	sort($emails);
	for ($m = 0; $m < $count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	usort($members,'sort_date_member_ascend');
	echo '<pre>';
	print_r($unsubscriber_emails);
	print_r($all_emails);
	print_r($emails);
	print_r($members);
	echo '</pre>';
}
if (isset($_GET['test']) && $_GET['test'] == 'yes') {
	test_email_daily_suggestion();
	echo 'tested!';
} else {
	email_daily_suggestion();
	echo 'success!';
}
?>