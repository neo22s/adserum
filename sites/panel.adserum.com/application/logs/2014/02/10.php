<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2014-02-10 12:08:35 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:08:35 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: :uri ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2014-02-10 12:10:43 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:10:44 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:20:07 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:25:25 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:25:26 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:47:36 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:51:57 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:52:03 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:58:21 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: :uri ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2014-02-10 12:58:28 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:58:29 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:59:34 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 12:59:35 --- ERROR: Database_Exception [ 8192 ]: :error ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2014-02-10 13:03:34 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:04:33 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:04:42 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:04:42 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:04:43 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:04:43 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:35 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:37 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:43 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:51 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:52 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 13:10:52 --- ERROR: Kohana_Exception [ 0 ]: exception 'Predis\Connection\ConnectionException' with message 'Connection refused' in /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php:139
Stack trace:
#0 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(93): Predis\Connection\AbstractConnection->onConnectionError('Connection refu...', 111)
#1 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(69): Predis\Connection\StreamConnection->tcpStreamInitializer(Object(Predis\Connection\ConnectionParameters))
#2 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/AbstractConnection.php(95): Predis\Connection\StreamConnection->createResource()
#3 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Connection/StreamConnection.php(136): Predis\Connection\AbstractConnection->connect()
#4 /var/www/adserum/modules/ko-predis/vendor/predis/lib/Predis/Client.php(153): Predis\Connection\StreamConnection->connect()
#5 /var/www/adserum/modules/common/classes/remodel.php(79): Predis\Client->connect()
#6 /var/www/adserum/sites/panel.adserum.com/application/classes/controller/moderation.php(23): Remodel->__construct()
#7 [internal function]: Controller_Moderation->action_index()
#8 /var/www/kohana-322/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Moderation))
#9 /var/www/kohana-322/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#10 /var/www/kohana-322/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#11 /var/www/adserum/sites/panel.adserum.com/www/index.php(121): Kohana_Request->execute()
#12 {main} ~ /var/www/adserum/modules/common/classes/remodel.php [ 83 ]
2014-02-10 17:43:23 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: :uri ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2014-02-10 17:48:00 --- ERROR: ErrorException [ 8 ]: Array to string conversion ~ APPPATH/classes/controller/stats.php [ 255 ]