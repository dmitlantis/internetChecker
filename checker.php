<?php

const SITES
= [
    'ya.ru',
    'vk.com',
    'slack.com',
    'aliexpress.ru',
    'whatsapp.com',
    't.me',
];

const CYCLE = 2;

$curl  = curl_init();
$index = 0;

function wlog(...$args)
{
    print PHP_EOL . date('[H:i:s] ', time() + 3600 * 3) . implode(' ', $args);
}

while (1) {
    $site = SITES[$index % count(SITES)];
    curl_setopt($curl, CURLOPT_URL, "http://$site/");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'accept-encoding: gzip, deflate, br',
        'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        'cache-control: max-age=0',
        'cookie: b=bsh2p1ngqar4siosjp0keufp4; x=bsh2p1ngqar4siosjp0keufp4.1598364992',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: cross-site',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36',
    ]);
    wlog("requesting... $site");
    $response = curl_exec($curl);
    if ($error = curl_errno($curl)) {
        wlog("ErrorCode: #$error,", ' httpCode:', curl_getinfo($curl, CURLINFO_HTTP_CODE), " reason:", curl_error($curl), 'last ip: ', curl_getinfo($curl, CURLINFO_PRIMARY_IP));
        wlog('response: ' . $response);
    }
    if ($index % count(SITES) == 0) {
        sleep(CYCLE + ((rand(0, 1000) - 500) / 1000));
    }
    $index++;
}