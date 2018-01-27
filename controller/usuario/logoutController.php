<?php
    session_destroy();
    SessionController::RemoverToken();
    header("location: ../../login");
?>

