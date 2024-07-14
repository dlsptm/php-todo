<?php
  session_destroy();
  header('Location: index.php?t=user&a=login');
  exit;