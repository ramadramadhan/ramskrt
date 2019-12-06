<?php ?><?php
function request($url, $token = null, $data = null, $pin = null) {
    $header[] = "Host: api.gojekapi.com";
    $header[] = "User-Agent: okhttp/3.10.0";
    $header[] = "Accept: application/json";
    $header[] = "Accept-Language: en-ID";
    $header[] = "Content-Type: application/json; charset=UTF-8";
    $header[] = "X-AppVersion: 3.16.1";
    $header[] = "X-UniqueId: 106605982657" . mt_rand(1000, 9999);
    $header[] = "Connection: keep-alive";
    $header[] = "X-User-Locale: en_ID";
    $header[] = "X-Location: -7.613805,110.633676";
    $header[] = "X-Location-Accuracy: 3.0";
    if ($pin):
        $header[] = "pin: $pin";
    endif;
    if ($token):
        $header[] = "Authorization: Bearer $token";
    endif;
    $c = curl_init("https://api.gojekapi.com" . $url);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    if ($data):
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_POST, true);
    endif;
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, $header);
    if ($socks):
        curl_setopt($c, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($c, CURLOPT_PROXY, $socks);
    endif;
    $response = curl_exec($c);
    $httpcode = curl_getinfo($c);
    if (!$httpcode) return false;
    else {
        $header = substr($response, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
        $body = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
    }
    $json = json_decode($body, true);
    return $json;
}
function curl($url, $fields = null, $headers = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($fields !== null) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }
    if ($headers !== null) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result, $httpcode);
}
function nama() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $ex = curl_exec($ch);
    preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
    return $name[2][mt_rand(0, 14) ];
}
function register($no) {
    $nama = nama();
    $email = str_replace(" ", "", $nama) . mt_rand(100, 999);
    $data = '{"name":"' . nama() . '","email":"' . $email . '@gmail.com","phone":"+' . $no . '","signed_up_country":"ID"}';
    $register = request("/v5/customers", "", $data);
    if ($register['success'] == 1) {
        return $register['data']['otp_token'];
    } else {
        return false;
    }
}
function verif($otp, $token) {
    $data = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
    $verif = request("/v5/customers/phone/verify", "", $data);
    if ($verif['success'] == 1) {
        return $verif['data']['access_token'];
    } else {
        return false;
    }
}
function login($no) {
    $nama = nama();
    $email = str_replace(" ", "", $nama) . mt_rand(100, 999);
    $data = '{"phone":"+' . $no . '"}';
    $register = request("/v4/customers/login_with_phone", "", $data);
    print_r($register);
    if ($register['success'] == 1) {
        return $register['data']['login_token'];
    } else {
        return false;
    }
}
function veriflogin($otp, $token) {
    $data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
    $verif = request("/v4/customers/login/verify", "", $data);
    if ($verif['success'] == 1) {
        return $verif['data']['access_token'];
    } else {
        return false;
    }
}
function claim($token) {
    sleep(5);
    echo "
SANS19 :";
    $datas = '{"promo_code":"GOFOODSANTAI19"}';
    $claim = request("/go-promotions/v1/promotions/enrollments", $token, $datas);
    if ($claim['success'] == 1) {
        return $claim['data']['message'];
    } else {
        return false;
    }
}
function claim1($token) {
    sleep(5);
    echo "VOC GOFOOD 15K :";
    $data1 = '{"promo_code":"GOFOODBOBA10"}';
    $claim1 = request("/go-promotions/v1/promotions/enrollments", $token, $data1);
    if ($claim1['success'] == 1) {
        return $claim1['data']['message'];
    } else {
        return false;
    }
}
function claim2($token) {
    sleep(5);
    echo "SANS 11:";
    $data2 = '{"promo_code":"GOFOODSANTAI11"}';
    $claim2 = request("/go-promotions/v1/promotions/enrollments", $token, $data2);
    if ($claim2['success'] == 1) {
        return $claim2['data']['message'];
    } else {
        return false;
    }
}
function claim3($token) {
    sleep(5);
    echo "SANTAI 08:";
    $data3 = '{"promo_code":"GOFOODSANTAI08"}';
    $claim3 = request("/go-promotions/v1/promotions/enrollments", $token, $data3);
    if ($claim3['success'] == 1) {
        return $claim3['data']['message'];
    } else {
        return false;
    }
}
utama:
    system('clear');
    echo "
