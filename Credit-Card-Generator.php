<?php
error_reporting(0);
header("content-type: application/json");

class creditCard{
	const BASE = "https://ineo-team.ir/creditcard-generator/";
	public function setParams($bin, $cvv = null, $year = null, $month = null, $count = 5){
		return [
			'card' => $bin, 'cvv' => $cvv,
			'year' => $year, 'month' => $month,
			'count' => $count, 'format' => "pipe"
		];
	}
	public function request($params = []){
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => self::BASE,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $params
		]);
		$response = curl_exec($curl);
		curl_close(); return $response;
	}
	public function generateJson($html_source){
		preg_match_all('#<b>Result:</b><br><code>(.*?)</code>#su', $html_source, $output);
		$output = explode("<br>", $output[1][0]); $list = [];
		foreach($output as $out){
			if(!empty($out)){ $n++; $list[$n] = $out; }
		}
		return $list;
	}
}

$credit = new creditCard();
$params = $credit->setParams(406068, null, 2028, null, 10);
$source = $credit->request($params);
$output	= $credit->generateJson($source);

if(empty($output)){
	echo json_encode(['ok' => false, 'status' => 400]);
}else{
	echo json_encode(['ok' => true, 'status' => 200, 'result' => $output]);
}

unlink("error_log");
?>
