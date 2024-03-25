<?php require_once("../portfolio4/php/getExchange.php") ?>

<div>
    <script> // php에서 정보 받아오기

        let usdInfo = <?php echo $exchangeRateRef_usd ?> ;
        let jpnInfo = <?php echo $exchangeRateRef_jpn ?> ;
        let chnInfo = <?php echo $exchangeRateRef_chn ?> ;

        // 콤마 제거
        function commaReplace(arr) {
            for(var i = 0; i < arr.length; i++) {
                arr[i] = arr[i].replace(/,/g, ''); 
            }
        }

        commaReplace(usdInfo);
        commaReplace(jpnInfo);

        // console.log(usdInfo);
        // console.log(jpnInfo);
        // console.log(chnInfo);

        // 테스트용 더미
        // let usdInfo = [230, 300, 299, 432, 245];
        // let jpnInfo = [103, 300, 399, 234, 352];
        // let chnInfo = [240, 200, 266, 112, 523];

        let integrityInfoUSD = {};
        let integrityInfoJPN = {};
        let integrityInfoCHN = {};
    </script>

    <h2>오늘의 환율 정보예요. <span>휴일 정보는 0입니다.<br class="mobile"> 현재가는 직전 기록값입니다.</span></h2>
    <ul class="chartStart">
        <li>
            <h3>달러</h3>
            <canvas id="myChart1" height="250px"></canvas>
            <h4 id="usdCur">현재가: 로딩중...</h4>
            <p id="usdChange">전일대비 로딩중... 예요.</p>
        </li>
        <hr class="mobile">
        <li>
            <h3>엔</h3>
            <canvas id="myChart2" height="250px"></canvas>
            <h4 id="jpnCur">현재가: 로딩중...</h4>
            <p id="jpnChange">전일대비 로딩중... 예요.</p>
        </li>
        <hr class="mobile">
        <li>
            <h3>위안</h3>
            <canvas id="myChart3" height="250px"></canvas>
            <h4 id="chnCur">현재가: 로딩중...</h4>
            <p id="chnChange">전일대비 로딩중... 예요.</p>
        </li>
    </ul>

    <script> // 차트 정보
        const ctx1 = document.getElementById('myChart1');
        const ctx2 = document.getElementById('myChart2');
        const ctx3 = document.getElementById('myChart3');

        Chart.defaults.font.family = 'Pretendard-Regular';
        Chart.defaults.color = 'rgb(117, 73, 158)';

        let date = [];

        // 이 함수는 오늘부터 5일전까지의 배열을 만들기 위한 함수.
        function mkDateStr() {
            // 오늘에 대한 과정
            var now = new Date();
            var today = now.getDate();
            var month = (now.getMonth()+1);
            var dateStr = `${month}월 ${today}일`;

            date[0] = dateStr; // 오늘날짜 저장

            // 어제부터의 과정
            for (i = 1; i < 5; i++) {
                var j = 1;
                
                var tempDate = new Date();
                tempDate.setDate(tempDate.getDate()-i);
                var lastDateStr = `${tempDate.getMonth()+1}월 ${tempDate.getDate()}일`;
                
                date[i] = lastDateStr;

                var isDayoffLast = tempDate.getDay();
            }
        };

        mkDateStr();
        // 오늘로부터 5일 전 날짜배열
        // console.log(date);

        // 이 함수는 날짜 배열과 환율값 배열을 합치기 위함 함수.
        function mkIntegrityData() {
            var now = new Date();
            var isDayOff = now.getDay();

            for (var i = 0; i < 5; i++) {
                // 하루하루씩 줄여가며 비교할 요일
                var changeDay = (now.getDay()-i+7)%7;
                // 0에서 1빼면 6이 나오기 위함.
                //console.log(changeDay);

                if(changeDay == 0 || changeDay == 6) {

                    usdInfo.unshift('0');
                    jpnInfo.unshift('0');
                    chnInfo.unshift('0');
                    integrityInfoUSD[date[i]] = 0;
                    integrityInfoJPN[date[i]] = 0;
                    integrityInfoCHN[date[i]] = 0;

                }else {

                    integrityInfoUSD[date[i]] = usdInfo[i];
                    integrityInfoJPN[date[i]] = jpnInfo[i];
                    integrityInfoCHN[date[i]] = chnInfo[i];
                }
            }
        }

        mkIntegrityData();

        // console.log(integrityInfoUSD);
        // console.log(integrityInfoJPN);
        // console.log(integrityInfoCHN);


        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: [date[4], date[3], date[2], date[1], date[0]],
                datasets: [{
                label: 'USD(달러)',
                data: [
                    integrityInfoUSD[date[4]],
                    integrityInfoUSD[date[3]],
                    integrityInfoUSD[date[2]],
                    integrityInfoUSD[date[1]],
                    integrityInfoUSD[date[0]]
                ],
                borderWidth: 1,
                borderColor: 'rgb(213, 174, 250)',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(213, 174, 250, 0.5)'
                }]
            },
            options: {
                responsible: false,
                scales: {
                y: {
                    beginAtZero: true,
                    max: 2000
                }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: 'Pretendard-Regular',
                            }
                        }
                    }
                }
            }
        });

        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [date[4], date[3], date[2], date[1], date[0]],
                datasets: [{
                label: 'JPY(엔)',
                data: [
                    integrityInfoJPN[date[4]],
                    integrityInfoJPN[date[3]],
                    integrityInfoJPN[date[2]],
                    integrityInfoJPN[date[1]],
                    integrityInfoJPN[date[0]]
                ],
                borderWidth: 1,
                borderColor: 'rgb(213, 174, 250)',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(213, 174, 250, 0.5)'
                }]
            },
            options: {
                responsible: false,
                scales: {
                y: {
                    beginAtZero: true,
                    max: 2000
                }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: 'Pretendard-Regular',
                            }
                        }
                    }
                }
            }
        });

        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: [date[4], date[3], date[2], date[1], date[0]],
                datasets: [{
                label: 'CNY(위안)',
                data: [
                    integrityInfoCHN[date[4]],
                    integrityInfoCHN[date[3]],
                    integrityInfoCHN[date[2]],
                    integrityInfoCHN[date[1]],
                    integrityInfoCHN[date[0]]
                ],
                borderWidth: 1,
                borderColor: 'rgb(213, 174, 250)',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(213, 174, 250, 0.5)'
                }]
            },
            options: {
                responsible: false,
                scales: {
                y: {
                    beginAtZero: true,
                    max: 400
                }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: 'Pretendard-Regular',
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script> // 현재가 표시

        function compareLastDay(countryCode, arrayName) {

            let validIndex = 0;

            // 배열 첫 원소가 0일경우 다음값 찾기
            while(true) {
                if(arrayName[validIndex] == 0) {
                    validIndex ++;
                    continue;
                }else if(arrayName[validIndex] != 0) {
                    break;
                }
            }

            let todayRate = parseFloat(arrayName[validIndex]);
            let lastRate = parseFloat(arrayName[validIndex + 1]);

            let temp1 = document.getElementById(`${countryCode}Cur`);
            temp1.innerHTML = `현재가 <span>${arrayName[validIndex]} 원</span>`;

            let temp2 = document.getElementById(`${countryCode}Change`);

            let compareRate = ((todayRate/lastRate)*100)-100;
            compareRate = Math.round(compareRate*1000)/1000;

            if (todayRate > lastRate) {
                temp2.innerHTML = `직전일 보다 ${compareRate}% 올랐어요.`;
                temp1.style.color = "var(--color-up)";
                temp2.style.color = "var(--color-up)";
            } else if (todayRate <= lastRate) {
                temp2.innerHTML = `직전일 보다 ${compareRate* -1}% 내렸어요.`;
                temp1.style.color = "var(--color-down)";
                temp2.style.color = "var(--color-down)";
            }
        }

        compareLastDay("usd", usdInfo);
        compareLastDay("jpn", jpnInfo);
        compareLastDay("chn", chnInfo);
    </script>
</div>