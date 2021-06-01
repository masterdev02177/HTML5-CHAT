<?php function checkCanEnter($panel) {
    // print_r($_SESSION);exit();
    if ($panel=='' && isset($_SESSION['role'])) {
        return true;
    }

    if (isset($_SESSION['role']) && $_SESSION['role']['canEnterChatAdmin'] && $_SESSION['role'][$panel]) {
        return true;
    }
    session_destroy();
    header('location:/chatadmin');
    exit;
}