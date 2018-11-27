## AbuseIPDBtest

Small test script to test my ipset block list against the AbuseIPDB list to see if the ip is still being abused.

Usage instructions:  
1. Extract your ipset ip list with command.  
ipset save <ipset group> -file <ipset group>.ipset
  
2. Put the ipset file in same folder as the "test.php" file.

3. Put your AbuseIPDB API key in test.php.

4. Put your <ipset group> on field "ipsetname"

5. run "php test.php"

Notes:  
to delete not founds ips from ipset use command "php test.php -f 1"  
to delete with zero abuseConfidenceScore ips from ipset use command "php test.php -z 1"

Program will list all ips as not found if the API don't return any results.  
And all those with Confidence Score less than 100 as found with the confidence score.
