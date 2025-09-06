<script setup>

</script>
<template>
    <div class="flex flex-col items-center min-h-64">
        <iframe src="https://player.twitch.tv/?channel=fibremusic&parent=rook.fm" frameborder="0" allowfullscreen="true" scrolling="no" height="378" width="620" class="w-full h-[20rem]"></iframe>
        <details class="w-full">
            <summary class="bg-black px-2 py-1 rounded text-white w-full mt-2 cursor-pointer">Chat</summary>
            <iframe id="twitch-chat-embed"
                    src="https://www.twitch.tv/embed/fibremusic/chat?parent=rook.fm"
                    height="500"
                    width="350" class="w-full h-[32rem]">
            </iframe>
        </details>
    </div>
</template>
