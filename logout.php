<?php
session_start();
session_unset();
session_destroy();
header("Location: first.html");
exit();