<?php
define("TRANSACTION_ID_DEFAULT_LENGTH",                     32);
define("TRANSACTION_PAGE_DEFAULT_LIMIT",                    10);

define("TRANSACTION_STATUS_ID_PENDING",                     1);
define("TRANSACTION_STATUS_ID_SUCCESSFUL",                  2);
define("TRANSACTION_STATUS_ID_FAILED",                      3);
define("TRANSACTION_STATUS_ID_CANCELLED",                   4);

define("TRANSACTION_FLAG_LIVE",                             "LIVE");
define("TRANSACTION_FLAG_WEBSERVER_TEST",                   "WEBSERVERTEST");
define("TRANSACTION_FLAG_WEBSERVICE_TEST",                  "WEBSERVICETEST");
define("TRANSACTION_FLAG_LOCALSERVER_TEST",                 "LOCALSERVERTEST");