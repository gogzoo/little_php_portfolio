<?php include_once ("../portfolio4/php/getBITOInfo.php"); ?>

<?php 
    //테스트용 더미데이터
    // $BITOData = "{  'status':'OK',
    //                 'from':'2024-03-20',
    //                 'symbol':'BITO',
    //                 'open':29.03,
    //                 'high':30.255,
    //                 'low':28.41,
    //                 'close':30.11,
    //                 'volume':2.3632179e+07,
    //                 'afterHours':31.07,
    //                 'preMarket':28.76}";
    
    // $BITOdivDate = "[   '2024-03-08',
    //                     '2024-02-08',
    //                     '2023-12-28',
    //                     '2023-12-08',
    //                     '2023-11-08']";
    
    // $BITOdivAmount = "[ 0.726437,
    //                     0.356317,
    //                     0.147162,
    //                     0.12698,
    //                     0.140305]";
?>

<script>
    let BITOInfo = <?= $BITOData ?>;
    BITOInfo = JSON.parse(BITOInfo);

    //console.log(BITOInfo);

    let BITOdivDate = <?= $BITOdivDate ?>;
    let BITOdivAmount = <?= $BITOdivAmount ?>;

    BITOdivDate = BITOdivDate.reverse();
    BITOdivAmount = BITOdivAmount.reverse();

    for (var i = 0; i < BITOdivDate.length; i++) {
        BITOdivDate[i] = BITOdivDate[i].replace(/-/g, '');
        BITOdivDate[i] = BITOdivDate[i].substring(2, 9);
    }

    //console.log(BITOdivDate);
    //console.log(BITOdivAmount);
</script>

<div>
    <h2>BITO 정보 리포트예요. <span>휴일 정보는 0입니다.<br class="mobile"> 오늘이 휴일일 경우 직전 평일값이 출력됩니다.</span></h2>
    <ul class="report">
        <li>
            <h3>기본 정보</h3>
            <span id="localtime1">로딩중...</span>
            <canvas id="myChart4" height="250px"></canvas>
            <ul class="explan">
                <li>개장 전 <span id="prem">로딩</span>$로 시작하고, <span id="open">로딩</span>$로 개장했어요.</li>
                <li>장중 최고치는 <span id="high">로딩</span>$, 최저치는 <span id="low">로딩</span>$을 기록했어요.</li>
                <li>개장 마감 시 <span id="close">로딩</span>$였고, 마감 후 <span id="afterm">로딩</span>$로 종료했어요. </li>
                <li>오늘 하루종일 <span id="diff">로딩</span>$, <span id="perc">로딩</span>% 만큼의 변화가 있었어요. </li>
            </ul>
        </li>
        <hr class="mobile">
        <li>
            <h3>분배금 정보</h3>
            <span id="localtime2">로딩중...</span>
            <canvas id="myChart5" height="250px"></canvas>
            <ul class="explan">
                <li>직전 분배금은, 1stock당 <span id="maxdiv">로딩</span>$ 분배했어요.</li>
                <li>해당 기간동안 평균 <span id="avgdiv">로딩</span>$ 분배했어요.</li>
            </ul>
        </li>
        <hr class="mobile">
        <li class="calc">
            <h3>나는?</h3>
            <div>
                <label for="unitprice">stock당 평균가<input type="number" id="unitprice" placeholder="예: 21.45"></label>
                <label for="ownstocks">보유한 stock 수<input type="number" id="ownstocks" placeholder="예: 10"></label>
                <button id="stcalc" class="themebtn">계산하기</button>
            </div>
            <div id="result" class="result">
                <ul class="explan"></ul>
                <p>여기에 결과가 표시됩니다.</p>
                <div class="circle"></div>
            </div>
        </li>
    </ul>
</div>

