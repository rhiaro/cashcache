<?
require_once("settings.php");

function get_fixer_rates($date, $currencies){
    global $FIXERAPI;
    if(is_array($currencies)){
        $currencies = implode($currencies, ",");
    }
    $date = $date->format("Y-m-d");
    $endpoint = "http://data.fixer.io/api/$date?access_key=$FIXERAPI&symbols=USD,GBP,$currencies";
    $rates = file_get_contents($endpoint);
    $rates = json_decode($rates, true);
    return $rates["rates"]; // the base is EUR
}

function read_rates($date){

    global $RATESPATH;
    $fn = $RATESPATH.$date->format("Ymd");
    if(file_exists($fn)){
        $rates = json_decode(file_get_contents($fn), true);
    }else{
        $rates = array();
    }
    return $rates;
}

function read_rate($date, $currency){
    $rates = read_rates($date);
    if(isset($rates[$currency])){
        return $rates[$currency];
    }else{
        return null;
    }
}

function write_rates($date, $rates){

    global $RATESPATH;

    $fn = $RATESPATH.$date->format("Ymd");
    if(file_exists($fn)){
        $existing = json_decode(file_get_contents($fn), true);
        if(is_array($existing)){
            $rates = array_merge($existing, $rates);
        }
    }
    $json = json_encode($rates);
    file_put_contents($fn, $json);
}

function convert_eur_to_any($amount, $currency, $date){
    $rate = read_rate($date, $currency);
    $any = $amount * $rate;
    return number_format($any, 2, '.', '');
}

function convert_any_to_eur($amount, $currency, $date){
    $rate = read_rate($date, $currency);
    $eur = $amount / $rate;
    return number_format($eur, 2, '.', '');
}


?>