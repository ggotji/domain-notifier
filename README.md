# 🌐 Domain Expiration Notifier (Telegram)

여러 업체의 도메인 ,호스팅 만료일을 관리하고, 만료 30일 전부터 매일 아침 텔레그램으로 자동 알림을 보내주는 PHP 프로그램입니다.

## ✨ 주요 기능
- **자동 날짜 계산**: 오늘 날짜와 도메인 만료일을 비교하여 남은 일수를 계산합니다.
- **텔레그램 알림**: 만료 30일 전부터 텔레그램 봇을 통해 실시간 메시지를 전송합니다.
- **멀티 도메인 관리**: `list.json` 파일에 업체명과 날짜만 추가하면 무제한으로 관리가 가능합니다.
- **경량화**: 별도의 DB 없이 JSON 파일과 PHP만으로 가볍게 동작합니다.

## 🛠 설치 및 설정 방법

### 1. 필수 환경
- PHP 7.4 이상
- `php-curl` 라이브러리 (텔레그램 API 전송용)

### 2. 파일 구성
- `alarm.php`: 메인 실행 로직
- `list.json`: 관리할 업체 및 도메인 만료일 목록
- `config.php`: 텔레그램 봇 토큰 및 채팅 ID (보안상 .gitignore 처리됨)

### 3. 설정 (config.php)
`config.php` 파일을 생성하고 아래 양식으로 정보를 입력합니다.
```php
<?php
$botToken = "YOUR_BOT_TOKEN";
$chatId = "YOUR_CHAT_ID";
```
### 4.실행 방법 (Crontab)
 매일 오전 9시에 자동으로 알림이 실행되도록 서버의 크론탭(crontab -e)에 아래 명령어를 등록합니다.
```bash
0 9 * * * /usr/bin/php /home/ubuntu/domain-alarm/alarm.php > /dev/null 2>&1
```