<script>// 정보출력
    const ctx4 = document.getElementById('myChart4');
    const ctx5 = document.getElementById('myChart5');

    Chart.defaults.font.family = 'Pretendard-Regular';
    Chart.defaults.color = 'rgb(117, 73, 158)';

    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: ["프리마켓", "개장", "마감", "애프터마켓"],
            datasets: [{
            label: 'USD(달러)',
            data: [BITOInfo["preMarket"], BITOInfo["open"], BITOInfo["close"], BITOInfo["afterHours"]],
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
                max: BITOInfo["high"]*1.5
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

    // 배당정보 그래프에 값 채워넣기

    let divideChart = new Chart(ctx5, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'USD(달러)',
                data: [],
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
                max: Math.max(...BITOdivAmount)*1.5
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

    const autoValue = (arrayLabel, arrayData) => {
        for (var i = 0; i < arrayLabel.length; i++) {
            divideChart.data.labels.push(arrayLabel[i]);
            divideChart.data.datasets[0].data.push(arrayData[i]);
        }
        divideChart.update();
    };

    autoValue(BITOdivDate, BITOdivAmount);

    $("#localtime1").html(`미국 시간 ${BITOInfo["from"]} 기준 최신값`);
    $("#localtime2").html(`미국 시간 ${BITOInfo["from"]} 기준 최신값`);

    $("#prem").html(BITOInfo["preMarket"]);
    $("#open").html(BITOInfo["open"]);
    $("#high").html(BITOInfo["high"]);
    $("#low").html(BITOInfo["low"]);
    $("#close").html(BITOInfo["close"]);
    $("#afterm").html(BITOInfo["afterHours"]);

    var diff = BITOInfo["high"]-BITOInfo["low"];
    diff = diff.toFixed(2); // 소수점아래 2자리에서 반올림.

    var perc = ((BITOInfo["afterHours"]-BITOInfo["preMarket"])/BITOInfo["preMarket"])*100;
    perc = perc.toFixed(2);
    
    $("#diff").html(diff);
    $("#perc").html(perc);

    if(perc > 0) {
        $("#diff").css("color", "var(--color-up)");
        $("#perc").css("color", "var(--color-up)");
    }else {
        $("#diff").css("color", "var(--color-down)");
        $("#perc").css("color", "var(--color-down)");
    }

    $("#maxdiv").html(Math.max(...BITOdivAmount).toFixed(2));

    let divsum = 0;

    BITOdivAmount.forEach((item)=>{
        divsum += item
    });

    let avgdiv = (divsum/BITOdivAmount.length).toFixed(2);
    $("#avgdiv").html(avgdiv);

    $("#stcalc").click(function(){
        let isValidUnitPrice = ($("#unitprice").val() > 0);
        let isValidStockNum = ($("#ownstocks").val() > 0);

        if(isValidUnitPrice && isValidStockNum) {
            let myUnitPrice = $("#unitprice").val();
            let myStockNum = $("#ownstocks").val();

            let myBuyPrice = myStockNum * myUnitPrice;
            let CurrentEvalPrice = myStockNum * BITOInfo["afterHours"];

            let benefit = (CurrentEvalPrice - myBuyPrice).toFixed(2);
            let benefitRate = ((benefit/myBuyPrice)*100).toFixed(2);
            let getDiv = (myStockNum * avgdiv).toFixed(2);

            $("#result>p").hide();

            $("#result>ul.explan").html(
                `<li>현재 평가금은 <span>${CurrentEvalPrice}$</span>예요.</li>
                <li>총 <span>${benefit}$</span>, <span>${benefitRate}%</span>의 변화가 있어요.</li>
                <li>다음 분배금일에 <span>${getDiv}$</span>받을 예정이예요.</li>`
            );

            let inner = window.innerWidth;

            if (inner < 1024) {
                $(".result").css("backgroundColor", "white");
                $(".result>div.circle").css("width", "100vw");
                $(".result>div.circle").css("height", "100vw");
            } else {
                $(".result").css("backgroundColor", "white");
                $(".result>div.circle").css("width", "40vw");
                $(".result>div.circle").css("height", "40vw");
            }

            if (benefitRate > 0) {
                $("#result span").css("color", "var(--color-up)");
            } else {
                $("#result span").css("color", "var(--color-down)");
            }

        } else {
            alert("빈 칸에 유효한 평균가와 stock수가 입력되지 않았어요.");
            unitprice.value = 0;
            ownstocks.value = 0;
        }
    });
</script>