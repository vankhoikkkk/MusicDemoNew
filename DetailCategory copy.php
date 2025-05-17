<?php
include 'DAO/DetailCategoryDAO.php';
include 'DAO/SingerDAO.php';
?>


<?php

$detaiCategory = new DetailCategoryDAO();
$singer = new SingerDAO();
$result_singer = null;
$result_idbaihat = null;

if (isset($_GET['id_baihat'])) {
    $id_baihat = $_GET['id_baihat'];
    $result_idbaihat = $detaiCategory->getMusicOfId($id_baihat);
    $result_singer = $singer->getSingerBySongId($id_baihat);
} else {
    echo "ID bài hát không được cung cấp.";
}
$singerInfo = $result_singer->fetch_assoc();

$row_idbaihat = $result_idbaihat->fetch_assoc();
$result = $detaiCategory->getAllMusicOfGenger($row_idbaihat['theloai'], $row_idbaihat['id_baihat']);


?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bolero Hay Nhất</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS/playMusic.css">
    <link rel="stylesheet" href="CSS/songCard.css">
    <link rel="stylesheet" href="CSS/DetailCategory.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
</head>
<style>
    .song-card.list-card {
        height: 80px;
    }

    .container {
        padding-top: 100px;
    }
</style>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <!-- Bên trái: Bài nhạc chính -->
        <div class="main-song">
            <div class="song-card playingg" data-audio="<?php echo $row_idbaihat['linknhac']; ?>">
                <div class="play-overlay">
                    <i class='bx bx-play-circle'></i>
                </div>
                <img src="<?php echo $row_idbaihat['album']; ?>" alt="Song">
                <!-- chỗ này viết để cho thanh nhạc có thể lấy và hiện thông tin -->
                <div style="display:  none;" class="song-info">
                    <p class="baihat"><?php echo $row_idbaihat['tenbaihat']; ?></p>
                    <p class="casi"> <?php echo $row_idbaihat['tenCaSi']; ?></p>
                </div>
            </div>
            <!-- chỗ này viết để show ra người dùng thấy -->
            <div class="song-info show-main">
                <p class="show-main-text "> <?php echo $row_idbaihat['tenbaihat']; ?></p>
                <p class="show-main-text casi"> (<?php echo $row_idbaihat['tenCaSi']; ?>)</p>
            </div>
            <div class="info-song">
                <strong>Nghệ sĩ : </strong>
                <p class="nghesi"> <?php echo $row_idbaihat['ngheSi']; ?> </p>
                <strong>Mô Tả Bài Hát : </strong>
                <p class="mota"> <?php echo $row_idbaihat['moTa']; ?></p>
            </div>

        </div>

        <!-- Bên phải: Danh sách nhạc và nghệ sĩ -->
        <div class="right-panel">
            <div class="song-list">
                <h3>Danh sách bài hát</h3>
                <?php
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <div class="song-item">
                            <div class="song-card list-card" data-audio="<?php echo $row['linknhac']; ?>">
                                <div class="play-overlay list-card">
                                    <i class='bx bx-play-circle'></i>
                                </div>
                                <img src="<?php echo $row['album']; ?>" alt="Song">
                                <div class="song-info">
                                    <p class="baihat"><?php echo $row['tenbaihat']; ?></p>
                                    <br>
                                    <p class="casi">(<?php echo $row['tenCaSi']; ?>)</p>
                                </div>
                                <!-- Ẩn để tiện cho việt lấy nội sung thay cho main song -->
                                <div class="info-song-hidden-list ">
                                    <p class="nghesi-list"> <?php echo $row['ngheSi']; ?> </p>
                                    <p class="mota-list"> <?php echo $row['moTa']; ?></p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                
            </div>
        </div>
    </div>

    <div class="container-singer">
        <?php if ($singerInfo): ?>
            <img src="<?php echo $singerInfo['linkAnh']; ?>" alt="<?php echo $singerInfo['tenCaSi']; ?>">
            <div class="container-singer-info">
                <h3>Thông tin ca sĩ</h3>
                <p><?php echo $singerInfo['tenCaSi']; ?></p>
            </div>
        <?php else: ?>
            <p>Không có thông tin ca sĩ.</p>
        <?php endif; ?>
    </div>

    <?php include 'player.php'; ?>
    <?php include 'footer.php'; ?>
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

        // Hàm phát nhạc chính
        function playMusic(card) {
            // console.log(card);
            // Xóa class playing từ tất cả card 
            document.querySelectorAll('.song-card').forEach(c => {
                c.classList.remove('playing');
            });

            const audioSrc = card.getAttribute('data-audio');
            const currentImg = card.querySelector('img');
            const playButtonCard = card.querySelector('.play-overlay i');
            const mainPlayOverlay = document.querySelector('.main-song .play-overlay i');
            const songTitle = card.querySelector(".baihat").textContent;
            const artistName = card.querySelector(".casi").textContent;

            // Thêm class playing vào card được chọn
            card.classList.add('playing');

            // Cập nhật UI
            document.getElementById('song-name').textContent = songTitle;
            document.getElementById('artist-name').textContent = artistName;
            containerPlay.classList.add('show');
            imgScrThanhNhac.src = currentImg.src;

            if (card.closest('.song-list')) {
                updateMainSong(card);
            }


            // Phát nhạc
            currentCard = card;
            audioPlayer.src = audioSrc;
            audioPlayer.currentTime = 0;
            audioPlayer.play();

            // Cập nhật icon
            document.querySelectorAll('.play-overlay i').forEach(icon => {
                icon.className = 'bx bx-play-circle';
            });
            mainPlayOverlay.className = 'bx bx-pause-circle';
            playButtonCard.className = 'bx bx-pause-circle';
            playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";
        }

        // Event listeners cho card
        songCards.forEach((card, index) => {
            const playOverlay = card.querySelector('.play-overlay');
            const mainPlayOverlay = document.querySelector('.main-song .play-overlay');

            // Click vào nút play trên card
            playOverlay.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                // Kiểm tra nếu card này đang phát nhạc
                if (currentCard !== null) {
                    if (currentCard.dataset.audio === card.dataset.audio) {
                        console.log('Đang phát bài này');
                        // Toggle play/pause mà không xóa class playing 
                        if (!audioPlayer.paused) {
                            console.log('dừng lại');
                            audioPlayer.pause();
                            playOverlay.querySelector('i').className = 'bx bx-play-circle';
                            mainPlayOverlay.querySelector('i').className = 'bx bx-play-circle';
                            playButton.innerHTML = "<i class='bx bx-play-circle'></i>";
                            card.classList.add('playing');
                        } else {
                            console.log('phat lại');
                            audioPlayer.play();
                            playOverlay.querySelector('i').className = 'bx bx-pause-circle';
                            mainPlayOverlay.querySelector('i').className = 'bx bx-pause-circle';
                            playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";
                            card.classList.add('playing');
                        }
                        return;
                    }
                }
                currentIndex = index;
                playMusic(card);
            });
        });

        // Xử lý điều khiển phát nhạc
        playButton.addEventListener('click', () => {
            if (!currentCard) return;
            const playButtonCard = currentCard.querySelector('.play-overlay i');
            const mainPlayOverlay = document.querySelector('.main-song .play-overlay i');

            if (audioPlayer.paused) {
                audioPlayer.play();
                playButtonCard.className = 'bx bx-pause-circle';
                mainPlayOverlay.className = 'bx bx-pause-circle';
                playButton.innerHTML = "<i class='bx bx-pause-circle'></i>";
            } else {
                audioPlayer.pause();
                mainPlayOverlay.className = 'bx bx-play-circle';
                playButtonCard.className = 'bx bx-play-circle';
                playButton.innerHTML = "<i class='bx bx-play-circle'></i>";
            }
        });

        // Xử lý next/prev
        document.getElementById('prev').addEventListener('click', () => {
            // chuyển list songCard thành mảng
            const cards = Array.from(songCards);
            // kiểm tra nếu không có bài hát nào đang phát thì không làm gì cả
            if (!currentCard) return;
            // nếu có thì chuyển index về bài trước đó
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

        function updateMainSong(card) {
            const mainSong = document.querySelector('.main-song .song-card');
            const mainSongInfo = document.querySelector('.song-info.show-main');
            const mainSongInfoMota = document.querySelector('.info-song');

            // Cập nhật thông tin cho main-song card
            mainSong.setAttribute('data-audio', card.getAttribute('data-audio'));
            mainSong.querySelector('img').src = card.querySelector('img').src;
            mainSong.querySelector('.baihat').textContent = card.querySelector('.baihat').textContent;
            mainSong.querySelector('.casi').textContent = card.querySelector('.casi').textContent;



            // Cập nhật thông tin hiển thị bên dưới
            mainSongInfo.querySelector('.show-main-text').textContent = card.querySelector('.baihat').textContent;
            mainSongInfo.querySelector('.show-main-text.casi').textContent = card.querySelector('.casi').textContent;

            // thông tin mô tả
            mainSongInfoMota.querySelector('.nghesi').textContent = card.querySelector('.nghesi-list').textContent;
            mainSongInfoMota.querySelector('.mota').textContent = card.querySelector('.mota-list').textContent;


        }
    </script>


</body>

</html>