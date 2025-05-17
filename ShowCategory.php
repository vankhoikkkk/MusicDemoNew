<?php
include 'DAO/ShowCategoryDAO.php';
?>


<?php
$showCategory = new ShowCategoryDAO();
$theloai = "";
if (isset($_GET['theloai'])) {
  $theloai = $_GET['theloai'];
} else {
  echo "Lỗi không tìm được thể loại nhạc";
}

// lấy dữ liệu show
$result = null;
if ($theloai == "ALL") {
  $result = $showCategory->getAllMusic();
} else {
  $result = $showCategory->getAllMusicOfGenger($theloai);
}

// phân trang
$rowCount = $result->num_rows;

$trang = $rowCount / 20;

$page = "";
if (!$_GET['page']) {
  $page = 0;
}



if ($theloai == "ALL") {
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;  // ép kiểu an toàn
  $limit = 20;
  $offset = $page * $limit;
  $result_show = $showCategory->getAllMusic18CO($offset);
} else {
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;  // ép kiểu an toàn
  $limit = 20;
  $offset = $page * $limit;
  $result_show = $showCategory->getShowMusic18CO($theloai, $offset);
} 
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thư Viện Nhạc</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="CSS/Category.css">
  <link rel="stylesheet" href="CSS/playMusic.css">
  <link rel="stylesheet" href="CSS/songCard.css">
  <link rel="stylesheet" href="CSS/search.css">
  <link rel="stylesheet" href="CSS/header.css">
  <link rel="stylesheet" href="CSS/footer.css">
</head>
<style>
  .hot-songs.row {
    /* display: flex;
    flex-wrap: wrap;
    justify-content: center; */
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
    grid-gap: 20px;
    gap: 20px;
  }

  li {
    list-style: none;
    padding: 10px;
    margin: 5px;
    cursor: pointer;
  }

  li>a {
    display: block;
    width: 100%;
    height: 100%;
  }

  .active {
    background-color: rgba(1, 22, 17, 0.34);
  }

  .list-buttom ul {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

   .song-card.playing {
    background: rgba(65, 65, 65, 0.3);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    transform: scale(1.02);
    transition: all 0.3s ease;
  }



  .song-card.playing .play-overlay {
    opacity: 1;
  }
</style>

<body>
  <?php include 'header.php' ?>
  <?php include 'slide.php' ?>
  <?php include 'V_headerCategory.php' ?>
<div class="list-music-top">
  <div class="hot-title">🎧 Top Nhạc <?php echo $theloai ?></div>
  <div class="hot-songs row">
    <?php
    if ($result_show) {
      while ($row = $result_show->fetch_assoc()) {
    ?>
        <div class="song-card" data-audio="<?php echo $row['linknhac']; ?>" data-id="<?php echo $row['id_baihat']; ?>">
          <div class="play-overlay">
            <i class='bx bx-play-circle'></i>
          </div>
          <img src="<?php echo $row['album']; ?>" alt="Song">
          <div class="song-info">
            <p class="baihat"><?php echo $row['tenbaihat']; ?></p>
            <p class="casi">(<?php echo $row['tenCaSi']; ?>)</p>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>Không có bài hát nào.</p>";
    }
    ?>
  </div>
</div>



<div class="list-buttom">
  <ul>
    <?php
    for ($page = 0; $page <= $trang; $page++) {
      echo "<a href='ShowCategory.php?page={$page}&theloai={$theloai}'><li> {$page} </li></a>";
    ?>
    <?php
    }
    ?>
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