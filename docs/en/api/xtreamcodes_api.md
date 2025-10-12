<h1 align="center">📡 XtreamCodes API</h1>

<p align="center">
  The API provides access to IPTV streams (Live TV, Radio), VOD (Movies), Series, and EPG (TV guide) for applications compatible with XtreamCodes.  
  It is a core component for integration with <b>XC_VM</b>.
</p>

<p align="center">
  <a href="../../en/main-page.md"><b>⬅️ Back to Main Page</b></a>
</p>

---

## 📚 Navigation

* [📂 File Location](#file-location)
* [📑 Overview](#overview)
* [🔑 Authorization](#authorization)
* [📺 Live TV (TV & Radio)](#live-tv-tv--radio)
* [🎬 VOD (Movies)](#vod-movies)
* [📽 Series](#series)
* [🎞 Media Access](#media-access)
* [🧾 Notes](#notes)

---

## 📂 File Location

The main API file is located at:

```
/home/xc_vm/wwwdir/player_api.php
```

---

## 📑 Overview

The API is divided into key sections for easier integration.
Each endpoint includes example requests and responses.

---

## 🔑 Authorization

Authorization is the first step to access content.
It validates user credentials and returns server information.

### Request

```http
GET /player_api?username={username}&password={password}
```

### Example Response

```json
{
  "user_info": {
    "username": "testxc",
    "password": "testxc",
    "message": "Welcome to XC_VM",
    "auth": 1,
    "status": "Active",
    "exp_date": null,
    "is_trial": 0,
    "created_at": 1757353729,
    "max_connections": 1,
    "allowed_output_formats": [
      "m3u8",
      "ts",
      "rtmp"
    ]
  },
  "server_info": {
    "xui": true,
    "version": "1.1.0",
    "url": "176.124.192.118",
    "port": "80",
    "https_port": "443",
    "server_protocol": "http",
    "rtmp_port": "8880",
    "timestamp_now": 1757442189,
    "time_now": "2025-09-09 19:23:09",
    "timezone": "Europe/London"
  }
}
```

> 💡 **Tip:** Use the obtained data for subsequent requests to avoid repeated authentication.

---

## 📺 Live TV (TV & Radio)

Endpoints for working with live streams, including categories, streams, and EPG.

### Get All Live Categories

```http
GET /player_api?username={username}&password={password}&action=get_live_categories
```

**Example Response**

```json
[
  {
    "category_id": "1",
    "category_name": "News",
    "parent_id": 0
  },
  {
    "category_id": "2",
    "category_name": "Sports",
    "parent_id": 0
  }
]
```

### Get All Live Streams

```http
GET /player_api?username={username}&password={password}&action=get_live_streams
```

**Example Response**

```json
[
  {
    "num": 1,
    "name": "BBC News",
    "stream_type": "live",
    "stream_id": 101,
    "stream_icon": "http://176.124.192.118/images/bbc.png",
    "epg_channel_id": "bbc.news.uk",
    "added": "1660568200",
    "category_id": "1",
    "custom_sid": "",
    "tv_archive": 0,
    "direct_source": "",
    "tv_archive_duration": 0
  }
]
```

### Get Streams by Category

```http
GET /player_api?username={username}&password={password}&action=get_live_streams&category_id={id}
```

### Get Channel Short EPG

```http
GET /player_api?username={username}&password={password}&action=get_short_epg&stream_id={id}&limit={N}
```

**Example Response**

```json
{
  "epg_listings": [
    {
      "id": 1,
      "title": "Morning News",
      "start": "2022-08-15 07:00:00",
      "end": "2022-08-15 08:00:00",
      "description": "Daily morning news update."
    }
  ]
}
```

### Get Full Channel Schedule

```http
GET /player_api?username={username}&password={password}&action=get_simple_data_table&stream_id={id}
```

### Get EPG for All Channels (XMLTV)

```http
GET /xmltv.php?username={username}&password={password}
```

**Example Response (XMLTV)**

```xml
<tv>
  <channel id="bbc.news.uk">
    <display-name>BBC News</display-name>
  </channel>
  <programme start="20220815070000 +0000" stop="20220815080000 +0000" channel="bbc.news.uk">
    <title>Morning News</title>
    <desc>Daily morning news update.</desc>
  </programme>
</tv>
```

---

## 🎬 VOD (Movies)

Endpoints for Video on Demand, including categories and movie details.

### Get Movie Categories

```http
GET /player_api?username={username}&password={password}&action=get_vod_categories
```

**Example Response**

```json
[
  {
    "category_id": "10",
    "category_name": "Action",
    "parent_id": 0
  },
  {
    "category_id": "11",
    "category_name": "Drama",
    "parent_id": 0
  }
]
```

> ⚠️ **Note:** The full list of categories may be large; only fragments are shown in examples.

### Get All VOD Streams

```http
GET /player_api?username={username}&password={password}&action=get_vod_streams
```

**Example Response**

```json
[
  {
    "num": 1,
    "name": "The Dark Knight (2008)",
    "title": "The Dark Knight",
    "year": 2008,
    "stream_type": "movie",
    "stream_id": 1,
    "stream_icon": "http://176.124.192.118:80/images/...jpg",
    "rating": 8.5,
    "rating_5based": 4.3,
    "added": 1757343129,
    "plot": "Batman raises the stakes in his war on crime. With the ...",
    "cast": "Christian Bale, Heath Ledger, Aaron Eckhart, Michael Caine, Maggie Gyllenhaal",
    "director": "Christopher Nolan, Christopher Nolan, Steve Gehrke",
    "genre": "Drama, Action, Crime",
    "release_date": "2008-07-16",
    "youtube_trailer": "kmJLuwP3MbY",
    "episode_run_time": "152",
    "category_id": "1",
    "category_ids": [1, 2],
    "container_extension": "mp4",
    "custom_sid": "",
    "direct_source": ""
  }
]
```

### Get Movies by Category

```http
GET /player_api?username={username}&password={password}&action=get_vod_streams&category_id={id}
```

### Get Movie Info

```http
GET /player_api?username={username}&password={password}&action=get_vod_info&vod_id={id}
```

---

## 📽 Series

Endpoints for managing TV shows, including categories, seasons, and episodes.

### Get Series Categories

```http
GET /player_api?username={username}&password={password}&action=get_series_categories
```

### Get All Series

```http
GET /player_api?username={username}&password={password}&action=get_series
```

**Example Response**

```json
[
  {
    "num": 1,
    "name": "Braceface (2001)",
    "title": "Braceface",
    "year": 2001,
    "stream_type": "series",
    "series_id": 1,
    "cover": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCLaoCzC2XshRjzbEF-0-ISX3OBAKZPN21ASjMFFF_OljpT6mCZeHa4zLXMQZ2eaTpGZUSOucptPKyuP5tgpEZm0.jpg",
    "plot": "The show, set in Elkford, British Columbia, is based around Sharon Spitz, who is a junior high school student with braces that get in her way of leading a normal teenage life. In the first season, she is enrolled at Mary Pickford Junior High.",
    "cast": "Stacey DePass",
    "director": "Charles E. Bastien",
    "genre": "Drama, Animation, Comedy",
    "release_date": "2001-06-02",
    "releaseDate": "2001-06-02",
    "last_modified": "1757348651",
    "rating": "7",
    "rating_5based": 3.5,
    "backdrop_path": [
      "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCADBzwKB171qpiTTqrZdeATBaSzxZEgi9EPIvh5kU_50ecbH2L-yDt1PIfqijKTIMg.jpg"
    ],
    "youtube_trailer": null,
    "episode_run_time": 25,
    "category_id": "4",
    "category_ids": [4]
  }
]
```

---

### Series by Category

```http
GET /player_api?username={username}&password={password}&action=get_series&category_id={id}
```

### Get Series Info

```http
GET /player_api?username={username}&password={password}&action=get_series_info&series_id={id}
```

**Example Response**

```json
{
  "seasons": [
    {
      "air_date": "2001-06-02",
      "episode_count": 26,
      "id": 4937,
      "name": "Season 1",
      "overview": "",
      "season_number": 1,
      "vote_average": 4.5,
      "cover": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCLaoCzC2XshRjzbEF-0-ISX3OBAKZPN21ASjMFFF_OljJBrZ7sNEJHE6hlqofFOJSRKiMRvSj08-T6iTQVWvj2I.jpg",
      "cover_big": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCLaoCzC2XshRjzbEF-0-ISX3OBAKZPN21ASjMFFF_OljJBrZ7sNEJHE6hlqofFOJSRKiMRvSj08-T6iTQVWvj2I.jpg"
    }
  ],
  "info": {
    "name": "Braceface (2001)",
    "title": "Braceface",
    "year": 2001,
    "cover": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCLaoCzC2XshRjzbEF-0-ISX3OBAKZPN21ASjMFFF_OljpT6mCZeHa4zLXMQZ2eaTpGZUSOucptPKyuP5tgpEZm0.jpg",
    "plot": "The show, set in Elkford, British Columbia, is based around Sharon Spitz, who is a junior high school student with braces that get in her way of leading a normal teenage life. In the first season, she is enrolled at Mary Pickford Junior High.",
    "cast": "Stacey DePass",
    "director": "Charles E. Bastien",
    "genre": "Drama, Animation, Comedy",
    "release_date": "2001-06-02",
    "releaseDate": "2001-06-02",
    "last_modified": "1757348651",
    "rating": "7",
    "rating_5based": 3.5,
    "backdrop_path": [
      "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCADBzwKB171qpiTTqrZdeATBaSzxZEgi9EPIvh5kU_50ecbH2L-yDt1PIfqijKTIMg.jpg"
    ],
    "youtube_trailer": null,
    "episode_run_time": 25,
    "category_id": "4",
    "category_ids": [4]
  },
  "episodes": {
    "1": [
      {
        "id": "2",
        "episode_num": "1",
        "title": "Braceface - S01E01 - Brace Yourself",
        "container_extension": "mp4",
        "info": {
          "release_date": "2001-06-02",
          "plot": "Sharon Spitz, a 13-year-old 8th grader gets braces the day before picture day. During the procedure, a thunderstorm causes a power outage, and something strange happens with Sharon's braces, making her do all sorts of weird things!",
          "duration_secs": 649,
          "duration": "00:10:49",
          "movie_image": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCADBzwKB171qpiTTqrZdeARCK8Ch0a6wlNxuIHhbJ3c1Dg_AqkwSsH-qdpnCvcMsiy6U85vvi1bs1xDOddBeOEQ.jpg",
          "bitrate": 2791,
          "rating": "5",
          "season": "1",
          "tmdb_id": "105800",
          "cover_big": "http://176.124.192.118:80/images/7pQr8EhEi05VXRmZc5QfCADBzwKB171qpiTTqrZdeARCK8Ch0a6wlNxuIHhbJ3c1Dg_AqkwSsH-qdpnCvcMsiy6U85vvi1bs1xDOddBeOEQ.jpg"
        },
        "subtitles": [],
        "custom_sid": "",
        "added": 1757348651,
        "season": 1,
        "direct_source": ""
      }
    ]
  }
}
```

---

## 🎞 Media Access

After authorization, you can access direct links to media files.

* **Live TV (channel):**

```http
http://176.124.192.118/live/{username}/{password}/{stream_id}.ts
```

* **Movie (VOD):**

```http
http://176.124.192.118/movie/{username}/{password}/{vod_id}.mp4
```

* **Episode:**

```http
http://176.124.192.118/series/{username}/{password}/{episode_id}.mp4
```

> ⚠️ **Important:** On the first request, a redirect occurs to `/auth/...` for authentication before content is served.

---

## 🧾 Notes

* **Output Formats:** Supported formats — `m3u8`, `ts`, `rtmp`. Choose based on your device.
* **Security:** All requests require valid authorization; monitor logs for access errors.
* **Integration:** Use tools like Postman for testing.
  Implement response caching in your application for better performance.

> 💬 If you have integration issues, please open an issue in the [repository](https://github.com/Vateron-Media/XC_VM/issues).

---

<p align="center">
  <a href="../../en/main-page.md"><b>⬅️ Back to Main Page</b></a>
</p>
