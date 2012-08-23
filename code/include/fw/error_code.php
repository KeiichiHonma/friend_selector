<?php
// --------------------------
// 共通エラー
// --------------------------

//エラー宣言
define('E_CMMN_URL_WRONG',               'FS_CMMN_00001');
define('E_CMMN_TOKEN_WRONG',             'FS_CMMN_00002');
define('E_CMMN_REQUEST_ERROR',           'FS_CMMN_00003');
define('E_CMMN_UNEXPEDTED_ERROR',        'FS_CMMN_00004');
define('E_CMMN_FRIENDLIST_EXISTS',       'FS_CMMN_00005');
define('E_CMMN_FRIENDLIST_SAME',         'FS_CMMN_00006');
define('E_CMMN_PERMISSIONS_ERROR',       'FS_CMMN_00007');

//エラーメッセージ
define('FS_CMMN_00001',            'URLが不正です。');
define('FS_CMMN_00002',            '不正なリクエストです。<br />クッキーが有効になっていない可能性があります。<br />ブラウザの設定をご確認ください。');
define('FS_CMMN_00003',            '現在、サーバーが混んでおります。しばらく経ってから、もう一度お試しください。');
define('FS_CMMN_00004',            '不正なリクエストです。<br />javascriptが有効になっていない、又はクッキーが有効になっていない可能性があります。<br />ブラウザの設定をご確認ください。');
define('FS_CMMN_00005',            '友達リストが削除されたか存在しません。');
define('FS_CMMN_00006',            '同じ名前の友達リストがすでに存在します。');
define('FS_CMMN_00007',            '必要な権限が許可されていないため本アプリを使用することができません。<br />本アプリを一度削除してから再度権限を許可した上でご使用くださいませ。<br />【削除手順】<br />ホーム→プライバシー設定→広告、アプリ、ウェブサイトの設定を編集<br />→利用しているアプリ→Friend Selector→アプリを削除');
//システム管理画面特有のエラー




// --------------------------
// USERエラー
// --------------------------
//エラー宣言
define('E_MANAGER_ACCESS_WRONG',                   'FS_MANAGER_00001');
define('E_MANAGER_AUTH_PARAM_UNSET',               'FS_MANAGER_00002');
define('E_AUTH_NG',                                'FS_MANAGER_00003');

//エラーメッセージ
define('FS_MANAGER_00001',                        '不正なリクエストです。');
define('FS_MANAGER_00002',                        '必要なユーザーパラメータがセットされていないため、操作できません。最初から操作をやり直してください。');
define('FS_MANAGER_00003',                        'メールアドレス、あるいは、パスワードが間違っています。<br />ログインするメールアドレスの設定がご指定のメールアドレス種類(携帯 or パソコン)と異なっている可能性があります。携帯、パソコン、両方のメールアドレスで試してください。');

// --------------------------
// ファイルシステムエラー
// --------------------------
define('E_SYSTEM_DIR_NO_EXIST',            'FS_SYSTEM_00001');
define('E_SYSTEM_DIR_NO_WRITE',            'FS_SYSTEM_00002');
define('E_SYSTEM_FILE_NO_WRITE',           'FS_SYSTEM_00003');
define('E_SYSTEM_FILE_EXIST',              'FS_SYSTEM_00004');

define('E_SYSTEM_FILE_1',                  'FS_SYSTEM_00005');//E_SYSTEM_FILE_BASE_SIZE
define('E_SYSTEM_FILE_2',                  'FS_SYSTEM_00006');//E_SYSTEM_FILE_FORM_SIZE
define('E_SYSTEM_FILE_3',                  'FS_SYSTEM_00007');//E_SYSTEM_FILE_PART_UPLOAD
define('E_SYSTEM_FILE_4',                  'FS_SYSTEM_00008');//E_SYSTEM_FILE_ALL_UPLOAD

define('E_SYSTEM_FILE_NOT_COPY',           'FS_SYSTEM_00009');
define('E_CODE_EMPTY',                     'FS_SYSTEM_00010');
define('E_CODE_DUPLICATION',               'FS_SYSTEM_00011');
define('E_MAIL_NOT_SEND',                  'FS_SYSTEM_00012');
define('E_SYSTEM_CSV_WRONG',               'FS_SYSTEM_00014');
define('E_SYSTEM_PARAM_WRONG',             'FS_SYSTEM_00015');

define('E_SYSTEM_MANAGER_EXISTS',            'FS_SYSTEM_00016');
define('E_SYSTEM_GROUP_EXISTS',              'FS_SYSTEM_00017');
define('E_SYSTEM_COUPON_EXISTS',             'FS_SYSTEM_00018');

define('FS_SYSTEM_00001',              'ディレクトリが存在しません');
define('FS_SYSTEM_00002',              'ディレクトリへの書き込み権限がありません');
define('FS_SYSTEM_00003',              'ファイルへの書き込み権限がありません');
define('FS_SYSTEM_00004',              '既にファイルが存在しています');
define('FS_SYSTEM_00005',              '！基本ファイルサイズの制限値を超えています');
define('FS_SYSTEM_00006',              '！フォームファイルサイズの制限値を超えています');
define('FS_SYSTEM_00007',              '！一部分のみしかアップロードされていませんでした');
define('FS_SYSTEM_00008',              '！ファイルは必須です。ファイルがアップロードされませんでした');
define('FS_SYSTEM_00009',              'ファイルコピーに失敗しました');
define('FS_SYSTEM_00010',              'コードが入力されていません');
define('FS_SYSTEM_00011',              'コードが重複しています');
define('FS_SYSTEM_00012',              'メールの送信に失敗しました．');
define('FS_SYSTEM_00014',              'CSVファイルが不正です');
define('FS_SYSTEM_00015',              'パラメータが不正です');
define('FS_SYSTEM_00016',            'マネージャが削除されたか存在しません。');
define('FS_SYSTEM_00017',            'グループが削除されたか存在しません。');
define('FS_SYSTEM_00018',            'クーポンが削除されたか存在しません。');
?>
