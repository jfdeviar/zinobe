<?php

namespace Modules\Notification\Model;

// use Firebase\JWT\JWT;

use Core\Util;
use Modules\Api\Controller\ApiController;

class CellVoz
{
    protected ?String $api_key = null;
    protected ?String $account = null;
    protected ?String $password = null;
    protected ?String $api_url = 'https://api.cellvoz.co/v2/';

    public function __construct(){
        $this->loadKeys();
    }

    public function loadKeys(){
        $this->api_key = Util::$config['sms']['api_key'];
        $this->account = Util::$config['sms']['account'];
        $this->password = Util::$config['sms']['password'];
    }

    public static function sendSMS($code,$number,$text){
        ApiController::registerLog('Send sms: '.$text,'sms');
        self::sendRequest('sms/single',['number'=>$code.$number,'message'=>$text,'type'=>1]);
    }

    public function getToken(){
        return self::sendRequest('auth/login',['account'=>$this->account,'password'=>$this->password],'POST');
    }

    private static function sendRequest($calledFunction, $data,$method='POST'){

        $cellvoz = new CellVoz();
        if($calledFunction!='auth/login'){
            $token = $cellvoz->getToken();
        }
        $request_url = $cellvoz->api_url.$calledFunction;

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_URL,$request_url);
        curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);
        if($calledFunction!='auth/login') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["authorization: Bearer " . $token->token, "content-type: application/json","api-key: ".$cellvoz->api_key]);
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["content-type: application/json"]);
        }
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        if($method=="POST" || $method=="PATCH"){
            curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
        }
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl)['http_code'];
        curl_close($curl);
        ApiController::registerLog('CellVoz response: '.var_export($response,true),'sms');
        if ($err || $code!=200) {
            ApiController::registerLog('Error on SMS: '.var_export($err,true),'sms');
            return false;
        }

        return json_decode($response);
    }


    public static function getCountryCodes(){
        $codes["AF"]=["nombre"=>"Afganistán","name"=>"Afghanistan","iso2"=>"AF","iso3"=>"AFG","code"=>"93"];
        $codes["AL"]=["nombre"=>"Albania","name"=>"Albania","iso2"=>"AL","iso3"=>"ALB","code"=>"355"];
        $codes["DE"]=["nombre"=>"Alemania","name"=>"Germany","iso2"=>"DE","iso3"=>"DEU","code"=>"49"];
        $codes["AD"]=["nombre"=>"Andorra","name"=>"Andorra","iso2"=>"AD","iso3"=>"AND","code"=>"376"];
        $codes["AO"]=["nombre"=>"Angola","name"=>"Angola","iso2"=>"AO","iso3"=>"AGO","code"=>"244"];
        $codes["AI"]=["nombre"=>"Anguila","name"=>"Anguilla","iso2"=>"AI","iso3"=>"AIA","code"=>"1 264"];
        $codes["AQ"]=["nombre"=>"Antártida","name"=>"Antarctica","iso2"=>"AQ","iso3"=>"ATA","code"=>"672"];
        $codes["AG"]=["nombre"=>"Antigua y Barbuda","name"=>"Antigua and Barbuda","iso2"=>"AG","iso3"=>"ATG","code"=>"1 268"];
        $codes["SA"]=["nombre"=>"Arabia Saudita","name"=>"Saudi Arabia","iso2"=>"SA","iso3"=>"SAU","code"=>"966"];
        $codes["DZ"]=["nombre"=>"Argelia","name"=>"Algeria","iso2"=>"DZ","iso3"=>"DZA","code"=>"213"];
        $codes["AR"]=["nombre"=>"Argentina","name"=>"Argentina","iso2"=>"AR","iso3"=>"ARG","code"=>"54"];
        $codes["AM"]=["nombre"=>"Armenia","name"=>"Armenia","iso2"=>"AM","iso3"=>"ARM","code"=>"374"];
        $codes["AW"]=["nombre"=>"Aruba","name"=>"Aruba","iso2"=>"AW","iso3"=>"ABW","code"=>"297"];
        $codes["AU"]=["nombre"=>"Australia","name"=>"Australia","iso2"=>"AU","iso3"=>"AUS","code"=>"61"];
        $codes["AT"]=["nombre"=>"Austria","name"=>"Austria","iso2"=>"AT","iso3"=>"AUT","code"=>"43"];
        $codes["AZ"]=["nombre"=>"Azerbaiyán","name"=>"Azerbaijan","iso2"=>"AZ","iso3"=>"AZE","code"=>"994"];
        $codes["BE"]=["nombre"=>"Bélgica","name"=>"Belgium","iso2"=>"BE","iso3"=>"BEL","code"=>"32"];
        $codes["BS"]=["nombre"=>"Bahamas","name"=>"Bahamas","iso2"=>"BS","iso3"=>"BHS","code"=>"1 242"];
        $codes["BH"]=["nombre"=>"Bahrein","name"=>"Bahrain","iso2"=>"BH","iso3"=>"BHR","code"=>"973"];
        $codes["BD"]=["nombre"=>"Bangladesh","name"=>"Bangladesh","iso2"=>"BD","iso3"=>"BGD","code"=>"880"];
        $codes["BB"]=["nombre"=>"Barbados","name"=>"Barbados","iso2"=>"BB","iso3"=>"BRB","code"=>"1 246"];
        $codes["BZ"]=["nombre"=>"Belice","name"=>"Belize","iso2"=>"BZ","iso3"=>"BLZ","code"=>"501"];
        $codes["BJ"]=["nombre"=>"Benín","name"=>"Benin","iso2"=>"BJ","iso3"=>"BEN","code"=>"229"];
        $codes["BT"]=["nombre"=>"Bhután","name"=>"Bhutan","iso2"=>"BT","iso3"=>"BTN","code"=>"975"];
        $codes["BY"]=["nombre"=>"Bielorrusia","name"=>"Belarus","iso2"=>"BY","iso3"=>"BLR","code"=>"375"];
        $codes["MM"]=["nombre"=>"Birmania","name"=>"Myanmar","iso2"=>"MM","iso3"=>"MMR","code"=>"95"];
        $codes["BO"]=["nombre"=>"Bolivia","name"=>"Bolivia","iso2"=>"BO","iso3"=>"BOL","code"=>"591"];
        $codes["BA"]=["nombre"=>"Bosnia y Herzegovina","name"=>"Bosnia and Herzegovina","iso2"=>"BA","iso3"=>"BIH","code"=>"387"];
        $codes["BW"]=["nombre"=>"Botsuana","name"=>"Botswana","iso2"=>"BW","iso3"=>"BWA","code"=>"267"];
        $codes["BR"]=["nombre"=>"Brasil","name"=>"Brazil","iso2"=>"BR","iso3"=>"BRA","code"=>"55"];
        $codes["BN"]=["nombre"=>"Brunéi","name"=>"Brunei","iso2"=>"BN","iso3"=>"BRN","code"=>"673"];
        $codes["BG"]=["nombre"=>"Bulgaria","name"=>"Bulgaria","iso2"=>"BG","iso3"=>"BGR","code"=>"359"];
        $codes["BF"]=["nombre"=>"Burkina Faso","name"=>"Burkina Faso","iso2"=>"BF","iso3"=>"BFA","code"=>"226"];
        $codes["BI"]=["nombre"=>"Burundi","name"=>"Burundi","iso2"=>"BI","iso3"=>"BDI","code"=>"257"];
        $codes["CV"]=["nombre"=>"Cabo Verde","name"=>"Cape Verde","iso2"=>"CV","iso3"=>"CPV","code"=>"238"];
        $codes["KH"]=["nombre"=>"Camboya","name"=>"Cambodia","iso2"=>"KH","iso3"=>"KHM","code"=>"855"];
        $codes["CM"]=["nombre"=>"Camerún","name"=>"Cameroon","iso2"=>"CM","iso3"=>"CMR","code"=>"237"];
        $codes["CA"]=["nombre"=>"Canadá","name"=>"Canada","iso2"=>"CA","iso3"=>"CAN","code"=>"1"];
        $codes["TD"]=["nombre"=>"Chad","name"=>"Chad","iso2"=>"TD","iso3"=>"TCD","code"=>"235"];
        $codes["CL"]=["nombre"=>"Chile","name"=>"Chile","iso2"=>"CL","iso3"=>"CHL","code"=>"56"];
        $codes["CN"]=["nombre"=>"China","name"=>"China","iso2"=>"CN","iso3"=>"CHN","code"=>"86"];
        $codes["CY"]=["nombre"=>"Chipre","name"=>"Cyprus","iso2"=>"CY","iso3"=>"CYP","code"=>"357"];
        $codes["VA"]=["nombre"=>"Ciudad del Vaticano","name"=>"Vatican City State","iso2"=>"VA","iso3"=>"VAT","code"=>"39"];
        $codes["CO"]=["nombre"=>"Colombia","name"=>"Colombia","iso2"=>"CO","iso3"=>"COL","code"=>"57"];
        $codes["KM"]=["nombre"=>"Comoras","name"=>"Comoros","iso2"=>"KM","iso3"=>"COM","code"=>"269"];
        $codes["CG"]=["nombre"=>"República del Congo","name"=>"Republic of the Congo","iso2"=>"CG","iso3"=>"COG","code"=>"242"];
        $codes["CD"]=["nombre"=>"República Democrática del Congo","name"=>"Democratic Republic of the Congo","iso2"=>"CD","iso3"=>"COD","code"=>"243"];
        $codes["KP"]=["nombre"=>"Corea del Norte","name"=>"North Korea","iso2"=>"KP","iso3"=>"PRK","code"=>"850"];
        $codes["KR"]=["nombre"=>"Corea del Sur","name"=>"South Korea","iso2"=>"KR","iso3"=>"KOR","code"=>"82"];
        $codes["CI"]=["nombre"=>"Costa de Marfil","name"=>"Ivory Coast","iso2"=>"CI","iso3"=>"CIV","code"=>"225"];
        $codes["CR"]=["nombre"=>"Costa Rica","name"=>"Costa Rica","iso2"=>"CR","iso3"=>"CRI","code"=>"506"];
        $codes["HR"]=["nombre"=>"Croacia","name"=>"Croatia","iso2"=>"HR","iso3"=>"HRV","code"=>"385"];
        $codes["CU"]=["nombre"=>"Cuba","name"=>"Cuba","iso2"=>"CU","iso3"=>"CUB","code"=>"53"];
        $codes["CW"]=["nombre"=>"Curazao","name"=>"Curaçao","iso2"=>"CW","iso3"=>"CWU","code"=>"5999"];
        $codes["DK"]=["nombre"=>"Dinamarca","name"=>"Denmark","iso2"=>"DK","iso3"=>"DNK","code"=>"45"];
        $codes["DM"]=["nombre"=>"Dominica","name"=>"Dominica","iso2"=>"DM","iso3"=>"DMA","code"=>"1 767"];
        $codes["EC"]=["nombre"=>"Ecuador","name"=>"Ecuador","iso2"=>"EC","iso3"=>"ECU","code"=>"593"];
        $codes["EG"]=["nombre"=>"Egipto","name"=>"Egypt","iso2"=>"EG","iso3"=>"EGY","code"=>"20"];
        $codes["SV"]=["nombre"=>"El Salvador","name"=>"El Salvador","iso2"=>"SV","iso3"=>"SLV","code"=>"503"];
        $codes["AE"]=["nombre"=>"Emiratos Árabes Unidos","name"=>"United Arab Emirates","iso2"=>"AE","iso3"=>"ARE","code"=>"971"];
        $codes["ER"]=["nombre"=>"Eritrea","name"=>"Eritrea","iso2"=>"ER","iso3"=>"ERI","code"=>"291"];
        $codes["SK"]=["nombre"=>"Eslovaquia","name"=>"Slovakia","iso2"=>"SK","iso3"=>"SVK","code"=>"421"];
        $codes["SI"]=["nombre"=>"Eslovenia","name"=>"Slovenia","iso2"=>"SI","iso3"=>"SVN","code"=>"386"];
        $codes["ES"]=["nombre"=>"España","name"=>"Spain","iso2"=>"ES","iso3"=>"ESP","code"=>"34"];
        $codes["US"]=["nombre"=>"Estados Unidos de América","name"=>"United States of America","iso2"=>"US","iso3"=>"USA","code"=>"1"];
        $codes["EE"]=["nombre"=>"Estonia","name"=>"Estonia","iso2"=>"EE","iso3"=>"EST","code"=>"372"];
        $codes["ET"]=["nombre"=>"Etiopía","name"=>"Ethiopia","iso2"=>"ET","iso3"=>"ETH","code"=>"251"];
        $codes["PH"]=["nombre"=>"Filipinas","name"=>"Philippines","iso2"=>"PH","iso3"=>"PHL","code"=>"63"];
        $codes["FI"]=["nombre"=>"Finlandia","name"=>"Finland","iso2"=>"FI","iso3"=>"FIN","code"=>"358"];
        $codes["FJ"]=["nombre"=>"Fiyi","name"=>"Fiji","iso2"=>"FJ","iso3"=>"FJI","code"=>"679"];
        $codes["FR"]=["nombre"=>"Francia","name"=>"France","iso2"=>"FR","iso3"=>"FRA","code"=>"33"];
        $codes["GA"]=["nombre"=>"Gabón","name"=>"Gabon","iso2"=>"GA","iso3"=>"GAB","code"=>"241"];
        $codes["GM"]=["nombre"=>"Gambia","name"=>"Gambia","iso2"=>"GM","iso3"=>"GMB","code"=>"220"];
        $codes["GE"]=["nombre"=>"Georgia","name"=>"Georgia","iso2"=>"GE","iso3"=>"GEO","code"=>"995"];
        $codes["GH"]=["nombre"=>"Ghana","name"=>"Ghana","iso2"=>"GH","iso3"=>"GHA","code"=>"233"];
        $codes["GI"]=["nombre"=>"Gibraltar","name"=>"Gibraltar","iso2"=>"GI","iso3"=>"GIB","code"=>"350"];
        $codes["GD"]=["nombre"=>"Granada","name"=>"Grenada","iso2"=>"GD","iso3"=>"GRD","code"=>"1 473"];
        $codes["GR"]=["nombre"=>"Grecia","name"=>"Greece","iso2"=>"GR","iso3"=>"GRC","code"=>"30"];
        $codes["GL"]=["nombre"=>"Groenlandia","name"=>"Greenland","iso2"=>"GL","iso3"=>"GRL","code"=>"299"];
        $codes["GP"]=["nombre"=>"Guadalupe","name"=>"Guadeloupe","iso2"=>"GP","iso3"=>"GLP","code"=>"590"];
        $codes["GU"]=["nombre"=>"Guam","name"=>"Guam","iso2"=>"GU","iso3"=>"GUM","code"=>"1 671"];
        $codes["GT"]=["nombre"=>"Guatemala","name"=>"Guatemala","iso2"=>"GT","iso3"=>"GTM","code"=>"502"];
        $codes["GF"]=["nombre"=>"Guayana Francesa","name"=>"French Guiana","iso2"=>"GF","iso3"=>"GUF","code"=>"594"];
        $codes["GG"]=["nombre"=>"Guernsey","name"=>"Guernsey","iso2"=>"GG","iso3"=>"GGY","code"=>"44"];
        $codes["GN"]=["nombre"=>"Guinea","name"=>"Guinea","iso2"=>"GN","iso3"=>"GIN","code"=>"224"];
        $codes["GQ"]=["nombre"=>"Guinea Ecuatorial","name"=>"Equatorial Guinea","iso2"=>"GQ","iso3"=>"GNQ","code"=>"240"];
        $codes["GW"]=["nombre"=>"Guinea-Bissau","name"=>"Guinea-Bissau","iso2"=>"GW","iso3"=>"GNB","code"=>"245"];
        $codes["GY"]=["nombre"=>"Guyana","name"=>"Guyana","iso2"=>"GY","iso3"=>"GUY","code"=>"592"];
        $codes["HT"]=["nombre"=>"Haití","name"=>"Haiti","iso2"=>"HT","iso3"=>"HTI","code"=>"509"];
        $codes["HN"]=["nombre"=>"Honduras","name"=>"Honduras","iso2"=>"HN","iso3"=>"HND","code"=>"504"];
        $codes["HK"]=["nombre"=>"Hong kong","name"=>"Hong Kong","iso2"=>"HK","iso3"=>"HKG","code"=>"852"];
        $codes["HU"]=["nombre"=>"Hungría","name"=>"Hungary","iso2"=>"HU","iso3"=>"HUN","code"=>"36"];
        $codes["IN"]=["nombre"=>"India","name"=>"India","iso2"=>"IN","iso3"=>"IND","code"=>"91"];
        $codes["ID"]=["nombre"=>"Indonesia","name"=>"Indonesia","iso2"=>"ID","iso3"=>"IDN","code"=>"62"];
        $codes["IR"]=["nombre"=>"Irán","name"=>"Iran","iso2"=>"IR","iso3"=>"IRN","code"=>"98"];
        $codes["IQ"]=["nombre"=>"Irak","name"=>"Iraq","iso2"=>"IQ","iso3"=>"IRQ","code"=>"964"];
        $codes["IE"]=["nombre"=>"Irlanda","name"=>"Ireland","iso2"=>"IE","iso3"=>"IRL","code"=>"353"];
        $codes["BV"]=["nombre"=>"Isla Bouvet","name"=>"Bouvet Island","iso2"=>"BV","iso3"=>"BVT","code"=>""];
        $codes["IM"]=["nombre"=>"Isla de Man","name"=>"Isle of Man","iso2"=>"IM","iso3"=>"IMN","code"=>"44"];
        $codes["CX"]=["nombre"=>"Isla de Navidad","name"=>"Christmas Island","iso2"=>"CX","iso3"=>"CXR","code"=>"61"];
        $codes["NF"]=["nombre"=>"Isla Norfolk","name"=>"Norfolk Island","iso2"=>"NF","iso3"=>"NFK","code"=>"672"];
        $codes["IS"]=["nombre"=>"Islandia","name"=>"Iceland","iso2"=>"IS","iso3"=>"ISL","code"=>"354"];
        $codes["BM"]=["nombre"=>"Islas Bermudas","name"=>"Bermuda Islands","iso2"=>"BM","iso3"=>"BMU","code"=>"1 441"];
        $codes["KY"]=["nombre"=>"Islas Caimán","name"=>"Cayman Islands","iso2"=>"KY","iso3"=>"CYM","code"=>"1 345"];
        $codes["CC"]=["nombre"=>"Islas Cocos (Keeling)","name"=>"Cocos (Keeling) Islands","iso2"=>"CC","iso3"=>"CCK","code"=>"61"];
        $codes["CK"]=["nombre"=>"Islas Cook","name"=>"Cook Islands","iso2"=>"CK","iso3"=>"COK","code"=>"682"];
        $codes["AX"]=["nombre"=>"Islas de Åland","name"=>"Åland Islands","iso2"=>"AX","iso3"=>"ALA","code"=>"358"];
        $codes["FO"]=["nombre"=>"Islas Feroe","name"=>"Faroe Islands","iso2"=>"FO","iso3"=>"FRO","code"=>"298"];
        $codes["GS"]=["nombre"=>"Islas Georgias del Sur y Sandwich del Sur","name"=>"South Georgia and the South Sandwich Islands","iso2"=>"GS","iso3"=>"SGS","code"=>"500"];
        $codes["HM"]=["nombre"=>"Islas Heard y McDonald","name"=>"Heard Island and McDonald Islands","iso2"=>"HM","iso3"=>"HMD","code"=>""];
        $codes["MV"]=["nombre"=>"Islas Maldivas","name"=>"Maldives","iso2"=>"MV","iso3"=>"MDV","code"=>"960"];
        $codes["FK"]=["nombre"=>"Islas Malvinas","name"=>"Falkland Islands (Malvinas)","iso2"=>"FK","iso3"=>"FLK","code"=>"500"];
        $codes["MP"]=["nombre"=>"Islas Marianas del Norte","name"=>"Northern Mariana Islands","iso2"=>"MP","iso3"=>"MNP","code"=>"1 670"];
        $codes["MH"]=["nombre"=>"Islas Marshall","name"=>"Marshall Islands","iso2"=>"MH","iso3"=>"MHL","code"=>"692"];
        $codes["PN"]=["nombre"=>"Islas Pitcairn","name"=>"Pitcairn Islands","iso2"=>"PN","iso3"=>"PCN","code"=>"870"];
        $codes["SB"]=["nombre"=>"Islas Salomón","name"=>"Solomon Islands","iso2"=>"SB","iso3"=>"SLB","code"=>"677"];
        $codes["TC"]=["nombre"=>"Islas Turcas y Caicos","name"=>"Turks and Caicos Islands","iso2"=>"TC","iso3"=>"TCA","code"=>"1 649"];
        $codes["UM"]=["nombre"=>"Islas Ultramarinas Menores de Estados Unidos","name"=>"United States Minor Outlying Islands","iso2"=>"UM","iso3"=>"UMI","code"=>"246"];
        $codes["VG"]=["nombre"=>"Islas Vírgenes Británicas","name"=>"Virgin Islands","iso2"=>"VG","iso3"=>"VGB","code"=>"1 284"];
        $codes["VI"]=["nombre"=>"Islas Vírgenes de los Estados Unidos","name"=>"United States Virgin Islands","iso2"=>"VI","iso3"=>"VIR","code"=>"1 340"];
        $codes["IL"]=["nombre"=>"Israel","name"=>"Israel","iso2"=>"IL","iso3"=>"ISR","code"=>"972"];
        $codes["IT"]=["nombre"=>"Italia","name"=>"Italy","iso2"=>"IT","iso3"=>"ITA","code"=>"39"];
        $codes["JM"]=["nombre"=>"Jamaica","name"=>"Jamaica","iso2"=>"JM","iso3"=>"JAM","code"=>"1 876"];
        $codes["JP"]=["nombre"=>"Japón","name"=>"Japan","iso2"=>"JP","iso3"=>"JPN","code"=>"81"];
        $codes["JE"]=["nombre"=>"Jersey","name"=>"Jersey","iso2"=>"JE","iso3"=>"JEY","code"=>"44"];
        $codes["JO"]=["nombre"=>"Jordania","name"=>"Jordan","iso2"=>"JO","iso3"=>"JOR","code"=>"962"];
        $codes["KZ"]=["nombre"=>"Kazajistán","name"=>"Kazakhstan","iso2"=>"KZ","iso3"=>"KAZ","code"=>"7"];
        $codes["KE"]=["nombre"=>"Kenia","name"=>"Kenya","iso2"=>"KE","iso3"=>"KEN","code"=>"254"];
        $codes["KG"]=["nombre"=>"Kirguistán","name"=>"Kyrgyzstan","iso2"=>"KG","iso3"=>"KGZ","code"=>"996"];
        $codes["KI"]=["nombre"=>"Kiribati","name"=>"Kiribati","iso2"=>"KI","iso3"=>"KIR","code"=>"686"];
        $codes["KW"]=["nombre"=>"Kuwait","name"=>"Kuwait","iso2"=>"KW","iso3"=>"KWT","code"=>"965"];
        $codes["LB"]=["nombre"=>"Líbano","name"=>"Lebanon","iso2"=>"LB","iso3"=>"LBN","code"=>"961"];
        $codes["LA"]=["nombre"=>"Laos","name"=>"Laos","iso2"=>"LA","iso3"=>"LAO","code"=>"856"];
        $codes["LS"]=["nombre"=>"Lesoto","name"=>"Lesotho","iso2"=>"LS","iso3"=>"LSO","code"=>"266"];
        $codes["LV"]=["nombre"=>"Letonia","name"=>"Latvia","iso2"=>"LV","iso3"=>"LVA","code"=>"371"];
        $codes["LR"]=["nombre"=>"Liberia","name"=>"Liberia","iso2"=>"LR","iso3"=>"LBR","code"=>"231"];
        $codes["LY"]=["nombre"=>"Libia","name"=>"Libya","iso2"=>"LY","iso3"=>"LBY","code"=>"218"];
        $codes["LI"]=["nombre"=>"Liechtenstein","name"=>"Liechtenstein","iso2"=>"LI","iso3"=>"LIE","code"=>"423"];
        $codes["LT"]=["nombre"=>"Lituania","name"=>"Lithuania","iso2"=>"LT","iso3"=>"LTU","code"=>"370"];
        $codes["LU"]=["nombre"=>"Luxemburgo","name"=>"Luxembourg","iso2"=>"LU","iso3"=>"LUX","code"=>"352"];
        $codes["MX"]=["nombre"=>"México","name"=>"Mexico","iso2"=>"MX","iso3"=>"MEX","code"=>"52"];
        $codes["MC"]=["nombre"=>"Mónaco","name"=>"Monaco","iso2"=>"MC","iso3"=>"MCO","code"=>"377"];
        $codes["MO"]=["nombre"=>"Macao","name"=>"Macao","iso2"=>"MO","iso3"=>"MAC","code"=>"853"];
        $codes["MK"]=["nombre"=>"Macedônia","name"=>"Macedonia","iso2"=>"MK","iso3"=>"MKD","code"=>"389"];
        $codes["MG"]=["nombre"=>"Madagascar","name"=>"Madagascar","iso2"=>"MG","iso3"=>"MDG","code"=>"261"];
        $codes["MY"]=["nombre"=>"Malasia","name"=>"Malaysia","iso2"=>"MY","iso3"=>"MYS","code"=>"60"];
        $codes["MW"]=["nombre"=>"Malawi","name"=>"Malawi","iso2"=>"MW","iso3"=>"MWI","code"=>"265"];
        $codes["ML"]=["nombre"=>"Mali","name"=>"Mali","iso2"=>"ML","iso3"=>"MLI","code"=>"223"];
        $codes["MT"]=["nombre"=>"Malta","name"=>"Malta","iso2"=>"MT","iso3"=>"MLT","code"=>"356"];
        $codes["MA"]=["nombre"=>"Marruecos","name"=>"Morocco","iso2"=>"MA","iso3"=>"MAR","code"=>"212"];
        $codes["MQ"]=["nombre"=>"Martinica","name"=>"Martinique","iso2"=>"MQ","iso3"=>"MTQ","code"=>"596"];
        $codes["MU"]=["nombre"=>"Mauricio","name"=>"Mauritius","iso2"=>"MU","iso3"=>"MUS","code"=>"230"];
        $codes["MR"]=["nombre"=>"Mauritania","name"=>"Mauritania","iso2"=>"MR","iso3"=>"MRT","code"=>"222"];
        $codes["YT"]=["nombre"=>"Mayotte","name"=>"Mayotte","iso2"=>"YT","iso3"=>"MYT","code"=>"262"];
        $codes["FM"]=["nombre"=>"Micronesia","name"=>"Estados Federados de","iso2"=>"FM","iso3"=>"FSM","code"=>"691"];
        $codes["MD"]=["nombre"=>"Moldavia","name"=>"Moldova","iso2"=>"MD","iso3"=>"MDA","code"=>"373"];
        $codes["MN"]=["nombre"=>"Mongolia","name"=>"Mongolia","iso2"=>"MN","iso3"=>"MNG","code"=>"976"];
        $codes["ME"]=["nombre"=>"Montenegro","name"=>"Montenegro","iso2"=>"ME","iso3"=>"MNE","code"=>"382"];
        $codes["MS"]=["nombre"=>"Montserrat","name"=>"Montserrat","iso2"=>"MS","iso3"=>"MSR","code"=>"1 664"];
        $codes["MZ"]=["nombre"=>"Mozambique","name"=>"Mozambique","iso2"=>"MZ","iso3"=>"MOZ","code"=>"258"];
        $codes["NA"]=["nombre"=>"Namibia","name"=>"Namibia","iso2"=>"NA","iso3"=>"NAM","code"=>"264"];
        $codes["NR"]=["nombre"=>"Nauru","name"=>"Nauru","iso2"=>"NR","iso3"=>"NRU","code"=>"674"];
        $codes["NP"]=["nombre"=>"Nepal","name"=>"Nepal","iso2"=>"NP","iso3"=>"NPL","code"=>"977"];
        $codes["NI"]=["nombre"=>"Nicaragua","name"=>"Nicaragua","iso2"=>"NI","iso3"=>"NIC","code"=>"505"];
        $codes["NE"]=["nombre"=>"Niger","name"=>"Niger","iso2"=>"NE","iso3"=>"NER","code"=>"227"];
        $codes["NG"]=["nombre"=>"Nigeria","name"=>"Nigeria","iso2"=>"NG","iso3"=>"NGA","code"=>"234"];
        $codes["NU"]=["nombre"=>"Niue","name"=>"Niue","iso2"=>"NU","iso3"=>"NIU","code"=>"683"];
        $codes["NO"]=["nombre"=>"Noruega","name"=>"Norway","iso2"=>"NO","iso3"=>"NOR","code"=>"47"];
        $codes["NC"]=["nombre"=>"Nueva Caledonia","name"=>"New Caledonia","iso2"=>"NC","iso3"=>"NCL","code"=>"687"];
        $codes["NZ"]=["nombre"=>"Nueva Zelanda","name"=>"New Zealand","iso2"=>"NZ","iso3"=>"NZL","code"=>"64"];
        $codes["OM"]=["nombre"=>"Omán","name"=>"Oman","iso2"=>"OM","iso3"=>"OMN","code"=>"968"];
        $codes["NL"]=["nombre"=>"Países Bajos","name"=>"Netherlands","iso2"=>"NL","iso3"=>"NLD","code"=>"31"];
        $codes["PK"]=["nombre"=>"Pakistán","name"=>"Pakistan","iso2"=>"PK","iso3"=>"PAK","code"=>"92"];
        $codes["PW"]=["nombre"=>"Palau","name"=>"Palau","iso2"=>"PW","iso3"=>"PLW","code"=>"680"];
        $codes["PS"]=["nombre"=>"Palestina","name"=>"Palestine","iso2"=>"PS","iso3"=>"PSE","code"=>"970"];
        $codes["PA"]=["nombre"=>"Panamá","name"=>"Panama","iso2"=>"PA","iso3"=>"PAN","code"=>"507"];
        $codes["PG"]=["nombre"=>"Papúa Nueva Guinea","name"=>"Papua New Guinea","iso2"=>"PG","iso3"=>"PNG","code"=>"675"];
        $codes["PY"]=["nombre"=>"Paraguay","name"=>"Paraguay","iso2"=>"PY","iso3"=>"PRY","code"=>"595"];
        $codes["PE"]=["nombre"=>"Perú","name"=>"Peru","iso2"=>"PE","iso3"=>"PER","code"=>"51"];
        $codes["PF"]=["nombre"=>"Polinesia Francesa","name"=>"French Polynesia","iso2"=>"PF","iso3"=>"PYF","code"=>"689"];
        $codes["PL"]=["nombre"=>"Polonia","name"=>"Poland","iso2"=>"PL","iso3"=>"POL","code"=>"48"];
        $codes["PT"]=["nombre"=>"Portugal","name"=>"Portugal","iso2"=>"PT","iso3"=>"PRT","code"=>"351"];
        $codes["PR"]=["nombre"=>"Puerto Rico","name"=>"Puerto Rico","iso2"=>"PR","iso3"=>"PRI","code"=>"1"];
        $codes["QA"]=["nombre"=>"Qatar","name"=>"Qatar","iso2"=>"QA","iso3"=>"QAT","code"=>"974"];
        $codes["GB"]=["nombre"=>"Reino Unido","name"=>"United Kingdom","iso2"=>"GB","iso3"=>"GBR","code"=>"44"];
        $codes["CF"]=["nombre"=>"República Centroafricana","name"=>"Central African Republic","iso2"=>"CF","iso3"=>"CAF","code"=>"236"];
        $codes["CZ"]=["nombre"=>"República Checa","name"=>"Czech Republic","iso2"=>"CZ","iso3"=>"CZE","code"=>"420"];
        $codes["DO"]=["nombre"=>"República Dominicana","name"=>"Dominican Republic","iso2"=>"DO","iso3"=>"DOM","code"=>"1 809"];
        $codes["SS"]=["nombre"=>"República de Sudán del Sur","name"=>"South Sudan","iso2"=>"SS","iso3"=>"SSD","code"=>"211"];
        $codes["RE"]=["nombre"=>"Reunión","name"=>"Réunion","iso2"=>"RE","iso3"=>"REU","code"=>"262"];
        $codes["RW"]=["nombre"=>"Ruanda","name"=>"Rwanda","iso2"=>"RW","iso3"=>"RWA","code"=>"250"];
        $codes["RO"]=["nombre"=>"Rumanía","name"=>"Romania","iso2"=>"RO","iso3"=>"ROU","code"=>"40"];
        $codes["RU"]=["nombre"=>"Rusia","name"=>"Russia","iso2"=>"RU","iso3"=>"RUS","code"=>"7"];
        $codes["EH"]=["nombre"=>"Sahara Occidental","name"=>"Western Sahara","iso2"=>"EH","iso3"=>"ESH","code"=>"212"];
        $codes["WS"]=["nombre"=>"Samoa","name"=>"Samoa","iso2"=>"WS","iso3"=>"WSM","code"=>"685"];
        $codes["AS"]=["nombre"=>"Samoa Americana","name"=>"American Samoa","iso2"=>"AS","iso3"=>"ASM","code"=>"1 684"];
        $codes["BL"]=["nombre"=>"San Bartolomé","name"=>"Saint Barthélemy","iso2"=>"BL","iso3"=>"BLM","code"=>"590"];
        $codes["KN"]=["nombre"=>"San Cristóbal y Nieves","name"=>"Saint Kitts and Nevis","iso2"=>"KN","iso3"=>"KNA","code"=>"1 869"];
        $codes["SM"]=["nombre"=>"San Marino","name"=>"San Marino","iso2"=>"SM","iso3"=>"SMR","code"=>"378"];
        $codes["MF"]=["nombre"=>"San Martín (Francia)","name"=>"Saint Martin (French part)","iso2"=>"MF","iso3"=>"MAF","code"=>"1 599"];
        $codes["PM"]=["nombre"=>"San Pedro y Miquelón","name"=>"Saint Pierre and Miquelon","iso2"=>"PM","iso3"=>"SPM","code"=>"508"];
        $codes["VC"]=["nombre"=>"San Vicente y las Granadinas","name"=>"Saint Vincent and the Grenadines","iso2"=>"VC","iso3"=>"VCT","code"=>"1 784"];
        $codes["SH"]=["nombre"=>"Santa Elena","name"=>"Ascensión y Tristán de Acuña","iso2"=>"SH","iso3"=>"SHN","code"=>"290"];
        $codes["LC"]=["nombre"=>"Santa Lucía","name"=>"Saint Lucia","iso2"=>"LC","iso3"=>"LCA","code"=>"1 758"];
        $codes["ST"]=["nombre"=>"Santo Tomé y Príncipe","name"=>"Sao Tome and Principe","iso2"=>"ST","iso3"=>"STP","code"=>"239"];
        $codes["SN"]=["nombre"=>"Senegal","name"=>"Senegal","iso2"=>"SN","iso3"=>"SEN","code"=>"221"];
        $codes["RS"]=["nombre"=>"Serbia","name"=>"Serbia","iso2"=>"RS","iso3"=>"SRB","code"=>"381"];
        $codes["SC"]=["nombre"=>"Seychelles","name"=>"Seychelles","iso2"=>"SC","iso3"=>"SYC","code"=>"248"];
        $codes["SL"]=["nombre"=>"Sierra Leona","name"=>"Sierra Leone","iso2"=>"SL","iso3"=>"SLE","code"=>"232"];
        $codes["SG"]=["nombre"=>"Singapur","name"=>"Singapore","iso2"=>"SG","iso3"=>"SGP","code"=>"65"];
        $codes["SX"]=["nombre"=>"Sint Maarten","name"=>"Sint Maarten","iso2"=>"SX","iso3"=>"SMX","code"=>"1 721"];
        $codes["SY"]=["nombre"=>"Siria","name"=>"Syria","iso2"=>"SY","iso3"=>"SYR","code"=>"963"];
        $codes["SO"]=["nombre"=>"Somalia","name"=>"Somalia","iso2"=>"SO","iso3"=>"SOM","code"=>"252"];
        $codes["LK"]=["nombre"=>"Sri lanka","name"=>"Sri Lanka","iso2"=>"LK","iso3"=>"LKA","code"=>"94"];
        $codes["ZA"]=["nombre"=>"Sudáfrica","name"=>"South Africa","iso2"=>"ZA","iso3"=>"ZAF","code"=>"27"];
        $codes["SD"]=["nombre"=>"Sudán","name"=>"Sudan","iso2"=>"SD","iso3"=>"SDN","code"=>"249"];
        $codes["SE"]=["nombre"=>"Suecia","name"=>"Sweden","iso2"=>"SE","iso3"=>"SWE","code"=>"46"];
        $codes["CH"]=["nombre"=>"Suiza","name"=>"Switzerland","iso2"=>"CH","iso3"=>"CHE","code"=>"41"];
        $codes["SR"]=["nombre"=>"Surinám","name"=>"Suriname","iso2"=>"SR","iso3"=>"SUR","code"=>"597"];
        $codes["SJ"]=["nombre"=>"Svalbard y Jan Mayen","name"=>"Svalbard and Jan Mayen","iso2"=>"SJ","iso3"=>"SJM","code"=>"47"];
        $codes["SZ"]=["nombre"=>"Swazilandia","name"=>"Swaziland","iso2"=>"SZ","iso3"=>"SWZ","code"=>"268"];
        $codes["TJ"]=["nombre"=>"Tayikistán","name"=>"Tajikistan","iso2"=>"TJ","iso3"=>"TJK","code"=>"992"];
        $codes["TH"]=["nombre"=>"Tailandia","name"=>"Thailand","iso2"=>"TH","iso3"=>"THA","code"=>"66"];
        $codes["TW"]=["nombre"=>"Taiwán","name"=>"Taiwan","iso2"=>"TW","iso3"=>"TWN","code"=>"886"];
        $codes["TZ"]=["nombre"=>"Tanzania","name"=>"Tanzania","iso2"=>"TZ","iso3"=>"TZA","code"=>"255"];
        $codes["IO"]=["nombre"=>"Territorio Británico del Océano Índico","name"=>"British Indian Ocean Territory","iso2"=>"IO","iso3"=>"IOT","code"=>"246"];
        $codes["TF"]=["nombre"=>"Territorios Australes y Antárticas Franceses","name"=>"French Southern Territories","iso2"=>"TF","iso3"=>"ATF","code"=>""];
        $codes["TL"]=["nombre"=>"Timor Oriental","name"=>"East Timor","iso2"=>"TL","iso3"=>"TLS","code"=>"670"];
        $codes["TG"]=["nombre"=>"Togo","name"=>"Togo","iso2"=>"TG","iso3"=>"TGO","code"=>"228"];
        $codes["TK"]=["nombre"=>"Tokelau","name"=>"Tokelau","iso2"=>"TK","iso3"=>"TKL","code"=>"690"];
        $codes["TO"]=["nombre"=>"Tonga","name"=>"Tonga","iso2"=>"TO","iso3"=>"TON","code"=>"676"];
        $codes["TT"]=["nombre"=>"Trinidad y Tobago","name"=>"Trinidad and Tobago","iso2"=>"TT","iso3"=>"TTO","code"=>"1 868"];
        $codes["TN"]=["nombre"=>"Tunez","name"=>"Tunisia","iso2"=>"TN","iso3"=>"TUN","code"=>"216"];
        $codes["TM"]=["nombre"=>"Turkmenistán","name"=>"Turkmenistan","iso2"=>"TM","iso3"=>"TKM","code"=>"993"];
        $codes["TR"]=["nombre"=>"Turquía","name"=>"Turkey","iso2"=>"TR","iso3"=>"TUR","code"=>"90"];
        $codes["TV"]=["nombre"=>"Tuvalu","name"=>"Tuvalu","iso2"=>"TV","iso3"=>"TUV","code"=>"688"];
        $codes["UA"]=["nombre"=>"Ucrania","name"=>"Ukraine","iso2"=>"UA","iso3"=>"UKR","code"=>"380"];
        $codes["UG"]=["nombre"=>"Uganda","name"=>"Uganda","iso2"=>"UG","iso3"=>"UGA","code"=>"256"];
        $codes["UY"]=["nombre"=>"Uruguay","name"=>"Uruguay","iso2"=>"UY","iso3"=>"URY","code"=>"598"];
        $codes["UZ"]=["nombre"=>"Uzbekistán","name"=>"Uzbekistan","iso2"=>"UZ","iso3"=>"UZB","code"=>"998"];
        $codes["VU"]=["nombre"=>"Vanuatu","name"=>"Vanuatu","iso2"=>"VU","iso3"=>"VUT","code"=>"678"];
        $codes["VE"]=["nombre"=>"Venezuela","name"=>"Venezuela","iso2"=>"VE","iso3"=>"VEN","code"=>"58"];
        $codes["VN"]=["nombre"=>"Vietnam","name"=>"Vietnam","iso2"=>"VN","iso3"=>"VNM","code"=>"84"];
        $codes["WF"]=["nombre"=>"Wallis y Futuna","name"=>"Wallis and Futuna","iso2"=>"WF","iso3"=>"WLF","code"=>"681"];
        $codes["YE"]=["nombre"=>"Yemen","name"=>"Yemen","iso2"=>"YE","iso3"=>"YEM","code"=>"967"];
        $codes["DJ"]=["nombre"=>"Yibuti","name"=>"Djibouti","iso2"=>"DJ","iso3"=>"DJI","code"=>"253"];
        $codes["ZM"]=["nombre"=>"Zambia","name"=>"Zambia","iso2"=>"ZM","iso3"=>"ZMB","code"=>"260"];
        $codes["ZW"]=["nombre"=>"Zimbabue","name"=>"Zimbabwe","iso2"=>"ZW","iso3"=>"ZWE","code"=>"263"];
        return $codes;
    }



}
