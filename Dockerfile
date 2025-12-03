# ---- نسخه سبک و سریع (پیشنهادی) ----
FROM php:8.2-cli

# نصب ابزارهای لازم برای دانلود یوتیوب (yt-dlp و ffmpeg اگر سورست نیاز داره)
RUN apt-get update && apt-get install -y \
    ffmpeg \
    curl \
    git \
    && curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp \
    && chmod a+rx /usr/local/bin/yt-dlp \
    && rm -rf /var/lib/apt/lists/*

# کپی کردن همه فایل‌های سورست
COPY . /var/www/html
WORKDIR /var/www/html

# پورت Render
EXPOSE $PORT

# دستور شروع (مهم‌ترین خط)
CMD php -S 0.0.0.0:$PORT -t .
