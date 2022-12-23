<?php

include_once 'global.php';

echo DIR;
exit;

$token = "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIwNzUxOWVkZDhjMjE2NDc3NmM2ZmE0ZjljNmRhNWY0YiIsInJlc3VsdCI6InN1Y2Nlc3MiLCJpZCI6IjIiLCJuYW1lIjoiUmVuYW4iLCJ1bml0IjoiTWlyYXNzb2wiLCJ1c2VyIjoicmVuYW5AbWF5YS5jb20iLCJsZXZlbCI6ImNsaWVudGUiLCJpYXQiOjE2MzY3MjQxNzR9.J700uwH_lqhDmQY8H1YHMwaAnOSaktWXocZS4e0RIjU";
print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])))));
