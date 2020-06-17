<?php

use App\Auth;

Auth::check();
session_destroy();
header('Location:'.$router->url('login').'?logout=1');