<?php

use Bitrix\Main\Diag\Debug;

Debug::writeToFile(
    "init loaded",
    "INIT",
    "/upload/debug.log"
);