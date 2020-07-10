<?php

declare(strict_types=1);

session_start();

unset($_SESSION);

header("Location: /index.php");
