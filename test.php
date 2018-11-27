<?php

//// to delete not founds ips from ipset use command "php test.php -f 1"
//// to delete with zero abuseConfidenceScore ips from ipset use command "php test.php -z 1"
    $options = getopt("f:z:");
    $ipsetname = 'evil_ips';
    $abuseipdbAPIKey = '<Abuseipdb API Key here>';
    $logdays = '365';
    $separator = "\r\n";
    $ipsetlist = file_get_contents($ipsetname . ".ipset");
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
                $displayline = '';
                if ($data[0]['abuseConfidenceScore'] < 100)
                {
                    $displayline .= $data[0]['ip'] . " Score: " . $data[0]['abuseConfidenceScore']  . " -- found";
                }
                if ($data[0]['abuseConfidenceScore'] == 0 and isset($options['z']) and $options['z'] == 1)
                {
                    exec("ipset del " . $ipsetname . " " . $data[0]['ip']);
                    $displayline .= ' -- deleted from ipset.';
                }
                if ($displayline != '')
                {
                    echo $displayline . "\n";
                }
            }
            else
            {
                echo $ip . " -- not found";  
                if (isset($options['f']) and $options['f'] == 1)
                {
                    echo ' -- deleted from ipset.';
                    exec("ipset del " . $ipsetname . " " . $ip);
                }
                echo "\n";
            }
        }
        $line = strtok( $separator );
    }    

?>