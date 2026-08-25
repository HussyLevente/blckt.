# Vegigvezeto videok / Walkthrough videos

Drop a walkthrough recording here and the project page picks it up on the next
request. No code change is needed.

## Naming

    {slug}_walkthrough.mp4      (preferred)
    {slug}_walkthrough.webm     (fallback, checked second)

Current slugs: `paradise`, `palesso`, `kepszakadas`, `juiced`.
Example: `paradise_walkthrough.mp4`

## Until a file exists

The player renders the project's `_after` screenshot as a poster with a
"Walkthrough recording soon" badge. Nothing 404s and no empty box appears.

## Recommended encode

- 1920x1080 or 1280x720, H.264 (`-c:v libx264 -crf 23 -preset slow`)
- 45-90 seconds, no audio track needed (the player has no sound cue)
- `-movflags +faststart` so playback can begin before the file finishes loading
- Keep it under ~8 MB: the file is only fetched after the visitor clicks play,
  but it is still a mobile download.

    ffmpeg -i raw.mov -c:v libx264 -crf 23 -preset slow -an \
           -vf scale=1280:-2 -movflags +faststart paradise_walkthrough.mp4

Update `video_duration` for the project in `WebsiteProjectController` so the
play button shows the right running time.
