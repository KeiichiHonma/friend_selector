<?php
// --------------------------
// 共通エラー
// --------------------------

//エラー宣言
define('E_CMMN_URL_WRONG',               'TAR_CMMN_00001');
define('E_CMMN_TOKEN_WRONG',             'TAR_CMMN_00002');
define('E_CMMN_REQUEST_ERROR',           'TAR_CMMN_00003');
define('E_CMMN_AREA_EXISTS',             'TAR_CMMN_00004');
define('E_CMMN_PROPERTY_EXISTS',         'TAR_CMMN_00005');
define('E_CMMN_ROOM_EXISTS',             'TAR_CMMN_00006');

//エラーメッセージ
define('TAR_CMMN_00001',                 'This URL is invalid.');
define('TAR_CMMN_00002',                 'This is unauthorized access. Cookies may not be activated. Please check your browser.');
define('TAR_CMMN_00003',                 'Now server busy,please try later.');
define('TAR_CMMN_00004',                 'The area is deleted or does not exist.');
define('TAR_CMMN_00005',                 'The property is deleted or does not exist.');
define('TAR_CMMN_00006',                 'The room is deleted or does not exist.');

//システム管理画面特有のエラー




// --------------------------
// USERエラー
// --------------------------
//エラー宣言
define('E_MANAGER_ACCESS_WRONG',                   'TAR_MANAGER_00001');
define('E_MANAGER_AUTH_PARAM_UNSET',               'TAR_MANAGER_00002');
define('E_AUTH_NG',                                'TAR_MANAGER_00003');

//エラーメッセージ
define('TAR_MANAGER_00001',                        '不正なリクエストです。');
define('TAR_MANAGER_00002',                        '必要なユーザーパラメータがセットされていないため、操作できません。最初から操作をやり直してください。');
define('TAR_MANAGER_00003',                        'メールアドレス、あるいは、パスワードが間違っています。<br />ログインするメールアドレスの設定がご指定のメールアドレス種類(携帯 or パソコン)と異なっている可能性があります。携帯、パソコン、両方のメールアドレスで試してください。');

// --------------------------
// ファイルシステムエラー
// --------------------------
define('E_SYSTEM_DIR_NO_EXIST',            'TAR_SYSTEM_00001');
define('E_SYSTEM_DIR_NO_WRITE',            'TAR_SYSTEM_00002');
define('E_SYSTEM_FILE_NO_WRITE',           'TAR_SYSTEM_00003');
define('E_SYSTEM_FILE_EXIST',              'TAR_SYSTEM_00004');

define('E_SYSTEM_FILE_1',                  'TAR_SYSTEM_00005');//E_SYSTEM_FILE_BASE_SIZE
define('E_SYSTEM_FILE_2',                  'TAR_SYSTEM_00006');//E_SYSTEM_FILE_FORM_SIZE
define('E_SYSTEM_FILE_3',                  'TAR_SYSTEM_00007');//E_SYSTEM_FILE_PART_UPLOAD
define('E_SYSTEM_FILE_4',                  'TAR_SYSTEM_00008');//E_SYSTEM_FILE_ALL_UPLOAD

define('E_SYSTEM_FILE_NOT_COPY',           'TAR_SYSTEM_00009');
define('E_CODE_EMPTY',                     'TAR_SYSTEM_00010');
define('E_CODE_DUPLICATION',               'TAR_SYSTEM_00011');
define('E_MAIL_NOT_SEND',                  'TAR_SYSTEM_00012');
define('E_SYSTEM_CSV_WRONG',               'TAR_SYSTEM_00014');
define('E_SYSTEM_PARAM_WRONG',             'TAR_SYSTEM_00015');

define('E_SYSTEM_MANAGER_EXISTS',            'TAR_SYSTEM_00016');
define('E_SYSTEM_GROUP_EXISTS',              'TAR_SYSTEM_00017');
define('E_SYSTEM_COUPON_EXISTS',             'TAR_SYSTEM_00018');

define('TAR_SYSTEM_00001',              'ディレクトリが存在しません');
define('TAR_SYSTEM_00002',              'ディレクトリへの書き込み権限がありません');
define('TAR_SYSTEM_00003',              'ファイルへの書き込み権限がありません');
define('TAR_SYSTEM_00004',              '既にファイルが存在しています');
define('TAR_SYSTEM_00005',              '！基本ファイルサイズの制限値を超えています');
define('TAR_SYSTEM_00006',              '！フォームファイルサイズの制限値を超えています');
define('TAR_SYSTEM_00007',              '！一部分のみしかアップロードされていませんでした');
define('TAR_SYSTEM_00008',              '！ファイルは必須です。ファイルがアップロードされませんでした');
define('TAR_SYSTEM_00009',              'ファイルコピーに失敗しました');
define('TAR_SYSTEM_00010',              'コードが入力されていません');
define('TAR_SYSTEM_00011',              'コードが重複しています');
define('TAR_SYSTEM_00012',              'メールの送信に失敗しました．');
define('TAR_SYSTEM_00014',              'CSVファイルが不正です');
define('TAR_SYSTEM_00015',              'パラメータが不正です');
define('TAR_SYSTEM_00016',            'マネージャが削除されたか存在しません。');
define('TAR_SYSTEM_00017',            'グループが削除されたか存在しません。');
define('TAR_SYSTEM_00018',            'クーポンが削除されたか存在しません。');
?>
