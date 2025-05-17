<?php
include 'DAO/ShowCategoryDAO.php';

$showCategory = new ShowCategoryDAO();
$query = "";
if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);
} else {
    echo "Vui lòng nhập từ khóa tìm kiếm.";
    exit;
}

// Tìm kiếm bài hát và ca sĩ
$result_baihat = $showCategory->searchMusic($query);

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
    .hot-songs.search {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        grid-gap: 20px;
        gap: 20px;
    }

  
</style>

<body>
  <?php include 'header.php' ?>
  <?php include 'slide.php' ?>
<div class="results">
    <h2 style="color: red;">Kết quả tìm kiếm cho: "<?php echo $_GET['query']; ?>"</h2>
    <?php if ($result_baihat && $result_baihat->num_rows > 0): ?>
        <div class="hot-songs search">
          
            <?php while ($row = $result_baihat->fetch_assoc()): ?>
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
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Không tìm thấy bài hát nào.</p>
    <?php endif; ?>
</div>

<div class="list-buttom">
    <ul>
        <!-- Thêm các trang nếu cần -->
    </ul>
</div>

<?php include 'player.php'?>
  <?php include 'footer.php' ?>
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


<!-- <script src="music-player.js"></script> -->
</html>