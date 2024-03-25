const key_hkbank = "8ROCD6TXTRHKJ37YIBPD";

//----------------------------------------

const key = "POx99f9MwT1UCPR9iYfVI1oYr6Hk52sU";
const url = "https://www.koreaexim.go.kr/site/program/financial/exchangeJSON";

request_url = url + "?authkey=" + key + "&data=AP01";

console.log(request_url);

fetch(request_url)
  .then((response) => response.json())
  .then((data) => console.log(data))