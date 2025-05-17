<?php
// Hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Util/database1.php';
require 'DAO/ArtistDAO.php';

// Khởi tạo biến mặc định
$artist = null;
$songs = null;
$albumCasi = null;
$error_message = null;

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception("Không thể kết nối đến database");
    }

    // Lấy ID nghệ sĩ từ URL
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        throw new Exception("ID nghệ sĩ không hợp lệ");
    }

    $dao = new ArtistDAO($conn);
    $artist = $dao->getArtistById($id);

    if (!$artist) {
        throw new Exception("Không tìm thấy nghệ sĩ với ID: $id");
    }

    // Lấy danh sách bài hát và album
    $songs = $dao->getSongsByArtistId($id);
    $albumCasi = $dao->getAlbumsByArtistId($id);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết nghệ sĩ - <?php echo htmlspecialchars($artist['tenCaSi'] ?? ''); ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/playMusic.css">
    <link rel="stylesheet" href="CSS/songCard.css">
    <!-- <link rel="stylesheet" href="CSS/songCard.css"> -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 130px;
        }

        .error {
            color: red;
            padding: 20px;
            background-color: #ffeeee;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .artist-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .artist-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .artist-info {
            flex: 1;
        }

        .artist-name {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        /* Phần tiểu sử */
        .artist-bio {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .bio-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .bio-content {
            color: #555;
            line-height: 1.8;
        }

        .album-l {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 25px 0;
        }

        .album-l img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .album-l img:hover {
            transform: scale(1.05);
        }

        .section-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .song-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        /* .song-card {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .song-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .song-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .song-card:hover .play-overlay {
            opacity: 1;
        }

        .play-overlay i {
            font-size: 50px;
            color: white;
            transition: transform 0.3s;
        }

        .song-card:hover .play-overlay i {
            transform: scale(1.1);
        }

        .song-info {
            padding: 15px;
        }

        .song-info .baihat {
            font-weight: bold;
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .song-info .casi {
            color: #7f8c8d;
            font-size: 0.9rem;
        } */

        .no-songs {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .artist-header {
                flex-direction: column;
                text-align: center;
            }

            .artist-avatar {
                margin-right: 0;
                margin-bottom: 20px;
            }

            .song-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .artist-name {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="container">
        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>

        <?php elseif ($artist): ?>
            <div class="artist-header">
                <?php
                // Lấy ảnh đại diện từ album đầu tiên hoặc ảnh mặc định
                $avatar = 'https://via.placeholder.com/150x150?text=Avatar';
                if ($albumCasi && $albumCasi->num_rows > 0) {
                    $firstAlbum = $albumCasi->fetch_assoc();
                    $avatar = htmlspecialchars($firstAlbum['linkAnh']);
                    // Đưa con trỏ trở lại đầu kết quả để có thể lặp lại sau này
                    $albumCasi->data_seek(0);
                }
                ?>
                <img src="<?php echo $avatar; ?>" alt="<?php echo htmlspecialchars($artist['tenCaSi']); ?>" class="artist-avatar">

                <div class="artist-info">
                    <h1 class="artist-name"><?php echo htmlspecialchars($artist['tenCaSi']); ?></h1>

                    <!-- Phần tiểu sử nghệ sĩ -->
                    <?php if (!empty($artist['tieusu'])): ?>
                        <div class="artist-bio">
                            <div class="bio-title">Tiểu sử nghệ sĩ</div>
                            <div class="bio-content"><?php echo nl2br(htmlspecialchars($artist['tieusu'])); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hiển thị ảnh album -->
            <?php if ($albumCasi && $albumCasi->num_rows > 0): ?>
                <h2 class="section-title">Albums</h2>
                <div class="album-l">
                    <?php while ($row = $albumCasi->fetch_assoc()): ?>
                        <img src="<?php echo htmlspecialchars($row['linkAnh']); ?>"
                            alt="<?php echo htmlspecialchars($artist['tenCaSi']); ?>">
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <!-- Hiển thị danh sách bài hát -->
            <h2 class="section-title">Bài hát</h2>
            <div class="song-list">
                <?php if ($songs && $songs->num_rows > 0): ?>
                    <div class="song-grid">
                        <?php while ($song = $songs->fetch_assoc()): ?>
                            <div class="song-card" data-audio="<?php echo htmlspecialchars($song['linknhac']); ?>" data-id="<?php echo $song['id_baihat'];?>">
                                <div class="play-overlay">
                                    <i class='bx bx-play-circle'></i>
                                </div>
                                <img src="<?php
                                            echo htmlspecialchars(
                                                $song['album'] ??
                                                    $song['album'] ??
                                                    'https://via.placeholder.com/300x300?text=No+Image'
                                            );
                                            ?>" alt="<?php echo htmlspecialchars($song['tenbaihat']); ?>">
                                <div class="song-info">
                                    <p class="baihat"><?php echo htmlspecialchars($song['tenbaihat']); ?></p>
                                    <p class="casi"><?php echo htmlspecialchars($artist['tenCaSi']); ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-songs">
                        <p>Nghệ sĩ này chưa có bài hát nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'player.php' ?>
    <?php include 'footer.php' ?>

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
</body>

</html>