GACHA KONTOL MEMANG ANJENG MISI BOBA07 BERHASIL
-----MENU-----: 
1.BY KEVIN
2. PILIH 2 KONTOL
Masukin Pilihan = ";
    $type = trim(fgets(STDIN));
    if ($type == 2) {
        $file = "token.txt";
        $results = fopen($file, "a");
        Daftar:
            echo "Menu Daftar Akun
";
            echo "Kode 62 untuk ID and 1 untuk US 
";
            $proxy = "
";
            error_reporting(E_ERROR | E_PARSE);
            echo "Enter Number: ";
            $nope = trim(fgets(STDIN));
            fwrite($results, $nope . "
");
            $register = register($nope);
            if ($register == false) {
                echo "GANTI NOMER KONTOL
";
                gotoDaftar;
            } else {
                echo "Masukin OTP: ";
                ulang:
                    $otp = trim(fgets(STDIN));
                    $verif = verif($otp, $register);
                    if ($verif == false) {
                        echo "
DAFTAR GAGAL! 
Mungkin OTP Salah, 
Coba Masukin Ulang : ";
                        gotoulang;
                    } else {
                        echo "Token Anda : " . $verif;
                        echo "
Costum pin atau default pin? 
1 = Costum
2 = default (12233)";
                        echo "
 Masukin Pilihan : ";
                        $pil = trim(fgets(STDIN));
                        if ($pil == 1) {
                            echo "
Masukin Pin :";
                            $pins = trim(fgets(STDIN));
                            $header[] = "User-uuid: $uuid";
                            $header[] = "Authorization: Bearer $verif";
                            $setpin = curl('https://api.gojekapi.com/wallet/pin', '{"pin":"' . $pins . '"}', $header, $proxy);
                            echo "
Masukin OTP setpin = ";
                            $otp_pins = trim(fgets(STDIN));
                            $header[] = "otp: $otp_pins";
                            $verif_setpins = curl('https://api.gojekapi.com/wallet/pin', '{"pin":"' . $pins . '"}', $header, $proxy);
                            echo "
Loading";
                            sleep(1);
                            echo ".";
                            sleep(1);
                            echo ".";
                            sleep(1);
                            echo ".";
                            echo "
Token Anda Telah Tersimpan di token.txt
";
                            fwrite($results, $verif . "
");
                            echo "
Proses claim dimulai...
";
                            $claim = claim($verif);
                            if ($claim == false) {
                                echo "[1] Error Bro
";
                            } else {
                                echo "[1] ";
                                echo $claim . " 
";
                            }
                            $claim2 = claim2($verif);
                            if ($claim2 == false) {
                                echo "[2] Error Bro
";
                            } else {
                                echo "[2] ";
                                echo $claim2 . " 
";
                            }
                            $claim3 = claim3($verif);
                            if ($claim3 == false) {
                                echo "[2] Error Bro
";
                            } else {
                                echo "[2] ";
                                echo $claim3 . " 
";
                            }
                        } else {
                            echo "
Pin Default : 112233";
                            $pin = 112233;
                            $header[] = "User-uuid: $uuid";
                            $header[] = "Authorization: Bearer $verif";
                            $setpin = curl('https://api.gojekapi.com/wallet/pin', '{"pin":"' . $pin . '"}', $header, $proxy);
                            echo "
Masukin OTP setpin = ";
                            $otp_pin = trim(fgets(STDIN));
                            $header[] = "otp: $otp_pin";
                            $verif_setpin = curl('https://api.gojekapi.com/wallet/pin', '{"pin":"' . $pin . '"}', $header, $proxy);
                            echo "Loading...";
                            sleep(3);
                            echo "
Token Anda Telah Tersimpan di token.txt
";
                            fwrite($results, $verif . "
");
                            echo "
Ngeclaim kode sabar ya kontol
";
                            $claim = claim($verif);
                            if ($claim == false) {
                                echo "[1] 1 GA AKAN DAPAT KONTOL
";
                            } else {
                                echo "[1] ";
                                echo $claim . " 
";
                            }
                            $claim3 = claim3($verif);
                            if ($claim3 == false) {
                                echo "[2] KONTOL ULANG LAGI ANJING
";
                            } else {
                                echo "[2] ";
                                echo $claim3 . " 
";
                            }
                            $claim2 = claim2($verif);
                            if ($claim2 == false) {
                                echo "[3] KONTOL ULANG LAGI ANJING
";
                            } else {
                                echo "[3] ";
                                echo $claim2 . " 
";
                            }
                        }
                        echo "
[!] Tekan enter untuk kembali ke menu [!]";
                        $asal = trim(fgets(STDIN));
                        gotoutama;
                    }
                }
            } else if ($type == 3) {
                system('clear');
                echo "    ======= MENU SPESIAL UNTUK KAMU ==========";
                echo "
======= AUTO REDEEM MASSAL DENGAN TOKEN ========
";
                echo "
Script ini berjalanan dengan no.hp + token yang didaftarkan melalui fitur nomor 4
    No Hp tanpa token berarti Gagal Mendaftar, Harap Maklum 
";
                echo "
";
                $fh = fopen("regtoken.txt", "r");
                $fh = fopen("nohp.txt", "r");
                $no = 0;
                $file = file_get_contents("regtoken.txt");
                $file2 = file_get_contents("nohp.txt");
                $data = explode("
", str_replace("
", "", $file));
                for ($a = 0;$a < count($data);$a++) {
                    $data2 = explode("
", str_replace("
", "", $file2));
                    for ($a = 0;$a < count($data2);$a++) {
                        $token = $data[$a];
                        $nohp = $data2[$a];
                        $no++;
                        echo "Proses.. 
";
                        $claim = claim($token);
                        if ($claim == false) {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                        } else {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token
";
                            echo "   ";
                            echo $claim . " 
=============================
";
                        }
                        $claim3 = claim3($token);
                        if ($claim3 == false) {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                        } else {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token.";
                            echo " ";
                            echo $claim3 . " 
=============================
";
                        }
                        $claim3 = claim3($token);
                        if ($claim2 == false) {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                        } else {
                            echo "   " . $nohp;
                            echo "
";
                            echo $no . ") Token : $token.";
                            echo " ";
                            echo $claim2 . " 
=============================
";
                        }
                    }
                    gotoutama;
                }
            } else if ($type == 4) {
                $file = "regtoken.txt";
                $file2 = "nohp.txt";
                $results = fopen($file, "a");
                $results2 = fopen($file2, "a");
                Regis:
                    echo "
Menu Daftar Akun
";
                    echo "Kode 62 untuk ID and 1 untuk US 
";
                    echo "Enter Number: ";
                    $nope = trim(fgets(STDIN));
                    fwrite($results2, $nope . "
");
                    $register = register($nope);
                    if ($register == false) {
                        echo "Failed to Get OTP, Use Unregistered Number!
";
                        gotoRegis;
                    } else {
                        otps:
                            echo "Masukin OTP: ";
                            $otp = trim(fgets(STDIN));
                            $verif = verif($otp, $register);
                            fwrite($results, $verif . "
");
                            fclose($fh);
                            if ($verif == false) {
                                echo "Regis gagal! Cek Otp!!
: ";
                                gotootps;
                                fclose($fh);
                            } else {
                                echo "Regis sukses!
";
                                echo "Token tersimpan di regtoken.txt 
";
                                echo "Token: " . $verif;
                                echo "
Menu selanjutnya:";
                                echo " 
1.Menu Utama
2.Reedem by login
3.Regis lagi";
                                echo "
 Masukin Pilihan =";
                                $pilih = trim(fgets(STDIN));
                                if ($pilih == 1) {
                                    gotoutama;
                                } else if ($pilih == 2) {
                                    gotolog;
                                } else {
                                    gotoRegis;
                                }
                            }
                        }
                    } else if ($type == 5) {
                        system('clear');
                        echo "==MENAMPILKAN DAFTAR NOMOR HP + TOKEN YANG TEDAFTAR==
";
                        echo "    =====NOMOR TANPA TOKEN BERARTI GAGAL DAFTAR======
";
                        $fh = fopen("regtoken.txt", "r");
                        $fh = fopen("nohp.txt", "r");
                        $no = 0;
                        $file = file_get_contents("regtoken.txt");
                        $file2 = file_get_contents("nohp.txt");
                        $data = explode("
", str_replace("
", "", $file));
                        for ($a = 0;$a < count($data);$a++) {
                            $data2 = explode("
", str_replace("
", "", $file2));
                            for ($a = 0;$a < count($data2);$a++) {
                                $token = $data[$a];
                                $nohp = $data2[$a];
                                $no++;
                                echo $no . ") $nohp
";
                                echo "$token
===================
";
                            }
                        }
                        echo "
Menu selanjutnya:";
                        echo " 
1.Menu Utama
2.Reedem by login
3.Regis lagi";
                        echo "
 Masukin Pilihan =";
                        $pilih = trim(fgets(STDIN));
                        if ($pilih == 1) {
                            gotoutama;
                        } else if ($pilih == 2) {
                            gotolog;
                        } else {
                            gotoRegis;
                        }
                    } else if ($type == 7) {
                        system('clear');
                        echo "==MENAMPILKAN DAFTAR NOMOR HP + TOKEN BY LOGIN==
";
                        echo "    =====NOMOR TANPA TOKEN BERARTI GAGAL LOGIN======
";
                        $fh = fopen("tokenbylogin.txt", "r");
                        $fh = fopen("nohpbylogin.txt", "r");
                        $no = 0;
                        $file = file_get_contents("tokenbylogin.txt");
                        $file2 = file_get_contents("nohpbylogin.txt");
                        $data = explode("
", str_replace("
", "", $file));
                        for ($a = 0;$a < count($data);$a++) {
                            $data2 = explode("
", str_replace("
", "", $file2));
                            for ($a = 0;$a < count($data2);$a++) {
                                $token = $data[$a];
                                $nohp = $data2[$a];
                                $no++;
                                echo $no . ") $nohp
";
                                echo "$token
===================
";
                            }
                        }
                        echo "
Menu selanjutnya:";
                        echo " 
1.Menu Utama
2.Lagi login
3.Regis lagi";
                        echo "
 Masukin Pilihan =";
                        $pilih = trim(fgets(STDIN));
                        if ($pilih == 1) {
                            gotoutama;
                        } else if ($pilih == 2) {
                            gotolog1;
                        } else {
                            gotoRegis;
                        }
                    } else if ($type == 6) {
                        system('clear');
                        echo "    ======= MENU SPESIAL UNTUK KAMU ==========";
                        echo "
======= AUTO REDEEM MASSAL DENGAN TOKEN ========
";
                        echo "
Script ini berjalanan dengan no.hp + token yang didaftarkan melalui fitur nomor 7
AUTO REDEEM MASSAL BY LOGIN
    No Hp tanpa token berarti Gagal Mendaftar, Harap Maklum 
";
                        echo "
";
                        $fh = fopen("regtokenbylogin.txt", "r");
                        $fh = fopen("regnohpbylogin.txt", "r");
                        $no = 0;
                        $file = file_get_contents("regtokenbylogin.txt");
                        $file2 = file_get_contents("regnohpbylogin.txt");
                        $data = explode("
", str_replace("
", "", $file));
                        for ($a = 0;$a < count($data);$a++) {
                            $data2 = explode("
", str_replace("
", "", $file2));
                            for ($a = 0;$a < count($data2);$a++) {
                                $token = $data[$a];
                                $nohp = $data2[$a];
                                $no++;
                                echo "Proses.. 
";
                                $claim = claim($token);
                                if ($claim == false) {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                                } else {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token
";
                                    echo "   ";
                                    echo $claim . " 
=============================
";
                                }
                                $claim2 = claim2($token);
                                if ($claim2 == false) {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                                } else {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token.";
                                    echo " ";
                                    echo $claim2 . " 
=============================
";
                                }
                                $claim3 = claim3($token);
                                if ($claim3 == false) {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token
 ", "  HASIL : Error Bro
=========================
";
                                } else {
                                    echo "   " . $nohp;
                                    echo "
";
                                    echo $no . ") Token : $token.";
                                    echo " ";
                                    echo $claim3 . " 
=============================
";
                                }
                            }
                            gotoutama;
                        }
                    } else if ($type == 1) {
                        log:
                            $file = "tokenbylogin.txt";
                            $file2 = "nohpbylogin.txt";
                            $results = fopen($file, "a");
                            $results2 = fopen($file2, "a");
                            echo "
Menu Redeem By Login 
";
                            $secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
                            $headers = array();
                            $headers[] = 'Content-Type: application/json';
                            $headers[] = 'X-AppVersion: 3.33.1';
                            $headers[] = "X-Uniqueid: ac94e5d0e7f3f" . rand(100, 999);
                            $headers[] = 'X-Location: -6.9726247,110.4043687';
                            echo "Nomer HP: ";
                            $number = trim(fgets(STDIN));
                            $login = curl('https://api.gojekapi.com/v3/customers/login_with_phone', '{"phone":"+' . $number . '"}', $headers);
                            $logins = json_decode($login[0]);
                            if ($logins->success == true) {
                                otpp:
                                    echo "OTP: ";
                                    $otp = trim(fgets(STDIN));
                                    $data1 = '{"scopes":"gojek:customer:transaction gojek:customer:readonly","grant_type":"password","login_token":"' . $logins->data->login_token . '","otp":"' . $otp . '","client_id":"gojek:cons:android","client_secret":"' . $secret . '"}';
                                    $verif = curl('https://api.gojekapi.com/v3/customers/token', $data1, $headers);
                                    $verifs = json_decode($verif[0]);
                                    if ($verifs->success == true) {
                                        $token = $verifs->data->access_token;
                                        echo "
Token + no hp anda tersimpan di token.txt";
                                        fwrite($results, $token . "
");
                                        fwrite($results2, $number . "
");
                                        fclose($fh);
                                        echo "
Proses claim 
";
                                        $claim = claim($token);
                                        if ($claim == false) {
                                            echo "[1] Error Bro
";
                                        } else {
                                            echo "[1] ";
                                            echo $claim . " 
";
                                        }
                                        $claim2 = claim2($token);
                                        if ($claim2 == false) {
                                            echo "[3] Error Bro
";
                                        } else {
                                            echo "[3] ";
                                            echo $claim2 . " 
";
                                        }
                                        $claim3 = claim3($token);
                                        if ($claim3 == false) {
                                            echo "[4] Error Bro
";
                                        } else {
                                            echo "[4] ";
                                            echo $claim3 . " 
";
                                        }
                                        echo "
Menu selanjutnya:";
                                        echo " 
1.Menu Utama
2.Reedem by login
3.Regis lagi";
                                        echo "
 Masukin Pilihan =";
                                        $pilih = trim(fgets(STDIN));
                                        if ($pilih == 1) {
                                            gotoutama;
                                        } else if ($pilih == 2) {
                                            gotolog;
                                        } else {
                                            gotoRegis;
                                        }
                                    } else {
                                        echo "OTP salah! Coba Lagi 
";
                                        gotootpp;
                                    }
                                } else {
                                    echo "Nomornya kok belum terdaftar?!
";
                                    gotolog;
                                }
                            } else if ($type == 8) {
                                log1:
                                    $file = "regtokenbylogin.txt";
                                    $file2 = "regnohpbylogin.txt";
                                    $results = fopen($file, "a");
                                    $results2 = fopen($file2, "a");
                                    echo "
LOGIN untuk Bisa REDEEM MASSAL
";
                                    $secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
                                    $headers = array();
                                    $headers[] = 'Content-Type: application/json';
                                    $headers[] = 'X-AppVersion: 3.33.1';
                                    $headers[] = "X-Uniqueid: ac94e5d0e7f3f" . rand(100, 999);
                                    $headers[] = 'X-Location: -6.9726247,110.4043687';
                                    echo "Nomer HP: ";
                                    $number = trim(fgets(STDIN));
                                    $login = curl('https://api.gojekapi.com/v3/customers/login_with_phone', '{"phone":"+' . $number . '"}', $headers);
                                    $logins = json_decode($login[0]);
                                    if ($logins->success == true) {
                                        otpp1:
                                            echo "OTP: ";
                                            $otp = trim(fgets(STDIN));
                                            $data1 = '{"scopes":"gojek:customer:transaction gojek:customer:readonly","grant_type":"password","login_token":"' . $logins->data->login_token . '","otp":"' . $otp . '","client_id":"gojek:cons:android","client_secret":"' . $secret . '"}';
                                            $verif = curl('https://api.gojekapi.com/v3/customers/token', $data1, $headers);
                                            $verifs = json_decode($verif[0]);
                                            if ($verifs->success == true) {
                                                $token = $verifs->data->access_token;
                                                fwrite($results, $token . "
");
                                                echo "
Token + no hp anda tersimpan di token.txt";
                                                fwrite($results2, $number . "
");
                                                echo "
Menu selanjutnya:";
                                                echo " 
1.Menu Utama
2.Lagi login
3.Regis lagi";
                                                echo "
 Masukin Pilihan =";
                                                $pilih = trim(fgets(STDIN));
                                                if ($pilih == 1) {
                                                    gotoutama;
                                                } else if ($pilih == 2) {
                                                    gotolog1;
                                                } else {
                                                    gotoRegis;
                                                }
                                                fclose($fh);
                                            } else {
                                                echo "OTP salah! Coba Lagi 
";
                                                gotootpp1;
                                            }
                                        } else {
                                            echo "Yaelah Nomor belum didaftar!
";
                                            gotolog1;
                                        }
                                    } ?>