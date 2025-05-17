<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="CSS/tc.css" />
  <link rel="stylesheet" href="CSS/playMusic.css">
  <link rel="stylesheet" href="CSS/songCard.css">
  <link rel="stylesheet" href="CSS/header.css" />
  <link rel="stylesheet" href="CSS/footer.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
  <title>Nhạc Của Tao</title>
</head>


<body>
  <!-- Header -->
 <?php include 'header.php' ?>


  <!-- Body -->
  <div class="tag1_element">
    <div class="tag1">
      <?php include("slide.php") ?>
    </div>
  </div>
  <div class="body_page">
    <div>
      <div class="list_album">
        <div class="tre">
          <p class="head_title">Vũ Trụ Nhạc Việt</p>
          <ul>
            <?php include("./listof_music/nhactre.php") ?>
          </ul>
        </div>
        <div class="do">
          <p class="head_title">Quốc Tế Nổi Bật</p>
          <ul>
            <?php include("./listof_music/nhacdo.php") ?>
          </ul>
        </div>
        <div class="rap">
          <p class="head_title">Tâm trạng hôm nay</p>
          <ul>
            <?php include("./listof_music/nhacrap.php") ?>
          </ul>
        </div>
        <div class="au">
          <p class="head_title">Nhạc Âu</p>
          <ul>
            <?php include("./listof_music/nhacau.php") ?>
          </ul>
        </div>
        <div class="trung">
          <p class="head_title">Nhạc Trung</p>
          <ul>
            <?php include("./listof_music/nhactrung.php") ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="ranking_theme">
      <div class="tag9">
        <a href="#" id="rank-title">BXH Bài Hát</a>
        <?php include("BXH.php") ?>
      </div>
    </div>
  </div>
  </div>
  <div class="tag10"></div>
  </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <ul class="menu_list">
      <li class="menu_list_main">
        <p>
          Hỗ trợ</p>
        <ul class="list_p">
          <li>
            <a href="#">Trợ giúp</a>
          </li>
          <li>
            <a href="#">Sơ đồ</a>
          </li>
          <li>
            <a href="#">NCCI</a>
          </li>
          <li>
            <a href="#">Liên hệ quảng cáo</a>
          </li>
        </ul>
        </a>
      </li>
      <li class="menu_list_main">
        <a href=""></a>
        <ul class="list_p">
          <li>
            <a href="#">Chính sách bảo mật và bảo vệ,</a>
            <br>
            <a href="#">xử lí thông tin cá nhân</a>
          </li>
          <li>
            <a href="#">Chính sách SHTT</a>
          </li>
          <li>
            <a href="#">Thoả thuận sử dụng</a>
          </li>
        </ul>
      </li>
      <li class="menu_list_main">
        <p>Sản phẩm khác</p>
        <ul class="list_p">
          <li>
            <a href="#">Mobile App</a>
          </li>
          <li>
            <a href="#">Mobile Web</a>
          </li>
          <li>
            <a href="#">Smart TV</a>
          </li>
          <li>
            <a href="#">Desktop</a>
          </li>
        </ul>
      </li>
      <li class="menu_list_main">
        <a href=""></a>
        <ul class="list_p">
          <li>
            <a href="#">Dịch vụ 3G</a>
          </li>
          <li>
            <a href="#">NCT Corp</a>
          </li>
          <li>
            <a href="#">Tuyển dụng</a>
          </li>
        </ul>
      </li>
      <li class="menu_list_main">
        <p>Kết nối với chúng tôi</p>
        <ul class="list_icon">
          <li>
            <a href="#">
              <img
                src="./image/social_network_icon/fb_icon.png"
                alt=""
                id="fb_icon" />
            </a>
          </li>
          <li>
            <a href="#">
              <img
                src="./image/social_network_icon/zalo_icon.png"
                alt=""
                id="zalo_icon" />
            </a>
          </li>
          <li>
            <a href="#">
              <img
                src="./image/social_network_icon/tiktok.png"
                alt=""
                id="tiktok_icon" />
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>

  <?php include 'player.php' ?>


</body>

