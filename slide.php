<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Slide Coverflow Có Điều Hướng</title>
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #f5f5f5;
      font-family: 'Poppins', sans-serif;
    }

    .mySwiper {
      width: 100%;
      max-width: 800px;
      height: 600px;
      margin: 50px auto;
      position: relative;
      padding-top: 100px;
    }

    .swiper-slide {
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .swiper-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s;
    }

    .swiper-slide img:hover {
      transform: scale(1.05);
    }

    /* Nút điều hướng */
    .swiper-button-next,
    .swiper-button-prev {
      color: #000;
    }
    /* Custom arrows */
.swiper-button-next::after,
.swiper-button-prev::after {
  font-size: 30px;
  font-weight: bold;
  color: black;
  content: ''; /* reset mặc định nếu muốn dùng hình ảnh thay */
}

/* Hoặc nếu bạn muốn hiển thị mũi tên dạng ký tự (→ ←) */
.swiper-button-next::after {
  color: gray;
  content: '>';
}

.swiper-button-prev::after {
  color: gray;
  content: '<';
}

  </style>
</head>
<body>
    
  <!-- Slide Swiper -->
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <?php include("./listof_music/connect.php");
        mysqli_set_charset($connect, 'utf8');
        $query = mysqli_query($connect, "SELECT * FROM `baihat` WHERE theloai = 'Nhạc trẻ' and id_CaSi != 5 ORDER by luotnghe DESC LIMIT 4;");
        while($row = mysqli_fetch_assoc($query)){
          ?>

          <div class="swiper-slide">
            <img src="<?php echo $row['album'] ?>" alt="Slide 1" />
          </div>
          <?php
        }
      ?>
    </div>

    <!-- Nút điều hướng -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper(".mySwiper", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      loop: true,
      coverflowEffect: {
        rotate: 30,
        stretch: 0,
        depth: 200,
        modifier: 1,
        slideShadows: true,
      },
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination:{
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>

</body>
</html>
