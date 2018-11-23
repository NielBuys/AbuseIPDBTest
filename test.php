<?php
    $ipsetlist = file_get_contents("evil_ips.ipset");
    $abuseipdbAPIKey = '<Abuseipdb API Key here>';
    $logdays = '120';
    $separator = "\r\n";
    $line = strtok($ipsetlist, $separator);
    while ($line !== false) {
        # do something with $line
        if (substr($line, 0, 3) == 'add')
        {
            $ip = str_replace("add evil_ips ", "", $line);
            $url = 'https://www.abuseipdb.com/check/' . $ip . '/json?key=' . $abuseipdbAPIKey . '&days=' . $logdays;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);            
            $data = json_decode($result, true);
            curl_close($ch);
            if (isset($data[0]['ip']))
            {
                if ($data[0]['abuseConfidenceScore'] < 100)
                {
                    echo $data[0]['ip'] . " Score: " . $data[0]['abuseConfidenceScore']  . " -- found\n";
                }
            }
            else
            {
                echo $ip . " -- not found\n";            
            }
        }
        $line = strtok( $separator );
    }    

?>