<script>
  // Khai báo biến
  const audioPlayer = document.getElementById('audio');
  const songCards = document.querySelectorAll('.song-card');
  const imgScrThanhNhac = document.querySelector('.cover > img');
  const containerPlay = document.querySelector('.container-play');
  const playButton = document.getElementById("play");
  const duration = document.getElementById("duration");
  const current = document.getElementById("current");
  const progress = document.getElementById("progress");
  const volume = document.getElementById("volume");
  const volumeIcon = document.getElementById("volume-icon");

  let currentAudioSrc = '';
  let currentTime = 0;
  let currentCard = null;
  let currentIndex = 0;

  // Thêm vào sau phần khai báo biến
  function updatePlayHistory(card) {
    const data = new FormData();
    data.append('add_to_history', '1');
    data.append('id', card.getAttribute('data-id'));
    data.append('title', card.querySelector('.baihat').textContent);
    data.append('artist', card.querySelector('.casi').textContent);
    data.append('cover', card.querySelector('img').src);
    data.append('audio', card.getAttribute('data-audio'));

    fetch(window.location.href, {
      method: 'POST',
      body: data
    });
  }

  // Hàm phát nhạc chính
  function playMusic(card) {
    console.log(card);
    // Xóa class playing từ tất cả card 
    document.querySelectorAll('.song-card').forEach(c => {
      c.classList.remove('playing');
    });

    const audioSrc = card.getAttribute('data-audio');
    const currentImg = card.querySelector('img');
    const playButtonCard = card.querySelector('.play-overlay i');
    const songTitle = card.querySelector(".baihat").textContent;
    const artistName = card.querySelector(".casi").textContent;

    // Thêm class playing vào card được chọn
    card.classList.add('playing');

    // Cập nhật UI
    document.getElementById('song-name').textContent = songTitle;
    document.getElementById('artist-name').textContent = artistName;
    containerPlay.classList.add('show');
    imgScrThanhNhac.src = currentImg.src;

    // Reset card cũ
    // if (currentCard && currentCard !== card) {
    //     const oldPlayButton = currentCard.querySelector('.play-overlay i');
    //     oldPlayButton.className = 'bx bx-play-circle';
    //     currentCard.classList.remove('playing');
    // }

    // Phát nhạc
    currentCard = card;
    audioPlayer.src = audioSrc;
    audioPlayer.currentTime = 0;
    audioPlayer.play();

    // Cập nhật icon
    document.querySelectorAll('.play-overlay i').forEach(icon => {
      icon.className = 'bx bx-play-circle';
    });
    playButtonCard.className = 'bx bx-pause-circle';
    playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";

    // Thêm bài hát vào history
    updatePlayHistory(card);
  }

  // Event listeners cho card
  songCards.forEach((card, index) => {
    const playOverlay = card.querySelector('.play-overlay');

    // Click vào nút play trên card
    playOverlay.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      // Kiểm tra nếu card này đang phát nhạc
      if (currentCard === card) {
        // Toggle play/pause mà không xóa class playing 
        if (!audioPlayer.paused) {
          audioPlayer.pause();
          playOverlay.querySelector('i').className = 'bx bx-play-circle';
          playButton.innerHTML = "<i class='bx bx-play-circle'></i>";
          card.classList.add('playing');
        } else {
          audioPlayer.play();
          playOverlay.querySelector('i').className = 'bx bx-pause-circle';
          playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";
          card.classList.add('playing');
        }
        return;
      }

      // Nếu là card khác
      currentIndex = index;
      playMusic(card);
    });

    // Click vào card để chuyển trang
    card.addEventListener('click', () => {
      const songId = card.getAttribute('data-id');
      window.location.href = `DetailCategory copy.php?id_baihat=${songId}`;
    });
  });

  // Xử lý điều khiển phát nhạc
  playButton.addEventListener('click', () => {
    if (!currentCard) return;
    const playButtonCard = currentCard.querySelector('.play-overlay i');

    if (audioPlayer.paused) {
      audioPlayer.play();
      playButtonCard.className = 'bx bx-pause-circle';
      playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";
    } else {
      audioPlayer.pause();
      playButtonCard.className = 'bx bx-play-circle';
      playButton.innerHTML = "<i class='bx bx-play-circle'></i>";
    }
  });

  // Xử lý next/prev
  document.getElementById('prev').addEventListener('click', () => {
    const cards = Array.from(songCards);
    if (!currentCard) return;
    currentIndex = (currentIndex - 1 + cards.length) % cards.length;
    playMusic(cards[currentIndex]);
  });

  document.getElementById('next').addEventListener('click', () => {
    const cards = Array.from(songCards);
    if (!currentCard) return;
    currentIndex = (currentIndex + 1) % cards.length;
    playMusic(cards[currentIndex]);
  });

  // Xử lý audio events
  audioPlayer.addEventListener('ended', () => {
    const cards = Array.from(songCards);
    if (!currentCard) return;
    currentIndex = (currentIndex + 1) % cards.length;
    playMusic(cards[currentIndex]);
  });

  audioPlayer.addEventListener('loadedmetadata', () => {
    duration.textContent = formatTime(audioPlayer.duration);
  });

  audioPlayer.addEventListener('timeupdate', () => {
    current.textContent = formatTime(audioPlayer.currentTime);
    progress.value = (audioPlayer.currentTime / audioPlayer.duration) * 100;
  });


  // Xử lý thanh progress và volume
  progress.addEventListener("input", () => {
    audioPlayer.currentTime = (progress.value / 100) * audioPlayer.duration;
  });

  volume.addEventListener("input", () => {
    audioPlayer.volume = volume.value;
  });

  // Helper functions
  function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes < 10 ? '0' + minutes : minutes}:${secs < 10 ? '0' + secs : secs}`;
  }
</script>

</html>