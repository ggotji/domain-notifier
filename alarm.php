<?php
include 'config.php';  // 이 한 줄이 개인정보를 불러옵니다.

// 1. JSON 파일 불러오기
$json_data = file_get_contents(__DIR__ . '/list.json');
$services = json_decode($json_data, true);

$today = new DateTime();
$today->setTime(0, 0, 0); // 시간 단위를 맞추기 위해 초기화
$alertMessages = [];

foreach ($services as $service) {
    // JSON의 날짜에서 월-일만 추출하여 '올해' 날짜로 재설정
    $tempDate = new DateTime($service['date']);
    $monthDay = $tempDate->format('m-d');
    $currentYear = $today->format('Y');
    
    // 올해의 해당 월/일로 날짜 객체 생성
    $expiryDate = new DateTime($currentYear . '-' . $monthDay);
    
    // 만약 올해 만료일이 이미 지났다면, 내년 날짜로 계산
    if ($expiryDate < $today) {
        $expiryDate->modify('+1 year');
    }

    // 남은 일수 계산
    $interval = $today->diff($expiryDate);
    $daysLeft = (int)$interval->format("%r%a");

    // 10일 전부터 당일까지 알림
    if ($daysLeft <= 10 && $daysLeft >= 0) {
        $displayDate = $expiryDate->format('Y-m-d');
        $alertMessages[] = "🏢 **{$service['name']}**\n   └ 차기 만료일: {$displayDate} ({$daysLeft}일 남음)";
    }
}

// 2. 텔레그램 전송
if (!empty($alertMessages)) {
    $text = urlencode("📅 **연례 도메인/호스팅 만료 알림**\n\n" . implode("\n\n", $alertMessages));
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$text}&parse_mode=Markdown";
    
    file_get_contents($url);
    echo "텔레그램 전송 완료\n";
} else {
    echo "알림 대상 없음\n";
}
?>
