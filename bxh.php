<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bxh.css">
    <title>BXH</title>
</head>
<body>
    <div class="tabs">
        <div class="tab active" onclick="showTab('vn')" id="tab-vn">Việt Nam</div>
        <div class="tab" onclick="showTab('usuk')" id="tab-usuk">Âu Mỹ</div>
        <div class="tab" onclick="showTab('cn')" id="tab-cn">Trung Quốc</div>
    </div>
    <div class="chart active" id="vn">
        <ul>
            <?php include("./listof_music/bxh_vn.php"); ?>
        </ul>
    </div>
    <div class="chart" id="usuk">
        <ul>
            <li>
                <?php include("./listof_music/bxh_usuk.php"); ?>
            </li>
        </ul>
    </div>
    <div class="chart" id="cn">
        <ul>
            <li>
                <?php include("./listof_music/bxh_cn.php"); ?>
            </li>
        </ul>
    </div>
    <script>
    function showTab(id) {
      document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
      document.querySelectorAll('.chart').forEach(chart => chart.classList.remove('active'));

      document.querySelector(`.tab[onclick="showTab('${id}')"]`).classList.add('active');
      document.getElementById(id).classList.add('active');
    }
  </script>
</html>