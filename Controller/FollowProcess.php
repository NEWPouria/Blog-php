<?php
session_start();
header("Content-Type: application/json");

// بررسی لاگین بودن کاربر
if (!isset($_SESSION['USER'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// دریافت داده‌های ارسالی
$input = json_decode(file_get_contents('php://input'), true);
$targetUserId = $input['user_id'] ?? null;
$action = $input['action'] ?? null; // 'follow' یا 'unfollow'

// اعتبارسنجی داده‌ها
if (!$targetUserId || !in_array($action, ['follow', 'unfollow'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

require_once '../Model/Users.php';
require_once '../Model/FollowService.php';

$LoggedUser = new Users;
// پردازش فالو/آنفالو (مثال با دیتابیس فرضی)
$followerId = $LoggedUser->FindUserID($_SESSION["USER"]);
// ID کاربر لاگین‌شده
$isFollowing = false;


if ($action === 'follow') {
    // کد برای فالو کردن (مثلاً INSERT در دیتابیس)
    $followService = new FollowService();
    $followService->Follow($targetUserId, $followerId);
    $isFollowing = true;
} else {
    // کد برای آنفالو کردن (مثلاً DELETE از دیتابیس)
    $followService = new FollowService();
    $followService->UnFollow($targetUserId, $followerId);
    $isFollowing = false;
}

// پاسخ به جاوااسکریپت
echo json_encode([

    'followerid' => $followerId,
    'success' => true,
    'following' => $isFollowing,
    'message' => $action === 'follow' ? 'Followed successfully' : 'Unfollowed successfully'
]);