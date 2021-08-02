<?php

$url = "https://apps.procuraduria.gov.co/webcert/Certificado.aspx?t=dAylAkFT%2fgSkkvpDoI89aORiq2C8LI3z9uHAnBFaF08%2f32nPrGQhH4HhIkyJHgMD30HMssetl++SaqHgMODdjawUxwsjuRTbKEb35HieFxdipvVlkwZR+TABjraDbp0hlrwP2GCLMX8Rl+aw1u6GwgGEkvY16WZ4qm8sL17YyY5FLVGWSy+Eys6FEeWWKx6G+Rm%2fXaHa4L9eayTZkhzvIL4bJrDB501fmA%2fwv3NJ74I%3d&tpo=1";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:90.0) Gecko/20100101 Firefox/90.0",
    "Accept: */*",
    "Accept-Language: es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3",
    "X-Requested-With: XMLHttpRequest",
    "X-MicrosoftAjax: Delta=true",
    "Cache-Control: no-cache",
    "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
    "Origin: https://apps.procuraduria.gov.co",
    "Connection: keep-alive",
    "Referer: https://apps.procuraduria.gov.co/webcert/Certificado.aspx?t=dAylAkFT/gSkkvpDoI89aORiq2C8LI3z9uHAnBFaF08/32nPrGQhH4HhIkyJHgMD30HMssetl++SaqHgMODdjawUxwsjuRTbKEb35HieFxdipvVlkwZR+TABjraDbp0hlrwP2GCLMX8Rl+aw1u6GwgGEkvY16WZ4qm8sL17YyY5FLVGWSy+Eys6FEeWWKx6G+Rm/XaHa4L9eayTZkhzvIL4bJrDB501fmA/wv3NJ74I=&tpo=1",
    "Cookie: ASP.NET_SessionId=jmcmdnfwqs3tjlctaf3anbyf; cookiesession1=678A3E10PQRSTUVWXYZABCOPQRST7394; _ga=GA1.3.2041897313.1627861701; _gid=GA1.3.1798654935.1627861701",
    "Sec-Fetch-Dest: empty",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Site: same-origin",
    "Pragma: no-cache",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "ctl05=UpdatePanel1%7CImageButton1&ddlTipoID=1&txtNumID=1110534050&txtRespuestaPregunta=Medellin&txtEmail=&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUKLTE2NTg5ODU2Mg8WAh4KSWRQcmVndW50YQUCMTIWAgIDD2QWAgIDD2QWAmYPZBYMAgMPDxYCHgRUZXh0BRhDb25zdWx0YSBkZSBhbnRlY2VkZW50ZXNkZAIPDxYCHgdWaXNpYmxlaBYEAgEPEGRkFgFmZAIFD2QWAgIBDxBkZBYAZAIRDw8WAh8BBS%2FCvyBDdWFsIGVzIGxhIENhcGl0YWwgZGUgQW50aW9xdWlhIChzaW4gdGlsZGUpP2RkAh0PDxYCHwJoZGQCJQ8PFgIfAQVIRmVjaGEgZGUgY29uc3VsdGE6IGRvbWluZ28sIGFnb3N0byAwMSwgMjAyMSAtIEhvcmEgZGUgY29uc3VsdGE6IDE4OjQ5OjE3ZGQCKQ8PFgIfAQUHVi4xLjAuMWRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQxJbWFnZUJ1dHRvbjHuPWlOWwxj7O1H9rR%2FQ%2F8zSUTgJP2xmw%2Brc6u6P6LFeA%3D%3D&__VIEWSTATEGENERATOR=538A70A7&__EVENTVALIDATION=%2FwEdAAs7Ryex2sTe%2B6FM9FfOY%2BHj%2BMfFIckWHtJo8mcJHXtf60jMm4Eun6Ch%2BsGQBFL6DWEkVe6Wdt5JQpNNO%2Brp7bXczd%2B4Rz0at1FkmyS31yoYKSTchSJOOjCJdHuiUEbQK6r2ZpHOBKWi14%2F6dT%2FaqDM96ZACrx5RZnllKSerU%2BIuKjGhJH0i4NdHQmmgFFeoootDB2YfWbjwUYntrwPx36EShAqw%2FI3OcaUSOxgiPcO%2FK8mZKA8jCfuhJ7mraJcEjO6RjhOcyDgZuHZsqgS9J2%2BB&__ASYNCPOST=true&ImageButton1.x=18&ImageButton1.y=2";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);
