## Benchmark

See [benchmark.sh](https://github.com/flyimg/flyimg/blob/main/benchmark.sh) for more details.

Requires: [Apache Ab](https://httpd.apache.org/docs/2.4/programs/ab.html)

```sh
./benchmark.sh
```

Latest Results:

```sh
------------------------------------------------------------------------------
------------------------------------------------------------------------------
Crop http://localhost:8081/upload/w_500,h_500,c_1/wat-arun.jpg
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1903618 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.18.0
Server Hostname:        localhost
Server Port:            8081

Document Path:          /upload/w_500,h_500,c_1/wat-arun.jpg
Document Length:        130752 bytes

Concurrency Level:      4
Time taken for tests:   2.098 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      131423000 bytes
HTML transferred:       130752000 bytes
Requests per second:    476.68 [#/sec] (mean)
Time per request:       8.391 [ms] (mean)
Time per request:       2.098 [ms] (mean, across all concurrent requests)
Transfer rate:          61178.30 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       0
Processing:     3    5  34.0      3    1056
Waiting:        3    5  33.4      3    1054
Total:          3    5  34.0      4    1056

Percentage of the requests served within a certain time (ms)
  50%      4
  66%      4
  75%      4
  80%      4
  90%      4
  95%      5
  98%      6
  99%      6
 100%   1056 (longest request)

------------------------------------------------------------------------------
------------------------------------------------------------------------------
Simple Resize http://localhost:8081/upload/w_500,h_500/wat-arun.jpg
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1903618 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.18.0
Server Hostname:        localhost
Server Port:            8081

Document Path:          /upload/w_500,h_500/wat-arun.jpg
Document Length:        95525 bytes

Concurrency Level:      4
Time taken for tests:   1.010 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      96195000 bytes
HTML transferred:       95525000 bytes
Requests per second:    989.84 [#/sec] (mean)
Time per request:       4.041 [ms] (mean)
Time per request:       1.010 [ms] (mean, across all concurrent requests)
Transfer rate:          92985.65 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:     3    4   3.3      3      61
Waiting:        3    4   3.2      3      61
Total:          3    4   3.3      4      61

Percentage of the requests served within a certain time (ms)
  50%      4
  66%      4
  75%      4
  80%      4
  90%      4
  95%      5
  98%      6
  99%      8
 100%     61 (longest request)

------------------------------------------------------------------------------
------------------------------------------------------------------------------
Simple Resize with refresh http://localhost:8081/upload/w_500,h_500,rf_1/wat-arun.jpg
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1903618 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.18.0
Server Hostname:        localhost
Server Port:            8081

Document Path:          /upload/w_500,h_500,rf_1/wat-arun.jpg
Document Length:        95523 bytes

Concurrency Level:      4
Time taken for tests:   203.944 seconds
Complete requests:      1000
Failed requests:        936
   (Connect: 0, Receive: 0, Length: 936, Exceptions: 0)
Non-2xx responses:      913
Total transferred:      8241565 bytes
HTML transferred:       7937078 bytes
Requests per second:    4.90 [#/sec] (mean)
Time per request:       815.778 [ms] (mean)
Time per request:       203.944 [ms] (mean, across all concurrent requests)
Transfer rate:          39.46 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       2
Processing:    95  813 398.7   1009    1434
Waiting:       95  813 398.7   1009    1434
Total:         95  813 398.7   1009    1434

Percentage of the requests served within a certain time (ms)
  50%   1009
  66%   1043
  75%   1068
  80%   1082
  90%   1123
  95%   1164
  98%   1253
  99%   1321
 100%   1434 (longest request)

------------------------------------------------------------------------------
------------------------------------------------------------------------------
Resize http://localhost:8081/upload/w_500,h_500,rz_1/wat-arun.jpg
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1903618 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.18.0
Server Hostname:        localhost
Server Port:            8081

Document Path:          /upload/w_500,h_500,rz_1/wat-arun.jpg
Document Length:        95823 bytes

Concurrency Level:      4
Time taken for tests:   2.104 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      96493000 bytes
HTML transferred:       95823000 bytes
Requests per second:    475.39 [#/sec] (mean)
Time per request:       8.414 [ms] (mean)
Time per request:       2.104 [ms] (mean, across all concurrent requests)
Transfer rate:          44796.88 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:     3    5  35.6      4    1128
Waiting:        3    5  35.6      3    1128
Total:          3    5  35.6      4    1128

Percentage of the requests served within a certain time (ms)
  50%      4
  66%      4
  75%      4
  80%      4
  90%      4
  95%      4
  98%      5
  99%      8
 100%   1128 (longest request)

------------------------------------------------------------------------------
------------------------------------------------------------------------------
Rotate http://localhost:8081/upload/r_-45,w_400,h_400/wat-arun.jpg
------------------------------------------------------------------------------
This is ApacheBench, Version 2.3 <$Revision: 1903618 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.18.0
Server Hostname:        localhost
Server Port:            8081

Document Path:          /upload/r_-45,w_400,h_400/wat-arun.jpg
Document Length:        68958 bytes

Concurrency Level:      4
Time taken for tests:   1.808 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      69628000 bytes
HTML transferred:       68958000 bytes
Requests per second:    553.16 [#/sec] (mean)
Time per request:       7.231 [ms] (mean)
Time per request:       1.808 [ms] (mean, across all concurrent requests)
Transfer rate:          37612.77 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:     3    5  25.7      3     805
Waiting:        3    5  25.7      3     805
Total:          3    5  25.7      4     806

Percentage of the requests served within a certain time (ms)
  50%      4
  66%      4
  75%      4
  80%      4
  90%      4
  95%      4
  98%      7
  99%     10
 100%    806 (longest request)
```
